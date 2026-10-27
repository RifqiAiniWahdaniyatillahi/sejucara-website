@extends('admin.layouts.app')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <h1>Edit Artikel</h1>
        <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.articles._form', ['submitLabel' => 'Update Artikel'])
        </form>
    </div>
@endsection
