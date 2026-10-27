<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(): View
    {
        $conversations = ChatConversation::query()
            ->with(['user:id,name,email', 'latestMessage.sender:id,name,is_admin'])
            ->withCount([
                'messages as unread_count' => fn ($query) => $query
                    ->whereHas('sender', fn ($sender) => $sender->where('is_admin', false))
                    ->whereNull('read_at'),
            ])
            ->latest('updated_at')
            ->paginate(15);

        return view('admin.chat.index', compact('conversations'));
    }

    public function show(Request $request, ChatConversation $conversation): View
    {
        $conversation->load('user:id,name,email');
        $this->markUserMessagesAsRead($conversation);

        $messages = $conversation->messages()
            ->with('sender:id,name,is_admin')
            ->oldest()
            ->get();

        return view('admin.chat.show', compact('conversation', 'messages'));
    }

    public function store(Request $request, ChatConversation $conversation): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'message' => ['nullable', 'required_without:image', 'string', 'max:2000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ]);

        $message = $conversation->messages()->create([
            'sender_id' => $request->user()->id,
            'message' => $validated['message'] ?? '',
            'image_path' => $this->storeChatImage($request),
        ]);

        $conversation->touch();

        if ($request->expectsJson()) {
            return response()->json($this->messageData($message->load('sender')));
        }

        return back()->with('success', 'Balasan berhasil dikirim.');
    }

    public function messages(Request $request, ChatConversation $conversation): JsonResponse
    {
        $this->markUserMessagesAsRead($conversation);

        $messages = $conversation->messages()
            ->with('sender:id,name,is_admin')
            ->when($request->integer('after') > 0, fn ($query) => $query->where('id', '>', $request->integer('after')))
            ->oldest()
            ->get()
            ->map(fn (ChatMessage $message) => $this->messageData($message));

        return response()->json(['messages' => $messages]);
    }

    private function markUserMessagesAsRead(ChatConversation $conversation): void
    {
        $conversation->messages()
            ->whereHas('sender', fn ($query) => $query->where('is_admin', false))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    private function messageData(ChatMessage $message): array
    {
        return [
            'id' => $message->id,
            'message' => $message->message,
            'image_url' => $message->image_path ? Storage::url($message->image_path) : null,
            'sender_name' => $message->sender->is_admin ? 'Admin Sejucara' : $message->sender->name,
            'is_admin' => (bool) $message->sender->is_admin,
            'is_mine' => (int) $message->sender_id === (int) auth()->id(),
            'time' => $message->created_at->translatedFormat('d M Y, H:i'),
            'read' => (bool) $message->read_at,
        ];
    }

    private function storeChatImage(Request $request): ?string
    {
        $image = $request->file('image');

        if (! $image) {
            return null;
        }

        $path = $image->store('chat-images', 'public');
        $source = Storage::disk('public')->path($path);

        foreach ($this->publicStorageRoots() as $root) {
            $target = $root.DIRECTORY_SEPARATOR.$path;
            File::ensureDirectoryExists(dirname($target));
            File::copy($source, $target);
        }

        return $path;
    }

    private function publicStorageRoots(): array
    {
        $roots = [public_path('storage')];

        if (app()->environment('production')) {
            $roots[] = base_path('sejucara.my.id/storage');
            $roots[] = base_path('../sejucara.my.id/storage');
        }

        return collect($roots)->unique()->values()->all();
    }
}
