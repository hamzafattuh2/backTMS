<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Public Trip</title>
</head>
<body>
    <h2>Create Public Trip</h2>
    @if($errors->any())
        <div style="color:red;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('admin.trips.store') }}">
        @csrf
        <label>Title:</label>
        <input type="text" name="title" required><br>
        <label>Description:</label>
        <textarea name="description" required></textarea><br>
        <label>Start Date:</label>
        <input type="date" name="start_date" required><br>
        <label>End Date:</label>
        <input type="date" name="end_date" required><br>
        <label>Language Guide:</label>
        <input type="text" name="language_guide" required><br>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" required><br>
        <button type="submit">Create Trip</button>
    </form>
</body>
</html> 