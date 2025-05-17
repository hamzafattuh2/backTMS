<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f8;
            padding: 30px;
            color: #333;
        }

        h2 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        form {
            background-color: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            max-width: 500px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="email"],
        input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        button:hover {
            background-color: #2980b9;
        }

        .success-message {
            color: #27ae60;
            margin-bottom: 15px;
            font-weight: bold;
        }

        .error-messages {
            color: #e74c3c;
            margin-bottom: 15px;
        }

        .error-messages ul {
            padding-left: 20px;
        }

        img {
            margin-top: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

    <h2>Edit Admin Profile</h2>

    @if(session('success'))
        <div class="success-message">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error-messages">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
        @csrf
        <label>User Name:</label>
        <input type="text" name="user_name" value="{{ $admin->user_name }}" required>

        <label>First Name:</label>
        <input type="text" name="first_name" value="{{ $admin->first_name }}" required>

        <label>Last Name:</label>
        <input type="text" name="last_name" value="{{ $admin->last_name }}" required>

        <label>Email:</label>
        <input type="email" name="email" value="{{ $admin->email }}" required>

        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="{{ $admin->phone_number }}">

        <label>Profile Image:</label>
        <input type="file" name="profile_image">
        @if($admin->profile_image)
            <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="Profile Image" width="100">
        @endif

        <button type="submit">Update Profile</button>
    </form>

</body>
</html>
