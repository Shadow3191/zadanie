<?php
	session_start();

	if(!isset($_SESSION['zalogowany']))
    {
        header('Location: index.php');
        exit();
    }
?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
    <meta charset="utf-8"/>
    <meta http-equix="X-UA-Compatible" content="IE=edge, chrome=1" />
</head>
</html>

   <?php echo "<p>Witaj ".$_SESSION['user'].'[<a href = "logout.php">Wyloguj się!</a>]</p>' ?>


<body>

<?php
$db = mysqli_connect("localhost", "root", "", "zadanie");

$result = $db->query("SELECT * FROM users");
?>

<html>
<head>
	<title>Lista CRM</title>
</head>

<body>
<h1>Lista osob z CRM</h1><hr>
<table border="2">
	<tr>
		<th>id<AUTO_INCREMENT</th>
		<th>Nazwa użytkownika</th>
		<th>Hasło</th>
		<th>Imie</th>
		<th>Nazwisko</th>
		<th>Stanowisko</th>
		<th>Telefon kontaktowy</th>
		<th>Email</th>
		<th>Nazwa Firmy</th>
		<th>Data dodania</th>
	</tr>

	<?php
	$i = 0;
	$result = $db->query("SELECT * FROM users");
	if($result->num_rows > 0){
		while ($row = $result->fetch_object())
		{
			foreach ($row as $r)
			{
				echo "<td>".$r."</td>";
				$i++;
				if ($i > 9)
				{
					$i = 0;
					echo "</tr>";
				}
			}
			unset($r);
		}
	}
	?>


</table>
</body>
</html>



