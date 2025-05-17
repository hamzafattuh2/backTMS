<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tourists</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
            color: #333;
        }

        h2 {
            color: #2c3e50;
        }

        a.button {
            display: inline-block;
            background-color: #3498db;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        a.button:hover {
            background-color: #2980b9;
        }

        .success-message {
            color: #27ae60;
            margin-bottom: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ccc;
        }

        th {
            background-color: #34495e;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .action-links a {
            margin-right: 10px;
            color: #2980b9;
            text-decoration: none;
        }

        .action-links form {
            display: inline;
        }

        .action-links button {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .action-links button:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <h2>Tourists</h2>
    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>User Name</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Gender</th>
                <th>Birth Date</th>
                <th>Code</th>
                <th>Expire At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tourists as $tourist)
                <tr>
                    <td>{{ $tourist['user_name'] }}</td>
                    <td>{{ $tourist['first_name'] }} {{ $tourist['last_name'] }}</td>
                    <td>{{ $tourist['email'] }}</td>
                    <td>{{ $tourist['phone_number'] }}</td>
                    <td>{{ ucfirst($tourist['gender']) }}</td>
                    <td>{{ $tourist['birth_date'] ?? 'N/A' }}</td>
                    <td>{{ $tourist['code'] ?? 'N/A' }}</td>
                    <td>{{ $tourist['expire_at'] ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
