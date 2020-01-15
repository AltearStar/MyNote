<?php
session_start();
if (!isset($_SESSION['account'])){
	$_SESSION['account']="";
}
include 'datahandler.php';
$Page = 0;
$NoteSum = 3;
$SortType = 0;
$newName = "";
$newEmail = "";
$newNText = "";

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



if (isset($_POST['name'])){
	$newName = $_POST['name'];
	$newEmail = $_POST['email'];
	$newNText = $_POST['text'];
	$Page = $_POST['page'];

	if ($newName=="" or $newEmail=="" or $newNText==""){
		echo '<script type="text/javascript">alert( "Пожалуйста, заполните все поля.");</script>';
	}elseif (strpos($newEmail,"@")==false or strpos($newEmail,".",strpos($newEmail,"@"))==false) {
		echo strpos($newEmail,".", strpos($newEmail,"@"));
		echo'<script type="text/javascript">alert( "Пожалуйста, укажите корректный E-mail.");</script>';
	}else{
		DataHandler::SetNewNote($newName, $newEmail, $newNText);
		echo'<script type="text/javascript">alert( "Новая задача успешно добавлена!");</script>';
	}


}




$NoteList = DataHandler::GetNotes($SortType);
$NoteSum = count($NoteList);

if(isset($_GET['Post']) and isset($_GET['page']) and isset($_GET['Sort']) and isset($_GET['check'])){
    if ($_GET['check']=="true"){
        DataHandler::SetNoteReady($NoteList[$_GET['Post']]["id"],"true");
    }else{
        DataHandler::SetNoteReady($NoteList[$_GET['Post']]["id"],"false");
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
<div class="container" style="background-color: #26a69a; padding: 10px; display: flex; justify-content: space-between; color: white;">
	<h1>MyNote</h1>
</div>

<div class="container" style="margin-top: 10px; display: flex; justify-content: space-between; width: 100%;">
	<h2>Задачи:</h2>
	<div>
		<div class="container">
		  <div class="row">
		  	<?php
			  	echo '<div class="col-sm" align="center">';
			  	if ($SortType == 1){
			  		echo '<a href="index.php?Sort=-1">Сортировать<br>по имени (Я-А)</a>';
			  	}else{
			  		echo '<a href="index.php?Sort=1">Сортировать<br>по имени (А-Я)</a>';
			  	}
			  	echo '</div><div class="col-sm" align="center">';
			  	if ($SortType == 2 ){
			  		echo '<a href="index.php?Sort=-2">Сортировать<br>по почте (Z-A)</a>';
			  	}else{			  		
			  		echo '<a href="index.php?Sort=2">Сортировать<br>по почте (A-Z)</a>';
			  	}
			  	echo '</div><div class="col-sm" align="center">';
			  	if ($SortType == 3 ){
			  		echo '<a href="index.php?Sort=-3">Сортировать<br>по статусу (Обратно)</a></div>';
			  	}else{			  		
			  		echo '<a href="index.php?Sort=3">Сортировать<br>по статусу (Обратно)</a></div>';
			  	}
		  	?>
		    </div>
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
			echo '<br><i style="font-size:0.8em;">Отредактировано администратором</i>';
		}
        if ($_SESSION['account']=="admin"){
            echo '<br><a href="admin.php?Post='. strval($i+$Page*3) . '&Sort=' . $SortType  . '">Изменить</a>';
            if ($NoteList[$i+$Page*3]["isteady"]=="Statusfalse"){
				echo ' | <a href="index.php?page='. strval($Page+1) . '&Post='. strval($i+$Page*3) . '&Sort=' . $SortType  . '&check=' . "true"  . '">Отметить, как выполненое</a>';
			}else{
				echo ' | <a href="index.php?page='. strval($Page+1) . '&Post='. strval($i+$Page*3) . '&Sort=' . $SortType  . '&check=' . "false"  . '">Отметить, как не выполненое</a>';
			}
		}
		echo "<hr></li>";
	}
	
}
?>
</ul>
<div class="container" style="text-align: center; font-size: 1.2em;">
	Страница <?php 
	if ($NoteSum%3 == 0){
		echo strval(intval($Page)+1) . " из " . strval(intval((int)($NoteSum/3))) ;
	}else{
		echo strval(intval($Page)+1) . " из " . strval(intval((int)($NoteSum/3)+1)) ;
	}

	?><br>
	<?php

	if ($Page > 0){
		echo '<a href="index.php?page='. $Page . '&Sort=' . $SortType . '"> На '. strval(intval($Page)) . " страницу</a>";
	}
	if ($Page+1 < ($NoteSum/3)){
		echo '<a href="index.php?page='.strval(intval($Page)+2) . '&Sort=' . $SortType . '"> На '. strval(intval($Page)+2) ." страницу </a>";
	}
	?> 
	</div>
<hr>	
</div>



</div>
<div class="container" style="background-color: #4db6ac; padding: 10px; color: white;">
	<h2>Добавить задачу:</h2>
	<form action="index.php" method="post">
		<p>
			<?php
			echo 'Имя: <input type="text" name="name" value="' . $newName . '">';
			echo ' E-mail: <input type="text" name="email" value="' . $newEmail . '">';
			echo ' Суть задачи: <input size="30%" type="text" name="text" value="' . $newNText . '">';
			echo '<input style="display: none;" type="text" name="page" value="'. (int)($NoteSum/3) . '">';
			echo '<input type="submit">';
			?>
		</p>
	</form>
</div>

<div class="container" align="right" style="background-color: #00796b; padding: 10px;">
	<?php
	if ($_SESSION['account']=="admin"){
		echo '<a href="admin.php" style="color: white;">Администрирование</a>';
		echo '<a href="index.php?status=exit" style="margin-left:15px; color: white;">Выход</a>';
	}else{
		echo '<a href="admin.php" style="color: white;">Вход</a>';
	}

	?>	
</div>
</body>
</html>

