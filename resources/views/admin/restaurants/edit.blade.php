<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Restaurant</title>
</head>
<body>
    <h2>Edit Restaurant</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.restaurants.update', $restaurant) }}">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $restaurant->name }}" required><br>
        <label>Location:</label>
        <input type="text" name="location" value="{{ $restaurant->location }}" required><br>
        <label>Description:</label>
        <textarea name="description">{{ $restaurant->description }}</textarea><br>
        <label>Cuisine:</label>
        <input type="text" name="cuisine" value="{{ $restaurant->cuisine }}"><br>
        <label>Price Range:</label>
        <input type="text" name="price_range" value="{{ $restaurant->price_range }}"><br>
        <label>Contact Email:</label>
        <input type="email" name="contact_email" value="{{ $restaurant->contact_email }}"><br>
        <label>Contact Phone:</label>
        <input type="text" name="contact_phone" value="{{ $restaurant->contact_phone }}"><br>
        <label>Active:</label>
        <input type="checkbox" name="is_active" value="1" {{ $restaurant->is_active ? 'checked' : '' }}><br>
        <button type="submit">Update Restaurant</button>
    </form>
</body>
</html>
