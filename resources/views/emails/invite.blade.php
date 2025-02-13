<!DOCTYPE html>
<html>
<head>
    <title>Invitation</title>
</head>
<body>
    <h2>You have been invited!</h2>
    <p>Click the link below to accept the invitation:</p>
    <a href="{{ url('/register?token=' . $token) }}">Accept Invite</a>
</body>
</html>
