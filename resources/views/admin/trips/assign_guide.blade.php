<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Guide to Trip</title>
</head>
<body>
    <h2>Assign Guide to Trip: {{ $trip->title }}</h2>
    <form method="POST" action="{{ route('admin.trips.assign_guide', $trip) }}">
        @csrf
        <label for="guide_id">Select Guide:</label>
        <select name="guide_id" required>
            <option value="">-- Select Guide --</option>
            @foreach($guides as $guide)
                <option value="{{ $guide->user_id }}">{{ $guide->user ? $guide->user->user_name : 'Guide #' . $guide->id }}</option>
            @endforeach
        </select>
        <button type="submit">Assign Guide</button>
    </form>
</body>
</html> 