<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <style>
        body {
            width: 80%;
            margin: auto
        }
    </style>
    <title>Admin</title>
  </head>
  <body>
 
  <?php

if ( ! empty($_POST) ) {

    if ( ! empty($_GET['register']) ) {

        $client = array ('username' => $_POST['uname'], 'hash' => password_hash($_POST['passwd'], PASSWORD_DEFAULT));
        $jsonClient = json_encode($client);

        $f = fopen('client.json', 'w');
        fwrite($f, $jsonClient);
        fclose($f);

        session_start();
        $_SESSION['username'] = $_POST['uname'];
        header("Location: index.php");


    } else if ( ! empty($_GET['settings']) ) {

        $uploadDir = "resources/";
        $fileName = basename($_FILES['fileUpload']['name']);

        $uploadPath = '../' . $uploadDir .  basename($_FILES['fileUpload']['name']);

        if ( ! move_uploaded_file($_FILES['fileUpload']['tmp_name'], $uploadPath)) { var_dump($_FILES); }
        
        $settings = array('title' => $_POST['titleInput'], 'headerImage' => $uploadDir . $fileName, 'bgColor' => $_POST['colorInput']);

        $settingsJSON = json_encode($settings);

        $f = fopen('../settings.json', 'w');
        fwrite($f, $settingsJSON);
        fclose($f);


    } else {

        $f = fopen('client.json', 'r');
        $jsonClient = fgets($f);
        fclose($f);

        $client = json_decode($jsonClient);

        if ( $client->username === $_POST['uname'] ) {
            if ( password_verify($_POST['passwd'], $client->hash) ) {
                session_start();
                $_SESSION['username'] = $_POST['uname'];
                header("Location: index.php");
            } else {
             $loginerror = 1;
            }
        } else {
           $loginerror = 1;
        }
    }

}

session_start();

if ( ! empty($_SESSION['username']) ) {

    if ( fopen('../settings.json', 'r') ) {

        $f = fopen('../settings.json', 'r');
        $settingsJSON = fgets($f);
        fclose($f);

        $settings = json_decode($settingsJSON);

        echo "<p><h2>Ustawienia</h2><p>
        <hr />";
echo "<p><a href=\"../index.php\">Przejdź do katalogu</a></p>";
echo "<form action=\"index.php?settings=1\" method=\"post\" enctype=\"multipart/form-data\">
        <div class=\"form-group\">
            <label for=\"titleInput\">Tytuł katalogu</label>
            <input id=\"titleInput\" class=\"form-control\" type=\"text\" name=\"titleInput\" aria-describedby=\"titleInputHelp\" value=\"" . $settings->title . "\" />
            <small id=\"titleInputHelp class=\"form-text text-mutted\">Wprowadź w powyższe pole tytuł katalogu.</small>
        </div>
        <br />
        <div>
            <img src=\"../". $settings->headerImage . "\" style=\"width: 128px;\" />
        <div>
        <br />
        <div class=\"custom-file\">
            <input type=\"file\" class=\"custom-file-input\" id=\"customFile\" name=\"fileUpload\">
            <label class=\"custom-file-label\" for=\"customFile\" data-browse=\"Wybierz plik\">Obrazk nagłówkowy</label>
        </div>
        <p>&nbsp</p>
        <div class=form-group>
            <label for=\"colorInput\">Kolor tła</label>
            <input id=\"colorInput\" class=\"form-control\" type=\"color\" value=\"" . $settings->bgColor . "\" name=\"colorInput\" aria-describedby=\"colorInputHelp\"/>
            <small id=\"colorInputHelp class=\"form-text text-mutted\">Wybierz kolor tła katalogu.</small>
        </div>
        <br />
        <button type=\"submit\" class=\"btn btn-success\">Zapisz!</button>
    </form>
"; 
    } else {

    echo "<p><h2>Ustawienia</h2><p>
            <hr />";
    echo "<p><a href=\"../index.php\">Przejdź do katalogu</a></p>";
    echo "<form action=\"index.php?settings=1\" method=\"post\" enctype=\"multipart/form-data\">
            <div class=\"form-group\">
                <label for=\"titleInput\">Tytuł katalogu</label>
                <input id=\"titleInput\" class=\"form-control\" type=\"text\" name=\"titleInput\" aria-describedby=\"titleInputHelp\" />
                <small id=\"titleInputHelp class=\"form-text text-mutted\">Wprowadź w powyższe pole tytuł katalogu.</small>
            </div>
            <br />
            <div class=\"custom-file\">
                <input type=\"file\" class=\"custom-file-input\" id=\"customFile\" name=\"fileUpload\">
                <label class=\"custom-file-label\" for=\"customFile\" data-browse=\"Wybierz plik\">Obrazk nagłówkowy</label>
            </div>
            <p>&nbsp</p>
            <div class=form-group>
                <label for=\"colorInput\">Kolor tła</label>
                <input id=\"colorInput\" class=\"form-control\" type=\"color\" name=\"colorInput\" aria-describedby=\"colorInputHelp\"/>
                <small id=\"colorInputHelp class=\"form-text text-mutted\">Wybierz kolor tła katalogu.</small>
            </div>
            <br />
            <button type=\"submit\" class=\"btn btn-success\">Zapisz!</button>
        </form>
    ";          
   
    }


} else {

    if (fopen('client.json', 'r')) {

        echo "<h2>Logowanie</h2>";
        echo "<hr />";
        echo "<form action=\"index.php\" method=\"post\">";

    } else {

        echo "<h2>Rejestracja</h2>";
        echo "<hr />";
        echo "<form action=\"index.php?register=1\" method=\"post\">";

    }
    echo "
    <div class=\"form-group\">
    <label for=\"username\">Nazwa użytkownika</label>
    <input id=\"username\" class=\"form-control\" type=\"text\" name=\"uname\" aria-describedby=\"usernameHelp\" />
    <small id=\"usernameHelp\" class=\"form-text text-muted\">Wprowadź nazwę użytkownika</small>
</div>
<div class=\"form-group\">
    <label for=\"password\">Hasło</label>
    <input id=\"password\" class=\"form-control\" type=\"password\" name=\"passwd\" aria-describedby=\"passwordHelp\" />
    <small id=\"passwordHelp\" class=\"form-text text-muted\">Wprowadź hasło</small>
<div>
<br />
    <button type=\"submit\" class=\"btn btn-success\">Zaloguj!</button>
</form>
    ";
    if ( ! empty($loginerror) ) {
        echo "
        <p>&nbsp;</p>
        <div class=\"alert alert-danger alert-dismissible fade show\" role=\"alert\">
          <strong>Nie poprawny login lub hasło</strong>
          <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-label=\"Close\">
            <span aria-hidden=\"true\">&times;</span>
          </button>
        </div>";
    }
}
?>

    


    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
  </body>
</html>

