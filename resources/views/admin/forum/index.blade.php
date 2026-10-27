@extends('admin.layouts.app')

@section('content')
    <h1>Moderasi Forum Diskusi</h1>

    <div class="card">
        <table>
            <thead>
                <tr>
                    <th>Topik</th>
                    <th>Pembuat</th>
                    <th>Balasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($threads as $thread)
                    <tr>
                        <td>{{ $thread->title }}</td>
                        <td>{{ $thread->user->name }}</td>
                        <td>{{ $thread->replies_count }}</td>
                        <td>
                            <div class="actions">
                                <a class="btn-primary" href="{{ route('admin.forum.show', $thread) }}">Lihat</a>
                                <form action="{{ route('admin.forum.destroy', $thread) }}" method="POST" onsubmit="return confirm('Yakin hapus thread ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn-danger" type="submit">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="muted">Belum ada thread forum.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div style="margin-top: 14px;">{{ $threads->links() }}</div>
    </div>
@endsection
