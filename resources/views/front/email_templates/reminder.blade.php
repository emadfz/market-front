<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
<p>this is new ad request from user (Name : {{$user->username}}) & Email : {{$user->email}} )  , to activate please click here <a href="{{env('APP_ADMIN_URL').'/admin/advertisements'}}">Click Me</a></p>

	this is front.email_template.reminder , <br>modify as you want :)
 
 
 
</body>
</html>