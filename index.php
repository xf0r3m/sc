<?php

  if ( fopen('settings.json', 'r') ) {
    
    $f = fopen('settings.json', 'r');
    $settingsJSON = fgets($f);
    fclose($f);

    $settings = json_decode($settingsJSON);
  } else {
    $settings = new stdClass;
    $settings->title = 'SiteCatalog';
    $settings->headerImage = 'resources/directory2.png';
    $settings->bgColor = '#ffffff';
  }

?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
      body{
        width: 80%;
        margin: auto;
        background-color: <?php echo $settings->bgColor; ?>;
      }
      #header{
        background-image: url(<?php echo $settings->headerImage; ?>);
        background-position: center;
        background-repeat: no-repeat;
        text-align: center;
        font-size: 84px;
        width: 100%;
        height: 50vh;
        padding-top: 12%;
        font-weight: bold;
        word-wrap: break-word;
      }

    </style>
    <title><?php echo $settings->title; ?></title>
  </head>
  <body>
    <a href="index.php" style="text-decoration: none; color: black;"><div id="header">
    <p id="headerTitle"><?php echo $settings->title; ?><p>
    </div></a>
    <hr />
    <div id="catalog">
    
    <?php
      session_start();
      if ( ! empty($_SESSION['username']) ) {

        function putJSONtoFile() {

          $f = fopen('sites/' . $_POST['siteTitle'] . '.json', 'w');
      
          $site = array ('title' => $_POST['siteTitle'], 'link' => $_POST['siteLink'], 'desc' => $_POST['siteDesc']);
          $siteJSON = json_encode($site, JSON_FORCE_OBJECT, JSON_UNESCAPED_SLASHES);
      
          fwrite($f, $siteJSON);
          fclose($f);
      
        }
      
        if ( ! empty($_POST) ) {
      
          if ( ! empty($_GET['add']) ) {
      
            if ( fopen('sites/' . $_POST['siteTitle'] . '.json', 'r') ) { 
              echo "<div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
              Holibka! Strona o tym tytule już istniej. Wybierz inny tytuł.
              <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
                <span aria-hidden=\"true\">&times;</span>
              </button>
              </div>"; 
            } else {
      
              putJSONtoFile();
      
            }
          } else if ( ! empty($_GET['mod']) ) {
      
            unlink('sites/' . $_POST['siteTitle'] . '.json');
      
            putJSONtoFile();
      
          } else {
      
            if ( ! empty($_GET['del']) ) {
      
              unlink('sites/' . $_POST['siteTitle'] . '.json');
      
            } 
      
          }
      
        }

        echo "
        <button type=\"button\" class=\"btn btn-success\" data-toggle=\"modal\" data-target=\"#addSite\" >Dodaj stronę</button>
        <div class=\"modal fade\" id=\"addSite\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"addSiteModalLabel\" aria-hidden=\"true\">
          <div class=\"modal-dialog\">
            <div class=\"modal-content\">
              <div class=\"modal-header\">
                <h5 class=\"modal-title\" id=\"addSiteModalLabel\">Nowa strona</h5>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                  <span aria-hidden=\"true\">&times;</span>
                </button>
              </div>
              <div class=\"modal-body\">
                <form action=\"index.php?add=1\" method=\"post\">
                  <div class=\"form-group\">
                    <label for=\"bookmark-name\" class=\"col-form-label\">Nazwa/Tytuł zakładki:</label>
                    <input type=\"text\" class=\"form-control\" id=\"bookmark-name\" name=\"siteTitle\">
                  </div>
                  <div class=\"form-group\">
                    <label for=\"link\" class=\"col-form-label\">Adres odnośnika:</label>
                    <input class=\"form-control\" id=\"link\" type=\"text\" name=\"siteLink\" />
                  </div>
                  <div class=\"form-group\">
                  <label for=\"desc\" class=\"col-form-label\">Opis zakładki:</label>
                  <textarea class=\"form-control\" id=\"desc\" name=\"siteDesc\" /></textarea>
                </div>
              </div>
              <div class=\"modal-footer\">
                <button type=\"submit\" class=\"btn btn-success\">Dodaj stronę</button>
                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Anuluj</button>
              </div>
                </form>
            </div>
          </div>
        </div>
        ";
      } 
    ?>
    <?php
      $dir = scandir('sites');
      if ( count($dir) > 2 ) {

        echo "<ul class=\"list-group list-group-flush\" style=\"margin-top: 1%; list-style-type: square;\">";
        for ($i=2; $i < count($dir); $i++) {

          $f = fopen('sites/' . $dir[$i], 'r');
          $siteJSON = fgets($f);
          fclose($f);

          $site = json_decode($siteJSON);

          if ( strlen($site->desc) > 0 ) {
            echo "<li class=\"list-group-item\"><a href=\"" . $site->link . "\" />" . $site->title . "</a>&nbsp;&nbsp;
            <small class=\"form-text text-muted\"> - " . $site->desc . "</small>";
          } else {
            echo "<li class=\"list-group-item\"><a href=\"" . $site->link . "\" />" . $site->title . "</a>&nbsp;&nbsp;";
          }

          if ( session_status() !== 2 ) { session_start(); }

          if ( ! empty($_SESSION['username']) ) {
            echo "<button type=\"button\" class=\"btn btn-danger btn-sm\" data-toggle=\"modal\" data-target=\"#delSite" . $i . "\" style=\"float: right;\">Usuń stronę</button>";
            echo "
          <div class=\"modal fade\" id=\"delSite" . $i . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"delSiteModalLabel\" aria-hidden=\"true\">
            <div class=\"modal-dialog\">
              <div class=\"modal-content\">
                <div class=\"modal-header\">
                  <h5 class=\"modal-title\" id=\"delSiteModalLabel\">Usuń stronę</h5>
                  <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                    <span aria-hidden=\"true\">&times;</span>
                  </button>
                </div>
                <div class=\"modal-body\">
                  <p>Czy na pewno usunąć tą stronę z katalogu?</p>
                  <form action=\"index.php?del=1\" method=\"post\">
                  <input type=\"hidden\" name=\"siteTitle\" value=\"" . $site->title . "\" />
                </div>
                <div class=\"modal-footer\">
                  <button type=\"button\" class=\"btn btn-success\" data-dismiss=\"modal\">Nie</button>
                  <button type=\"submit\" class=\"btn btn-danger\">Tak</button>
                </div>
                  </form>
              </div>
            </div>
          </div>
          ";
            echo "<button type=\"button\" class=\"btn btn-warning btn-sm\" data-toggle=\"modal\" data-target=\"#modSite" . $i . "\" style=\"float: right; margin-right: 1%;\">Modyfikuj stronę</button>&nbsp;&nbsp;";
            echo "
        <div class=\"modal fade\" id=\"modSite" . $i . "\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"modSiteModalLabel\" aria-hidden=\"true\">
          <div class=\"modal-dialog\">
            <div class=\"modal-content\">
              <div class=\"modal-header\">
                <h5 class=\"modal-title\" id=\"modSiteModalLabel\">Modyfikuj stronę</h5>
                <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">
                  <span aria-hidden=\"true\">&times;</span>
                </button>
              </div>
              <div class=\"modal-body\">
                <form action=\"index.php?mod=1\" method=\"post\">
                  <div class=\"form-group\">
                    <label for=\"bookmark-name\" class=\"col-form-label\">Nazwa/Tytuł zakładki:</label>
                    <input type=\"text\" class=\"form-control\" id=\"bookmark-name\" name=\"siteTitle\" value=\"" . $site->title . "\">
                  </div>
                  <div class=\"form-group\">
                    <label for=\"link\" class=\"col-form-label\">Adres odnośnika:</label>
                    <input class=\"form-control\" id=\"link\" type=\"text\" name=\"siteLink\" value=\"" . $site->link . "\" />
                  </div>
                  <div class=\"form-group\">
                  <label for=\"desc\" class=\"col-form-label\">Opis zakładki:</label>
                  <textarea class=\"form-control\" id=\"desc\" name=\"siteDesc\" />" . $site->desc . "</textarea>
                </div>
              </div>
              <div class=\"modal-footer\">
                <button type=\"submit\" class=\"btn btn-warning\">Zapisz zmiany</button>
                <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Anuluj</button>
              </div>
                </form>
            </div>
          </div>
        </div>
        ";

          }
          echo "</li>";
        }
        echo "</ul>";
        
      }
    ?>
    </div>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>