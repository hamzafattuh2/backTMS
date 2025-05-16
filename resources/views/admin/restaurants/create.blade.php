<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Restaurant</title>
</head>
<body>
    <h2>Add New Restaurant</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.restaurants.store') }}">
        @csrf
        <label>Name:</label>
        <input type="text" name="name" required><br>
        <label>Location:</label>
        <input type="text" name="location" required><br>
        <label>Description:</label>
        <textarea name="description"></textarea><br>
        <label>Cuisine:</label>
        <input type="text" name="cuisine"><br>
        <label>Price Range:</label>
        <input type="text" name="price_range"><br>
        <label>Contact Email:</label>
        <input type="email" name="contact_email"><br>
        <label>Contact Phone:</label>
        <input type="text" name="contact_phone"><br>
        <label>Active:</label>
        <input type="checkbox" name="is_active" value="1" checked><br>
        <button type="submit">Add Restaurant</button>
    </form>
</body>
</html>
