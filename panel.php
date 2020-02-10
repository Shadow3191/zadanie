<?php

session_start();

if(!isset($_SESSION['zalogowany']))
{
	header('Location: index.php');
	exit();
}

$db = mysqli_connect("localhost", "root", "", "zadanie");
$result = $db->query("SELECT * FROM users");

?>

<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8"/>
	<meta http-equix="X-UA-Compatible" content="IE=edge, chrome=1" />
	<title>Lista CRM</title>
</head>
<body>

<?php echo "<p>Witaj ".$_SESSION['user'].'[<a href = "logout.php">Wyloguj się!</a>]</p>' ?>

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
		<?php
		if ($_SESSION['is_admin'])
		{
			echo "<th>Usuń użytkownika</th>";
		}
		?>
	</tr>

	<?php

	$result = $db->query("SELECT * FROM users");
	if($result->num_rows > 0){
		while ($row = $result->fetch_object())
		{
			echo "<tr>";
			$i = 0;
			foreach ($row as $r)
			{
				echo "<td>".$r."</td>";
				$i++;
				if ($i > 9)
				{
					break;
				}
			}
			if ($_SESSION['is_admin'] && ! $row->is_admin) {
				echo "<td><a href=\"delete.php?uid=$row->id\">Usuń użytkownika</a></td>";
			}
			unset($r);
			echo "</tr>";
		}
	}
	?>


</table>
</body>
</html>