<?php
    session_start();

    if((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany'] == true))
    {
    header('Location: panel.php');
    exit();
    }
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equix="X-UA-Compatible" content="IE=edge, chrome=1" />
    <title>Zadanie - logodanie do CRM</title>
</head>

<body>
    Zaloguj się do CRM. </br></br>

    <a href="rejestracja.php">Zarejestruj się </a>
    </br></br>

    <form action="zaloguj.php" method="post">
        Login: </br><input type="text" name="login" /></br>
        Hasło: </br><input type="password" name="haslo" /></br></br>
        <input type="submit" value="Zaloguj się" />
    </form>



<?php
    if(isset($_SESSION['blad']))
    {
        echo $_SESSION['blad'];
    }


?>
</body>