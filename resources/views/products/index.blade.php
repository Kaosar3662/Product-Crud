<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Products</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 1200px;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        img {
            max-width: 60px;
            height: auto;
        }
        a {
            margin-right: 6px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Products</h2>

    <a href="{{ route('products.create') }}">Add Product</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Thumbnail</th>
                <th>Name</th>
                <th>Category</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>

                    <td>
                        @if ($product->thumbnail)
                            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="thumbnail">
                        @else
                            No image
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>

                    <td>{{ $product->category->name ?? '-' }}</td>

                    <td>{{ $product->status ? 'Active' : 'Inactive' }}</td>

                    <td>
                        <a href="{{ route('products.edit', $product) }}">Edit</a>

                        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this product?')">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

</body>
</html>
