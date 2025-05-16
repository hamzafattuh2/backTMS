<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Guides</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background-color: #f9f9f9;
            color: #333;
        }

        h2 {
            color: #2c3e50;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        button[type="submit"] {
            background-color: #28a745;
            color: white;
        }

        form[action*="delete"] button {
            background-color: #dc3545;
        }

        form {
            display: inline;
        }
    </style>
</head>
<body>
    <h2>Pending Guides</h2>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>User Name</th>
                <th>Languages</th>
                <th>Years of Experience</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guides as $guide)
                <tr>
                    <td>{{ $guide->id }}</td>
                    <td>{{ $guide->user ? $guide->user->user_name : 'N/A' }}</td>
                    <td>{{ $guide->languages }}</td>
                    <td>{{ $guide->years_of_experience }}</td>
                    <td>
                        <form action="{{ route('admin.guides.confirm', $guide) }}" method="POST">
                            @csrf
                            <button type="submit">Confirm</button>
                        </form>
                        <form action="{{ route('admin.guides.delete', $guide) }}" method="POST" onsubmit="return confirm('Delete this guide?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
