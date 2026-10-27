<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ForumReply;
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
        $threads = ForumThread::with('user')->withCount('replies')->latest()->paginate(10);

        return view('admin.forum.index', compact('threads'));
    }

    public function show(ForumThread $thread): View
    {
        $thread->load([
            'user',
            'replies' => fn ($query) => $query->oldest()->withCount('likes'),
            'replies.user',
        ]);

        return view('admin.forum.show', compact('thread'));
    }

    public function destroyThread(ForumThread $thread): RedirectResponse
    {
        $thread->delete();

        return redirect()->route('admin.forum.index')->with('success', 'Thread forum berhasil dihapus.');
    }

    public function storeReply(Request $request, ForumThread $thread): RedirectResponse
    {
        $validated = $request->validate([
            'body' => ['required', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $imagePath = $this->storeForumImage($request);

        ForumReply::create([
            'forum_thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
            'image_path' => $imagePath,
        ]);

        if ($thread->user_id !== $request->user()->id) {
            $notification = UserNotification::create([
                'user_id' => $thread->user_id,
                'type' => 'forum',
                'title' => 'Pertanyaan forum dibalas admin',
                'message' => 'Admin membalas diskusi "'.$thread->title.'".',
                'url' => route('forum.show', $thread),
            ]);

            $this->sendEmailNotification($thread->user, $notification);
        }

        return redirect()->route('admin.forum.show', $thread)->with('success', 'Balasan admin berhasil ditambahkan.');
    }

    public function destroyReply(ForumThread $thread, ForumReply $reply): RedirectResponse
    {
        if ($reply->forum_thread_id !== $thread->id) {
            abort(404);
        }

        $reply->delete();

        return redirect()->route('admin.forum.show', $thread)->with('success', 'Balasan berhasil dihapus.');
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
