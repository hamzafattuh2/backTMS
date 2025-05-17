<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 30px;
            background-color: #f7f9fc;
            color: #333;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #27ae60;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        a.button:hover {
            background-color: #219150;
        }

        .success-message {
            color: #2ecc71;
            font-weight: bold;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions a {
            color: #2980b9;
            margin-right: 10px;
            text-decoration: none;
        }

        .actions a:hover {
            text-decoration: underline;
        }

        .actions button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .actions button:hover {
            background-color: #c0392b;
        }

        .actions form {
            display: inline;
        }
    </style>
</head>
<body>
    <h2>Restaurants</h2>

    <a href="{{ route('admin.restaurants.create') }}" class="button">Add New Restaurant</a>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Cuisine</th>
                <th>Price Range</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($restaurants as $restaurant)
                <tr>
                    <td>{{ $restaurant->name }}</td>
                    <td>{{ $restaurant->location }}</td>
                    <td>{{ $restaurant->cuisine }}</td>
                    <td>{{ $restaurant->price_range }}</td>
                    <td>{{ $restaurant->is_active ? 'Yes' : 'No' }}</td>
                    <td class="actions">
                        <a href="{{ route('admin.restaurants.edit', $restaurant) }}">Edit</a>
                        <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" method="POST" onsubmit="return confirm('Delete this restaurant?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
