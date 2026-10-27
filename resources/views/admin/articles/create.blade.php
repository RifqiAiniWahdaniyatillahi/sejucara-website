@extends('admin.layouts.app')

@section('content')
    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <h1>Tambah Artikel</h1>
        <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.articles._form', ['submitLabel' => 'Simpan Artikel'])
        </form>
    </div>
@endsection
