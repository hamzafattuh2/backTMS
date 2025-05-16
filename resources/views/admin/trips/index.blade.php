<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Trips</title>
</head>
<body>
    <h2>Public Trips</h2>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    <a href="{{ route('admin.trips.create') }}">Create New Public Trip</a>
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>Title</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Language Guide</th>
                <th>Price</th>
                <th>Guide</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trips as $trip)
                <tr>
                    <td>{{ $trip->title }}</td>
                    <td>{{ $trip->start_date }}</td>
                    <td>{{ $trip->end_date }}</td>
                    <td>{{ $trip->language_guide }}</td>
                    <td>{{ $trip->price }}</td>
                    <td>{{ $trip->guide ? $trip->guide->user_name : 'Not assigned' }}</td>
                    <td>
                        <a href="{{ route('admin.trips.assign_guide_form', $trip) }}">Assign Guide</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 