<?php require 'check_login.php'; ?>
<html>
<head>
	<title>Update Version Mobile App Microsoft</title>
</head>
<body>
	
	<?php
		if(isset($_GET['updateapp']))
			echo "<h2>Data Updated !</h2>";
	?>
	
	<h1>Update Data Mobile App WPhone, complete next data:</h1>
	
	<form action="update_version.php" method="post">
		version number: <input type="text" name="version_numero" /><br /><br />
		description: <textarea name="version_descripcion"></textarea>
		<br />
		<input type="submit" value="Update Data" />
	
	</form>

	<input type="button" value="Logout" onclick="window.location = 'logout.php';" />
	
</body>
</html>