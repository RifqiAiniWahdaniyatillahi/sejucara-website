@extends('admin.layouts.app')

@section('content')
    <div class="card" style="max-width: 720px; margin: 0 auto;">
        <h1>Edit Produk</h1>
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @include('admin.products._form', ['submitLabel' => 'Update Produk'])
        </form>
    </div>
@endsection
