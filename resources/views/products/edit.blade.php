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
            background-color: #fff;
        }
        .container {
            max-width: 600px;
            width: 100%;
            padding: 20px 30px;
            border-radius: 6px;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        input[type="text"],
        input[type="number"],
        select,
        textarea,
        input[type="file"] {
            border: 1px solid black;
            padding: 8px 10px;
            width: 100%;
            box-sizing: border-box;
            border-radius: 3px;
            font-size: 14px;
            font-family: inherit;
        }
        input[type="checkbox"] {
            margin-left: 0;
            margin-right: 5px;
            vertical-align: middle;
            width: auto;
        }
        textarea {
            resize: vertical;
        }
        img {
            display: block;
            margin-top: 10px;
            max-width: 120px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        .success {
            color: green;
            margin-bottom: 10px;
        }
        button {
            margin-top: 20px;
            background-color: black;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #333;
        }
        a {
            color: black;
            text-decoration: none;
            font-weight: 600;
        }
        a:hover {
            text-decoration: underline;
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
            Price
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" required>
        </label>

        <label>
            Description
            <textarea name="description" rows="4" cols="50" required>{{ old('description', $product->description ?? '') }}</textarea>
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