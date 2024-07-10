<?php

  if ( ! empty($_POST) ) {
    $tableName = "users";
    $columnScheme = "hash";
    $whereValue = "username = '" . mysqli_real_escape_string($connection, $_POST['user']) . "';";
    $result = dbQuery($connection, $tableName, $columnScheme, $whereValue);
    if ( ! is_null($result) ) {
      $passHash = getFieldValue($result);
    }
    if ( isset($passHash) ) {
      if ( password_verify($_POST['pass'], $passHash) ) {
        if (session_status() != 2) {
          session_start();
          $_SESSION['username'] = $_POST['user'];
          header("Location: index.php");
        }
      } else {
        header("Location: index.php?a=login&result=1");
      }
    } else {
      header("Location: index.php?a=login&result=1");
    }
  } else {
?>
    <?php
      if ( isset($_GET['result']) ) {
      ?>
        <div class="alert alert-danger" role="alert">
          Niepoprawna nazwa użytkownika lub hasło.
        </div>
      <?php
      }
    ?>
    <div id="loginForm" class="card">
      <div class="card-header">Logowanie: </div>
      <form action="?a=login" method="post">
        <div class="mb-2 inputs">
          <label for="inputUsername" class="form-label">Nazwa użytkownika: </label>
          <input type="text" class="form-control" name="user">
        </div>
        <div class="mb-3 inputs">
          <label for="inputPassword" class="form-label">Hasło: </label>
          <input type="password" class="form-control" name="pass">
        </div>
        <button type="submit" class="btn btn-primary buttons">Zaloguj się</button>
      </form>
    </div>
<?php 
  }

?>
