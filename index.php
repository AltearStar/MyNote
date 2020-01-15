<?php

include 'datahandler.php';

$Page = 0;
$NoteSum = 3;
if (isset($_POST['name'])){
	$newName = $_POST['name'];
	$newEmail = $_POST['email'];
	$newNText = $_POST['text'];
	DataHandler::SetNewNote($newName, $newEmail, $newNText);
}
if (isset($_GET['page'])){
	$Page = intval($_GET['page'])-1 ;
}else{
	$Page = 0;
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
<div class="container" style="background-color: #26a69a; padding: 10px;"><h1>MyNote</h1></div>

<div class="container" style="margin-top: 10px;">
	<h2>Задачи:</h2>
	<hr>
<ul style="font-size: 1.2em;">	

<?php
$NoteList = DataHandler::GetNotes(0);
$NoteSum = count($NoteList);

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
		echo "<hr></li>";
	}
	
}
?>

</ul>
<div class="container" style="text-align: center; font-size: 1.2em;">
	Страница <?php echo strval(intval($Page)+1) ;?><br>
	<?php

	if ($Page > 0){
		echo '<a href="index.php?page='.$Page . '"> На '. strval(intval($Page)) ." страницу </a>";
	}
	if ($Page < intdiv($NoteSum, 3)){
		echo '<a href="index.php?page='.strval(intval($Page)+2) . '"> На '. strval(intval($Page)+2) ." страницу </a>";
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
</body>
</html>

