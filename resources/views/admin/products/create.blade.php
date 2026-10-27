@extends('admin.layouts.app')

@section('content')
    <div class="card" style="max-width: 720px; margin: 0 auto;">
        <h1>Tambah Produk</h1>
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @include('admin.products._form', ['submitLabel' => 'Simpan Produk'])
        </form>
    </div>
@endsection
