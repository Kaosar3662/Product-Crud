<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Product</title>
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
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, button {
            margin-top: 5px;
            padding: 6px;
            width: 300px;
        }
        img {
            display: block;
            margin-top: 10px;
            max-width: 120px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Edit Product</h2>

    <a href="{{ route('products.index') }}">Back to list</a>

    <br><br>

    {{-- Success message --}}
    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    {{-- Error message --}}
    @if(session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>
            Product Name
            <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
        </label>

        <label>
            Category
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            Status
            <input type="checkbox" name="status" value="1" {{ $product->status ? 'checked' : '' }}>
            Active
        </label>

        <label>
            Thumbnail
            <input type="file" name="thumbnail">
        </label>

        @if ($product->thumbnail)
            <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="Current thumbnail">
        @endif

        <br>

        <button type="submit">Update Product</button>
    </form>
</div>

</body>
</html>