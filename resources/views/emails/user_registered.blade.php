<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to eCommerce</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            padding: 20px;
        }
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .email-header {
            border-bottom: 1px solid #eaeaea;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        .email-header h2 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }
        .email-content p {
            margin: 12px 0;
        }
        .label {
            font-weight: bold;
            color: #34495e;
        }
        .btn-group { margin: 15px 0;}
        .footer {
            margin-top: 30px;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <h2>Hello {{ $user->name }},</h2>
        </div>

        <div class="email-content">
            <p>Welcome to <strong>eCommerce</strong>!</p>
            <p>Your account has been created successfully.</p>

            <p><strong>Login Details:</strong></p>
            <ul>
                <li><strong>Username:</strong> {{ $user->email }}</li>
                <li><strong>Password:</strong> {{ $plainPassword }}</li>
            </ul>

            <p>You can now update your profile or start listing your properties:</p>
            
            @php
                $isLoggedIn = Auth::check();
                $profileUrl = $isLoggedIn ? route('user.profile.edit') : route('front.home');
                $createPropertyUrl = $isLoggedIn ? route('properties.create') : route('front.home');
            @endphp

            <p class="btn-group">
                <a href="{{ $profileUrl }}"
                style="background:#4CAF50;color:#fff;padding:8px 16px;text-decoration:none;border-radius:5px;"
                @unless($isLoggedIn) onclick="alert('Please login to access this page.');" @endunless>
                    Edit Profile
                </a>
                &nbsp;
                <a href="{{ $createPropertyUrl }}"
                style="background:#2196F3;color:#fff;padding:8px 16px;text-decoration:none;border-radius:5px;"
                @unless($isLoggedIn) onclick="alert('Please login to create a property.');" @endunless>
                    Create Property
                </a>
            </p>

        </div>
        <div class="footer">
            <p>Thank you for joining us!<br>
            Regards,<br>
            <strong>eCommerce Team</strong></p>
        </div>
    </div>
</body>
</html>
