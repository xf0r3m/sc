<?php
  include('library.php');
  include('db_conf.php');
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Site Catalogue - morketsmerke.org</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style.css" type="text/css" rel="stylesheet">
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">sc</a>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <?php
            if ( session_status() != 2 ) { session_start(); }
            if ( ! empty($_SESSION['username']) ) {
              echo "<a class=\"nav-link\" href=\"?a=logout\">Wyloguj się</a>";
            } else {
              echo "<a class=\"nav-link\" href=\"?a=login\">Zaloguj się</a>";
            }
          ?>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <div id="main" class="container-lg">
      <?php
        if ( ! empty($_GET['a']) ) {
          if ( $_GET['a'] == 'login' ) { include('login.php'); }
          if ( $_GET['a'] == 'logout' ) { include('logout.php'); }
        } else {
      ?>
      <div id="categories" class="container-sm">
        <div class="card">
          <div class="card-header">
            Kategorie: 
          </div>
          <ul class="list-group list-group-flush">
            <?php
              # Usunięcie kategorii, a wraz znią przypisanych do niej site-ów
              if ( isset($_POST['cateid']) ) {
                $tableName = "sites";
                $whereValue = "cateId = " . mysqli_real_escape_string($connection, $_POST['cateid']) . ";";
                $result = dbDel($connection, $tableName, $whereValue);
                
                $tableName = "categories";
                $whereValue = "id = " . mysqli_real_escape_string($connection, $_POST['cateid']) . ";";
                $result = dbDel($connection, $tableName, $whereValue); 
              }
              # Dodanie kategorii
              if ( isset($_POST['newcategory']) ) {
                $tableName = "categories";
                $columnScheme = "name";
                $setValues = "'" . mysqli_real_escape_string($connection, $_POST['newcategory']) . "'";
                $result = dbAdd($connection, $tableName, $columnScheme, $setValues);
              }

              # Wyświetlenie kategorii
              $tableName = "categories";
              $columnScheme = "id, name";
              $whereValue = "1=1;";
              $result = dbQuery($connection, $tableName, $columnScheme, $whereValue);
              if ( ! is_null($result) ) {
                while ( $row = mysqli_fetch_row($result) ) {
                  if ( session_status() != 2 ) { session_start(); }
                  if ( ! empty($_SESSION['username']) ) {
                  echo "<li class=\"list-group-item\">
                  <form class=\"delForms\" action=\"index.php\" method=\"post\">
                  <input type=\"hidden\" name=\"cateid\" value=\"" . $row[0] . "\">
                  <button type=\"submit\" class=\"btn btn-danger deleteButton\" title=\"Usuń\">&times;</button>
                  </form>
                  <a href=\"?cate=" . $row[0] . "\">" . $row[1] . "</a></li>";
                  } else {
                    echo "<li class=\"list-group-item\">
                    <a href=\"?cate=" . $row[0] . "\">" . $row[1] . "</a></li>";
                  }
                }
              } 
              if ( session_status() != 2 ) { session_start(); }
              if ( ! empty($_SESSION['username']) ) {
            ?>
                <li class="list-group-item">
                  <form class="row g-2" action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                    <div class="col-auto">
                      <input type="text" class="form-control" name="newcategory" placeholder="Nowa kategoria">
                    </div>
                    <div class="col-auto">
                      <button type="submit" class="btn btn-primary">Dodaj</button>
                    </div>
                  </form>   
                </li>
            <?php
              }
            ?>
          </ul>
        </div>
        <!-- <h1>Categories</h1> -->
      </div>
      <div id="catalogue" class="container-md">
        <div class="card">
          <div class="card-header">
            <?php
              if ( ! isset($_GET['cate']) ) { $cate = 1; }
              else { $cate = $_GET['cate']; }
                $tableName = "categories";
                $columnScheme = "name";
                $whereValue = "id = " . $cate . ";";
                $result = dbQuery($connection, $tableName, $columnScheme, $whereValue);
                if ( ! is_null($result) ) {
                  $categoryName = getFieldValue($result);
                  echo $categoryName . ": ";
                } 
            ?>
          </div>
          <ul class="list-group list-group-flush">
            <?php
              #Usunięcie strony
              if ( isset($_POST['siteId']) ) {
                $tableName = "sites";
                $whereValue = "id = " . mysqli_real_escape_string($connection, $_POST['siteId']) . ";";
                $result = dbDel($connection, $tableName, $whereValue);
              }

              #Dodanie strony
              if ( isset($_POST['siteName']) ) {
                $tableName = "sites";
                $columnScheme = "cateId, name, href";
                $setValues = mysqli_real_escape_string($connection, $_POST['siteCategoryId']) . ",'" . mysqli_real_escape_string($connection, $_POST['siteName']) . "','" . mysqli_real_escape_string($connection, $_POST['siteHref']) . "'";
                $result = dbAdd($connection, $tableName, $columnScheme, $setValues);
              }

              #Wyświetlenie stron
              $tableName = "sites";
              $columnScheme = "id, name, href";
              $whereValue = "cateId = " . $cate . ";";
              $result = dbQuery($connection, $tableName, $columnScheme, $whereValue);
              if ( ! is_null($result) ) {
                while( $row = mysqli_fetch_row($result) ) {
                  if ( session_status() != 2 ) { session_start(); }
                  if ( ! empty($_SESSION['username']) ) {
                  echo "<li class=\"list-group-item\">
                  <form class=\"delForms\" action=\"" . $_SERVER['REQUEST_URI'] . "\" method=\"post\">
                  <input type=\"hidden\" name=\"siteId\" value=\"" . $row[0] . "\">
                  <button type=\"submit\" class=\"btn btn-danger deleteButton\" title=\"Usuń\">&times;</button>
                  </form>
                  <a href=\"" . $row[2] . "\">". $row[1] . "</a></li>";
                  } else {
                    echo "<li class=\"list-group-item\">
                    <a href=\"" . $row[2] . "\">" . $row[1] . "</a></li>";
                  }
                }
              }
              if ( session_status() != 2 ) { session_start(); }
              if ( ! empty($_SESSION['username']) ) {
            ?>
                <li class="list-group-item">
                 <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
                  <input type="hidden" name="siteCategoryId" value="<?php echo $cate ?>">
                  <div class="mb-2 inputs">
                    <label for="siteName" class="form-label">Nazwa/opis strony:</label>
                    <input type="text" class="form-control" name="siteName">
                  </div>
                  <div class="mb-3 inputs">
                    <label for="siteHref" class="form-label">Adres strony</label>
                    <input type="text" class="form-control" name="siteHref">
                  </div>
                  <button type="submit" class="btn btn-primary buttons">Zapisz</button>
                 </form>
                </li>
            <?php
              }
            ?>

          </ul>
        </div>
      </div>
      <?php
        }
      ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </body>
</html>

