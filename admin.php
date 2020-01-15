<?php
session_start();
if (isset($_POST['ligin'])){
	$login = $_POST['ligin'];
	$password = $_POST['passw'];
	if ($login == "admin" and $password == "123"){
		$_SESSION['account']="admin";
	}else{
		$_SESSION['account']="";
	}
}
if (isset($_GET['status'])){
	if ($_GET['status']=="exit"){
		$_SESSION['account']="";
	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>MyNote</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
</head>
<body>

<div class="container" style="background-color: #26a69a; padding: 10px; display: flex; justify-content: space-between;">
	<h1>Администрирование</h1>
</div>
<?php
	if ($_SESSION['account']!="admin"){
		?>
			<div class="container" align="center">
				<h3>Вход в учетную запись администратора</h3>
				<form action="admin.php" method="post">
					<p>Логин: <input type="text" name="ligin"></p>
					<p>E-mail: <input type="text" name="passw"></p>
						<input type="submit" value="Вход">
				</form>
			</div>
		<?php
	}else{
		?>
		<div class="container" align="center">
			<h3><a href="index.php">На главную</a></h3>
			<h3><a href="admin.php?status=exit">Выход</a></h3>
		</div>
		<?php
	}
?>


</body>
</html>