<?php
session_start();
if (!isset($_SESSION['account'])){
	$_SESSION['account']="";
}
include 'datahandler.php';
$Page = 0;
$NoteSum = 3;
$SortType = 0;

if (isset($_POST['name'])){
	$newName = $_POST['name'];
	$newEmail = $_POST['email'];
	$newNText = $_POST['text'];
	DataHandler::SetNewNote($newName, $newEmail, $newNText);
}
if (isset($_GET['status'])){
	if ($_GET['status']=="exit"){
		$_SESSION['account']="";
	}
}
if (isset($_GET['page'])){//получение номера выводимой страницы
	$Page = intval($_GET['page'])-1 ;
}else{
	$Page = 0;
}
if (isset($_GET['Sort'])){//получение типа сортировки
	$SortType = intval($_GET['Sort']) ;
}



$NoteList = DataHandler::GetNotes($SortType);
$NoteSum = count($NoteList);

if(isset($_GET['Post']) and isset($_GET['page']) and isset($_GET['Sort'])){
    if ($NoteList[$_GET['Post']]["isteady"]=="Statustrue"){
        DataHandler::SetNoteReady($NoteList[$_GET['Post']]["id"],"false");
    }else{
        DataHandler::SetNoteReady($NoteList[$_GET['Post']]["id"],"true");
    }
}
$NoteList = DataHandler::GetNotes($SortType);

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
	<h1>MyNote</h1>
</div>

<div class="container" style="margin-top: 10px; display: flex; justify-content: space-between; width: 100%;">
	<h2>Задачи:</h2>
	<div>
		<div class="container">
		  <div class="row">
		    <div class="col-sm" align="center">
		      <a href="index.php?Sort=1">Сортировать<br>по имени</a>
		    </div>
		    <div class="col-sm" align="center">
		      <a href="index.php?Sort=2">Сортировать<br>по почте</a>
		    </div>
		    <div class="col-sm" align="center">
		      <a href="index.php?Sort=3">Сортировать<br>по статусу</a>
		    </div>
		  </div>
</div>

	</div>
</div>
<div class="container">
<ul style="font-size: 1.2em;">	

<?php

for ($i=0; $i<3; $i++){

	if($i+$Page*3 < $NoteSum){
		echo "<li>";
		echo $NoteList[$i+$Page*3]["name"];
		echo " <i>".$NoteList[$i+$Page*3]["email"]."</i><br>";
		echo " ".$NoteList[$i+$Page*3]["text"];
		if ($NoteList[$i+$Page*3]["isteady"]=="Statustrue"){
			echo "<br><b>Выполнено</b>";
		}
		if ($NoteList[$i+$Page*3]["changed"]=="true"){
			echo '<br><i style="font-size:0.8em;">Было отредактировано администратором</i>';
		}
        if ($_SESSION['account']=="admin"){
            echo '<br><a href="admin.php?Post='. strval($i+$Page*3) . '&Sort=' . $SortType  . '">Изменить</a>';
            echo ' | <a href="index.php?page='. strval($Page+1) . '&Post='. strval($i+$Page*3) . '&Sort=' . $SortType  . '">Пометить, как выполненое</a>';
        }
		echo "<hr></li>";
	}
	
}
?>

</ul>
<div class="container" style="text-align: center; font-size: 1.2em;">
	Страница <?php echo strval(intval($Page)+1) ;?><br>
	<?php

	if ($Page > 0){
		echo '<a href="index.php?page='. $Page . '&Sort=' . $SortType . '"> На '. strval(intval($Page)) . " страницу </a>";
	}
	if ($Page < intdiv($NoteSum, 3)){
		echo '<a href="index.php?page='.strval(intval($Page)+2) . '&Sort=' . $SortType . '"> На '. strval(intval($Page)+2) ." страницу </a>";
	}
	?> 
	</div>	
</div>



</div>
<div class="container">
	<h2>Добавить задачу:</h2>
	<form action="index.php" method="post">
		<p>
			Имя: <input type="text" name="name">
			E-mail: <input type="text" name="email">
			Суть задачи: <input type="text" name="text">
			<input type="submit">
		</p>
	</form>
</div>
<div class="container" align="right">
	<?php
	if ($_SESSION['account']=="admin"){
		echo '<a href="admin.php">Администрирование</a>';
		echo '<a href="index.php?status=exit" style="margin-left:15px;">Выход</a>';
	}else{
		echo '<a href="admin.php">Вход</a>';
	}

	?>	
</div>
</body>
</html>

