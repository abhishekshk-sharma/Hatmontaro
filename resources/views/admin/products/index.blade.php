@extends('admin.layouts.app')

@section('content')
<h2>Products</h2>
<a href="{{ route('admin.products.create') }}" class="btn btn-sm btn-success mb-3">Add Product</a>
<table class="table table-striped">
    <thead>
        <tr><th>ID</th><th>Name</th><th>Price</th><th>Category</th><th></th></tr>
    </thead>
    <tbody>
    @foreach($products as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->name }}</td>
            <td>₹{{ number_format($p->price,2) }}</td>
            <td>{{ $p->category->name ?? '' }}</td>
            <td>
                <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-primary">Edit</a>
                <form action="{{ route('admin.products.destroy', $p) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
 </table>
 {{ $products->links() }}
@endsection
