<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\ForumReply;
use App\Models\ForumReplyLike;
use App\Models\ForumThread;
use App\Models\UserNotification;
use App\Notifications\UserActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ForumController extends Controller
{
    public function index(): View
    {
        $threads = ForumThread::with(['user', 'latestReply.user'])
            ->withCount('replies')
            ->withExists([
                'replies as has_admin_reply' => fn ($query) => $query->whereHas(
                    'user',
                    fn ($userQuery) => $userQuery->where('is_admin', true)
                ),
            ])
            ->latest()
            ->paginate(10);

        return view('user.forum.index', compact('threads'));
    }

    public function show(ForumThread $thread): View
    {
        $thread->load([
            'user',
            'replies' => fn ($query) => $query->oldest()->withCount('likes'),
            'replies.user',
            'replies.likes' => fn ($query) => $query->where('user_id', auth()->id()),
        ]);

        return view('user.forum.show', compact('thread'));
    }

    public function storeThread(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:8', 'max:255'],
            'body' => ['required', 'string', 'min:20', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $this->storeForumImage($request);

        ForumThread::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'image_path' => $imagePath,
        ]);

        return redirect()->route('forum.index')->with('success', 'Pertanyaan berhasil diposting.');
    }

    public function storeReply(Request $request, ForumThread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string', 'min:5', 'max:3000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $this->storeForumImage($request);

        ForumReply::create([
            'forum_thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'image_path' => $imagePath,
        ]);

        if ($request->user()->is_admin && $thread->user_id !== $request->user()->id) {
            $notification = UserNotification::create([
                'user_id' => $thread->user_id,
                'type' => 'forum',
                'title' => 'Pertanyaan forum dibalas admin',
                'message' => 'Admin membalas diskusi "'.$thread->title.'".',
                'url' => route('forum.show', $thread),
            ]);

            $this->sendEmailNotification($thread->user, $notification);
        }

        return redirect()->route('forum.show', $thread)->with('success', 'Balasan berhasil ditambahkan.');
    }

    public function toggleReplyLike(Request $request, ForumReply $reply): RedirectResponse
    {
        $like = ForumReplyLike::where('forum_reply_id', $reply->id)
            ->where('user_id', $request->user()->id)
            ->first();

        if ($like) {
            $like->delete();

            return back()->with('success', 'Like pada balasan berhasil dibatalkan.');
        }

        ForumReplyLike::create([
            'forum_reply_id' => $reply->id,
            'user_id' => $request->user()->id,
        ]);

        return back()->with('success', 'Balasan berhasil disukai.');
    }

    private function sendEmailNotification($user, UserNotification $notification): void
    {
        if (! $user || blank($user->email)) {
            return;
        }

        try {
            $user->notify(new UserActivityNotification(
                $notification->title,
                $notification->message,
                $notification->url
            ));
        } catch (Throwable $exception) {
            logger()->warning('Email notifikasi forum belum bisa dikirim.', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    private function storeForumImage(Request $request): ?string
    {
        $image = $request->file('image');

        if (! $image) {
            return null;
        }

        $path = $image->store('forum', 'public');
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
