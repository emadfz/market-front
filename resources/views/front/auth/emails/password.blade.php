{{--Click here to reset your password: <a href="{{ $link = url('password/reset', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
--}}
<!DOCTYPE html>
<html lang="en-US">
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <div>Hello {{$full_name}},</div><br/>
        <div>
            You have requested password reset, please click on the below link to reset your password.<br/>
            Password reset link: <a href="{{ $link = route('resetPassword', $token) }}"> {{ $link }} </a>
            <br/><br/>
        </div>
        <div>Best Regards,<br/> The inSpree Team</div>
    </body>
</html>
