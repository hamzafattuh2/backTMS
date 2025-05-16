<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Hotel</title>
</head>
<body>
    <h2>Add New Hotel</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.hotels.store') }}">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Location:</label>
        <input type="text" name="location" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <label>Price per Night:</label>
        <input type="number" name="price_per_night" step="0.01" required><br>
        <label>Stars:</label>
        <input type="number" name="stars" min="1" max="5" required><br>
        <label>Amenities:</label>
        <input type="text" name="amenities"><br>
        <label>Contact Email:</label>
        <input type="email" name="contact_email"><br>
        <label>Contact Phone:</label>
        <input type="text" name="contact_phone"><br>
        <label>Active:</label>
        <input type="checkbox" name="is_active" value="1" checked><br>
        <button type="submit">Add Hotel</button>
    </form>
</body>
</html>
