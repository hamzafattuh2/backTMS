<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hotels</title>
</head>
<body>
    <h2>Hotels</h2>
    <a href="{{ route('admin.hotels.create') }}">Add New Hotel</a>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Name</th>
                <th>Location</th>
                <th>Price/Night</th>
                <th>Stars</th>
                <th>Active</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hotels as $hotel)
                <tr>
                    <td>{{ $hotel->name }}</td>
                    <td>{{ $hotel->location }}</td>
                    <td>{{ $hotel->price_per_night }}</td>
                    <td>{{ $hotel->stars }}</td>
                    <td>{{ $hotel->is_active ? 'Yes' : 'No' }}</td>
                    <td>
                        <a href="{{ route('admin.hotels.edit', $hotel) }}">Edit</a>
                        <form action="{{ route('admin.hotels.destroy', $hotel) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this hotel?')">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
