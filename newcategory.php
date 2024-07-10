<?php
  if ( isset($_POST['newcategory']) ) {
    $tableName = "categories";
    $columnScheme = "name";
    $setValues = "'" . mysqli_real_escape_string($connection, $_POST['newcategory']) . "'";
    $result = dbAdd($connection, $tableName, $columnScheme, $setValues);
  }
?>
