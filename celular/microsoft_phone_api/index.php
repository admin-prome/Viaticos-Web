<?php
session_start();
if(isset($_SESSION['autorizado']))
	header('Location: version_mobile.php');
?>
<html>
<head>
	<title>Update Version Mobile App Microsoft - Login</title>
</head>
<body>
<h1>Login</h1>
<form action="login.php" method="post">
User: <input type="text" name="user" />
<br />
Password: <input type="password" name="pass" />
<br /><br />

<input type="submit" value="Login" />
</form>

<?php
if(isset($_GET['errorlogin']))
	echo "<h2>User and / or password wrong. Try again.</h2>";
?>	

</body>
</html>