<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div>Hello {{$fullName}},</div><br/>
        <div>
            Congratulations on signing up with inSpree Marketplace.<br/>
            Please <a href="{{ route('accountVerify', $activationCode) }}">Click here</a> to verify your email address.<br/>
            {{ route('accountVerify', $activationCode) }}
            <br/><br/>
        </div>
        <div>Best Regards,<br/> The inSpree Team</div>
    </body>
</html>