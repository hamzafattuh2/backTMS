<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Admin Profile</title>
</head>
<body>
    <h2>Edit Admin Profile</h2>
    @if(session('success'))
        <div style="color:green;">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div style="color:red;">
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
        <input type="text" name="user_name" value="{{ $admin->user_name }}" required><br>
        <label>First Name:</label>
        <input type="text" name="first_name" value="{{ $admin->first_name }}" required><br>
        <label>Last Name:</label>
        <input type="text" name="last_name" value="{{ $admin->last_name }}" required><br>
        <label>Email:</label>
        <input type="email" name="email" value="{{ $admin->email }}" required><br>
        <label>Phone Number:</label>
        <input type="text" name="phone_number" value="{{ $admin->phone_number }}"><br>
        <label>Profile Image:</label>
        <input type="file" name="profile_image"><br>
        @if($admin->profile_image)
            <img src="{{ asset('storage/' . $admin->profile_image) }}" alt="Profile Image" width="100">
        @endif
        <button type="submit">Update Profile</button>
    </form>
</body>
</html> 