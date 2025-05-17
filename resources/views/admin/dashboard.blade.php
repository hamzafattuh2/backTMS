<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            text-align: center;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .btn-container {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 15px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h2>Welcome to the Admin Dashboard</h2>
        <p>You are logged in as admin.</p>

        <div class="btn-container">
            <!-- Guides -->
            <a href="{{ route('admin.guides.pending') }}" class="btn">Pending Guides</a>

            <!-- Hotels -->
            <a href="{{ route('admin.hotels.index') }}" class="btn">Hotel List</a>

            <!-- Places -->
            {{-- <a href="{{ route('admin.places.create') }}" class="btn">Create Place</a> --}}

            <!-- Profile -->
            <a href="{{ route('admin.profile.edit') }}" class="btn">Edit Profile</a>

            <!-- Restaurants -->
            <a href="{{ route('admin.restaurants.index') }}" class="btn">Restaurants List</a>

            <!-- Trips -->
            <a href="{{ route('admin.trips.index') }}" class="btn">Trips List</a>

            <!-- Tourists -->
            <a href="{{ route('admin.tourists.all') }}" class="btn">Tourists List</a>

            <!-- Wallet -->
            <a href="{{ route('admin.wallet.charges') }}" class="btn">Wallet Charges</a>
        </div>
    </div>
</body>
</html>
