<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Public Trips</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f7f9;
            padding: 30px;
            color: #333;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .success-message {
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 20px;
        }

        a.button {
            display: inline-block;
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        a.button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #2c3e50;
            color: #fff;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }

        .actions a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <h2>Public Trips</h2>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.trips.create') }}" class="button">Create New Public Trip</a>

    <table>
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
                    <td class="actions">
                        <a href="{{ route('admin.trips.assign_guide_form', $trip) }}">Assign Guide</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
