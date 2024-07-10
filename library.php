<?php

function mysqliResult($connection, $result) {
  if ( ($result === true) || (mysqli_num_rows($result) > 0) ) {
    if ( ! isset($_SERVER["SHELL"]) ) {
      echo "<script>console.log('Zapytanie powiodło się.')</script>";
    }
    return true;
  } else {
    echo "<script>console.log('Zapytanie nie powiodło się: " . mysqli_error($connection) . "');</script>";
    return false;
  }
}

function dbQuery($connection, $tableName, $columnScheme, $whereValue, $debug=0) {
  $query = "SELECT " . $columnScheme . " FROM " . $tableName . " WHERE " . $whereValue;
  if ( $debug == 1 ) { var_dump($query); }
  $result = mysqli_query($connection, $query);
 
  if ( mysqliResult($connection, $result) ) {
    return $result;
  } else {
    echo "<script>console.log('Pobranie danych z bazy jest niemożliwe');</script>";
  }

}

function getFieldValue($result) {
  $row = mysqli_fetch_row($result);
  return $row[0];
}

function dbUpdate($connection, $tableName, $setValue, $whereValue) {
  $query = "UPDATE " . $tableName . " SET " . $setValue . " WHERE " . $whereValue;
  $result = mysqli_query($connection, $query);
 
  if ( mysqliResult($connection, $result) ) {
    return $result;
  } else {
    echo "<script>console.log('Zmiana danych w bazie jest niemożliwa');</script>";
  }

}

function dbAdd($connection, $tableName, $columnScheme, $setValues) {
  $query = "INSERT INTO " . $tableName . " (" . $columnScheme . ") VALUES (" . $setValues . ");";
  $result = mysqli_query($connection, $query);

  if ( mysqliResult($connection, $result) ) {
    return $result;
  } else {
    echo "<script>console.log('Dodanie danych do bazy jest niemożliwa');</script>";
  }
}

function dbDel($connection, $tableName, $whereValue) {
  $query = "DELETE FROM " . $tableName . " WHERE " . $whereValue;
  $result = mysqli_query($connection, $query);

  if ( mysqliResult($connection, $result) ) {
    return $result;
  } else {
    echo "<script>console.log('Usunięcie danych z bazy jest niemożliwa');</script>";
  }
}

?>
