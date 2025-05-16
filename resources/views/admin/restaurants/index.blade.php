<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurants</title>
</head>
<body>
    <h2>Restaurants</h2>
    <a href="{{ route('admin.restaurants.create') }}">Add New Restaurant</a>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    <table border="1" cellpadding="5">
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
                    <td>
                        <a href="{{ route('admin.restaurants.edit', $restaurant) }}">Edit</a>
                        <form action="{{ route('admin.restaurants.destroy', $restaurant) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this restaurant?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
