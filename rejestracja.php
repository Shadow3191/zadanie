<?php
    session_start();

    if (isset($_POST['email']))
    {
        // udana walidacja
        $wszystko_ok = true;

        // sprawdzanie poprawnosci nicka
        $nick = $_POST['nick'];

        // sprawdzanie dlugosci nicka
        if ((strlen($nick) < 3) || (strlen($nick) > 20))
        {
            $wszystko_ok = false;
            $_SESSION['e_nick'] = "Nick musi posiadać od 3 do 20 znaków!";
        }

        if (ctype_alnum(@$nick) == false)
        {
            $wszystko_ok = false;
            $_SESSION['e_nick'] = "Nick może się składać tylko z liter i cyfr (bez polkich znaków)";
        }
        // sprawdza poprawnosc adreu email
        $email = $_POST['email'];

        $name = $_POST['name'];
        $surname = $_POST['surname'];
        $position = $_POST['position'];
        $telephone = $_POST['telephone'];
        $company = $_POST['company'];


        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL) == false) || ($emailB != $email))
        {
            $wszystko_ok = false;
            $_SESSION['e_mail'] = "Podaj poprawny adres email!";
        }

        // sprawdza poprwnosc hasla

        $haslo1 = $_POST['haslo1'];
        $haslo2 = $_POST['haslo2'];



        if ((strlen($haslo1) < 8) || (strlen($haslo2) > 20))
        {
	        $wszystko_ok = false;
	        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków !";
        }

        if ($haslo1 != $haslo2)
        {
	        $wszystko_ok = false;
	        $_SESSION['e_haslo'] = "Podane hasła nie są identyczne!";
        }

        $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
        // czy zaakceptowano regulamin
        if (!isset($_POST['regulamin']))
        {
            $wszystko_ok = false;
            $_SESSION['e_regulamin'] = "Potwierdz akceptację regulaminu!";
        }

        // sprawdza zaznaczenie recaptcha
        $sekret = "6LfccNYUAAAAAFIriV8hR5FUDgPRQYbgaUEQTBlx";

	    $sprawdz = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$sekret.'&response='.$_POST['g-recaptcha-response']);
	    $odpowiedz = json_decode($sprawdz);

        if ($odpowiedz->success==false)
        {
            $wszystko_ok = false;
            $_SESSION['e_bot'] = "Potwierdź że nie jesteś botem !";
        }

        // zapamietuje wprowadzone dane
        $_SESSION['fr_nick'] = $nick;
	    $_SESSION['fr_haslo1'] = $haslo1;
	    $_SESSION['fr_haslo2'] = $haslo2;
	    $_SESSION['fr_email'] = $email;
	    $_SESSION['fr_name'] = $name;
	    $_SESSION['fr_surname'] = $surname;
	    $_SESSION['fr_position'] = $position;
	    $_SESSION['fr_telephone'] = $telephone;
	    $_SESSION['fr_company'] = $company;
	    if(isset($_SESSION['regulamin'])) $_SESSION['fr_regulamin'] = true;

        require_once "connect.php";
        mysqli_report(MYSQLI_REPORT_STRICT);

	    try
        {
	        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);

	        if ($polaczenie->connect_errno != 0)
	        {
		       throw new Exception(mysqli_connect_errno());
	        }
	        else
            {
                // czy email juz istnieje
                $rezultat = $polaczenie->query("SELECT id FROM users WHERE email='$email'");

                if (!$rezultat) throw new Exception($polaczenie -> error);

                $ile_takich_maili = $rezultat -> num_rows;

                if ($ile_takich_maili > 0)
                {
	                $wszystko_ok = false;
	                $_SESSION['e_mail'] = "Istnieje już konto przypisane do tego adresu email!";
                }

	            // czy nick jest juz zarezerwowany
	            $rezultat = $polaczenie->query("SELECT id FROM users WHERE user_name='$nick'");

	            if (!$rezultat) throw new Exception($polaczenie -> error);

	            $ile_takich_nickow = $rezultat -> num_rows;

	            if ($ile_takich_nickow > 0)
	            {
		            $wszystko_ok = false;
		            $_SESSION['e_nick'] = "Istnieje już osoba o takim nicku, wybierz inny!";
	            }

	            if ($wszystko_ok == true)
	            {
		            if ($polaczenie->query("INSERT INTO users VALUES (NULL , '$nick', '$haslo_hash', '$name', '$surname', '$position', '$telephone', '$email', 
                        '$company', now(), FALSE   )"))
                    {
                        $_SESSION['udana_rejestracja'] = true;
                        header('Location: witamy.php');
                    }
                    else
                    {
	                    throw new Exception($polaczenie -> error);
                    }
	            }

                $polaczenie->close();
            }



	    }
	    catch (Exception $e)
        {
            echo '<span style="color: red;">Błąd serwera, przepraszamy za niedogodności i prosimy o rejestrację w innym terminie.</span>';
            echo $e;
        }
    }

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equix="X-UA-Compatible" content="IE=edge, chrome=1" />


    <title>Zarejestracja</title>
    <script src='https://www.google.com/recaptcha/api.js?render=_reCAPTCHA_site_key'></script>

    <style>
        .error
        {
            color:red;
            margin-top: 10px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>

    <form method="post">

    Nick: <br/> <input type = "text" value="<?php
		if (isset($_SESSION['fr_nick']))
		{
			echo $_SESSION['fr_nick'];
			unset($_SESSION['fr_nick']);
		}
	    ?>" name="nick"/><br/>

    <?php
    if (isset($_SESSION['e_nick']))
    {
	    echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
	    unset($_SESSION['e_nick']);
    }
    ?>

    Twoje hasło: <br/> <input type = "text" value="<?php
		if (isset($_SESSION['fr_haslo1']))
		{
			echo $_SESSION['fr_haslo1'];
			unset($_SESSION['fr_haslo1']);
		}?>"  name="haslo1"/><br/>


    Powtórz hasło: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_haslo2']))
	    {
		    echo $_SESSION['fr_haslo2'];
		    unset($_SESSION['fr_haslo2']);
	    }?>" name="haslo2"/><br/>

	    <?php
	    if (isset($_SESSION['e_haslo']))
	    {
		    echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
		    unset($_SESSION['e_haslo']);
	    }
	    ?>

    Imie: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_name']))
	    {
		    echo $_SESSION['fr_name'];
		    unset($_SESSION['fr_name']);
	    }?>" name="name"/><br/>

    Nazwisko: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_surname']))
	    {
		    echo $_SESSION['fr_surname'];
		    unset($_SESSION['fr_surname']);
	    }?> "name="surname"/><br/>

    Stanowisko: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_position']))
	    {
		    echo $_SESSION['fr_position'];
		    unset($_SESSION['fr_position']);
	    }?>" name="position"/><br/>

    Telefon kontaktowy: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_telephone']))
	    {
		    echo $_SESSION['fr_telephone'];
		    unset($_SESSION['fr_telephone']);
	    }?>" name="telephone"/><br/>

    Email: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_email']))
	    {
		    echo $_SESSION['fr_email'];
		    unset($_SESSION['fr_email']);
	    }?>" name="email"/><br/>



	    <?php
	    if (isset($_SESSION['e_mail']))
	    {
		    echo '<div class="error">'.$_SESSION['e_mail'].'</div>';
		    unset($_SESSION['e_mail']);
	    }
	    ?>

    Firma: <br/> <input type = "text" value="<?php
	    if (isset($_SESSION['fr_company']))
	    {
		    echo $_SESSION['fr_company'];
		    unset($_SESSION['fr_company']);
	    }?>" name="company"/><br/>


    <label>
	    <input type="checkbox" name="regulamin" <?php
	    if (isset($_SESSION['fr_regulamin']))
	         {
				echo "checked";
				unset($_SESSION['fr_regulamin']);
	         }

	    ?>/> Akcepruję regulamin
    </label>

	    <?php
	    if (isset($_SESSION['e_regulamin']))
	    {
		    echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
		    unset($_SESSION['e_regulamin']);
	    }
	    ?>

    <div class="g-recaptcha" data-sitekey="6LfccNYUAAAAAEjZELttVc_1bD1BiL7qm2eIZky9"></div>

	    <?php
	    if (isset($_SESSION['e_bot']))
	    {
		    echo '<div class="error">'.$_SESSION['e_bot'].'</div>';
		    unset($_SESSION['e_bot']);
	    }
	    ?>

    <br />
    <input type="submit" value="Zarejestruj się!" />


    </form>


</body>
</html>