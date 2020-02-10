 <?php
    session_start();

    if((!isset($_SESSION['udana_rejestracja'])))
    {
        header('Location: index.php');
        exit();
    }
    else
    {
        unset($_SESSION['udana_rejestracja']);
    }

    // usuwamy zmienne pamietajace wartosci
    if (isset($_SESSION['fr_nick'])) unset($_SESSION['fr_nick']);
    if (isset($_SESSION['fr_haslo1'])) unset($_SESSION['fr_haslo1']);
    if (isset($_SESSION['fr_haslo2'])) unset($_SESSION['fr_haslo2']);
    if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
    if (isset($_SESSION['fr_name'])) unset($_SESSION['fr_name']);
    if (isset($_SESSION['fr_surname'])) unset($_SESSION['fr_surname']);
    if (isset($_SESSION['fr_position'])) unset($_SESSION['fr_position']);
    if (isset($_SESSION['fr_telephone'])) unset($_SESSION['fr_telephone']);
    if (isset($_SESSION['fr_company'])) unset($_SESSION['fr_company']);
    if (isset($_SESSION['fr_add_data'])) unset($_SESSION['fr_add_data']);
    if (isset($_SESSION['fr_regulamin'])) unset($_SESSION['fr_regulamin']);

    // usuwanie bledow rejestracji
    if (isset($_SESSION['e_nick'])) unset($_SESSION['e_nick']);
     if (isset($_SESSION['e_haslo1'])) unset($_SESSION['e_haslo1']);
     if (isset($_SESSION['e_haslo2'])) unset($_SESSION['e_haslo2']);
     if (isset($_SESSION['e_email'])) unset($_SESSION['e_email']);
     if (isset($_SESSION['e_name'])) unset($_SESSION['e_name']);
     if (isset($_SESSION['e_surname'])) unset($_SESSION['e_surname']);
     if (isset($_SESSION['e_position'])) unset($_SESSION['e_position']);
     if (isset($_SESSION['e_telephone'])) unset($_SESSION['e_telephone']);
     if (isset($_SESSION['e_company'])) unset($_SESSION['e_company']);
     if (isset($_SESSION['e_add_data'])) unset($_SESSION['e_add_data']);
     if (isset($_SESSION['e_regulamin'])) unset($_SESSION['e_regulamin']);


 ?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equix="X-UA-Compatible" content="IE=edge, chrome=1" />
    <title>Zadanie - logodanie do CRM</title>
</head>

<body>
    Dziękujemy za zalogowanie w CRM. Możesz już zalogować się na swoje konto. </br></br>

    <a href="index.php">Zarejestruj się na swoje konto! </a>
    </br></br>


</body>