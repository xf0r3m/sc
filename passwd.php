
<form action="passwd.php" method="post">
	Password: <input type="password" name="pass" />
	<input type="submit" value="Get pass hash" />
</form>
<?php
  if (isset($_POST["pass"])) {
    echo "<h2>" . password_hash($_POST["pass"], PASSWORD_DEFAULT) . "</h2>";
  }
?>
