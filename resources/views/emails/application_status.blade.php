<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Application Status</title>
</head>
<body>

    <h2>SDO HR Department</h2>

    <p>Dear <strong>{{ $application->name }}</strong>,</p>

    <p>Thank you for submitting your application.</p>

    <p>
        <strong>Status:</strong> {!! $finalResultText !!}
    </p>

    <p>
        Position Applied: {{ $application->position_applied }}<br>
        School: {{ $application->school_name }}
    </p>

    <hr>

    @if($password)

        <!-- ✅ FIRST APPLICATION -->
        <h3>🔐 Your Account Details</h3>

        <p>
            <strong>Login Link:</strong><br>
            <a href="{{ url('/login') }}">{{ url('/login') }}</a>
        </p>

        <p>
            <strong>Username (Email):</strong><br>
            {{ $application->email }}
        </p>

        <p>
            <strong>Temporary Password:</strong><br>
            {{ $password }}
        </p>

        <p style="color:red;">
            ⚠ Please login and change your password immediately.
        </p>

        <p>
            If you encounter login issues, you may use the "Forgot Password" feature on the login page.
        </p>

    @else

        <!-- ✅ SECOND APPLICATION -->
        <h3>🔐 Account Notice</h3>

        <p>
            You already have an existing account in our system.
        </p>

        <p>
            Please use your current login credentials to access your account.
        </p>

        <p>
            <strong>Login Link:</strong><br>
            <a href="{{ url('/login') }}">{{ url('/login') }}</a>
        </p>

        <p>
            If you forgot your password, you may click "Forgot Password" on the login page to reset your account.
        </p>

    @endif

    <p>Regards,<br>SDO HR Department</p>

</body>
</html>