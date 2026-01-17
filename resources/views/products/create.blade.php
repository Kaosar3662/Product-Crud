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
            background-color: #fff;
        }
        .container {
            max-width: 600px;
            width: 100%;
            padding: 25px 30px;
            border-radius: 6px;
            box-sizing: border-box;
        }
        label {
            display: block;
            margin-top: 15px;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
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
            border-radius: 4px;
            font-size: 14px;
            font-family: Arial, sans-serif;
        }
        textarea {
            resize: vertical;
        }
        input[type="checkbox"] {
            width: auto;
            margin-left: 5px;
            vertical-align: middle;
        }
        button {
            background-color: black;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 20px;
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
            Price
            <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>
        </label>

        <label>
            Description
            <textarea name="description" rows="4" cols="50" required>{{ old('description') }}</textarea>
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

        <button type="submit">Create Product</button>
    </form>
</div>

</body>
</html>