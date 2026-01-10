<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Product</title>
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
    </style>
</head>
<body>

<div class="container">
    <h2>Create Product</h2>

    <a href="{{ route('products.index') }}">Back to list</a>

    <br><br>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <label>
            Product Name
            <input type="text" name="name" value="{{ old('name') }}" required>
        </label>

        <label>
            Category
            <select name="category_id" required>
                <option value="">Select Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </label>

        <label>
            Status
            <input type="checkbox" name="status" value="1" checked>
            Active
        </label>

        <label>
            Thumbnail
            <input type="file" name="thumbnail">
        </label>

        <br>

        <button type="submit">Create Product</button>
    </form>
</div>

</body>
</html>