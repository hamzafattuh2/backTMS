<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Hotel</title>
</head>
<body>
    <h2>Edit Hotel</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.hotels.update', $hotel) }}">
        @csrf
        @method('PUT')
        <label>Name:</label>
        <input type="text" name="name" value="{{ $hotel->name }}" required><br>

        <label>Location:</label>
        <input type="text" name="location" value="{{ $hotel->location }}" required><br>

        <label>Description:</label>
        <textarea name="description">{{ $hotel->description }}</textarea><br>

        <label>Price per Night:</label>
        <input type="number" name="price_per_night" step="0.01" value="{{ $hotel->price_per_night }}" required><br>

        <label>Stars:</label>
        <input type="number" name="stars" min="1" max="5" value="{{ $hotel->stars }}" required><br>

        <label>Amenities (comma separated):</label>
        <input type="text" name="amenities" value="{{ $hotel->amenities }}" required><br>

        <label>Contact Email:</label>
        <input type="email" name="contact_email" value="{{ $hotel->contact_email }}"><br>

        <label>Contact Phone:</label>
        <input type="text" name="contact_phone" value="{{ $hotel->contact_phone }}"><br>

        <label>Active:</label>
        <input type="checkbox" name="is_active" value="1" {{ $hotel->is_active ? 'checked' : '' }}><br>

        <button type="submit">Update Hotel</button>
    </form>
</body>
</html>
