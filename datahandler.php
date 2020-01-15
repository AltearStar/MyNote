<?php

	
	$tname="Александр";
	$temail="alteer111@gmail.com";
	$ttext="Задача на решение задач!";

	//DataHandler::SetNewNote($tname, $temail, $ttext);
	//DataHandler::EditNote(2, $tname, $temail, $ttext);
	//DataHandler::SetNoteReady(2,true);
	
	//$kek = DataHandler::GetNotes(2);



//Класс для аботы с данными
class DataHandler{
	private $Data;


	public static function SetNewNote($name, $email, $text){
		$myFile = new File(true);
		$id = $myFile->GetNewID()-1;
		$data = $id . "|`1|" . $name . "|`2|" . $email . "|`3|" . $text . "|`4|" . "Statusfalse" . "|`5|" . "false" . "|`6|" ; // . "|`|" - Разделитель полей в строке
		$myFile->WriteData($id, $data);
	}

	public static function GetNotes($SortType){
		$myFile = new File(false);	
		for ($i=0; $i<$myFile->GetNewID()-1; $i++){
			$data = $myFile->GetData($i);
			$NoteList[$i] = array("id"=>$i,"name"=>DataManipualtion::GetValue($data, 2),"email"=>DataManipualtion::GetValue($data, 3),"text"=>DataManipualtion::GetValue($data, 4),"isteady"=>DataManipualtion::GetValue($data, 5),"changed"=>DataManipualtion::GetValue($data, 6));
		}

		/*for ($i=0; $i<$myFile->GetNewID()-1; $i++){
			echo $NoteList[$i]["name"];
			echo " ".$NoteList[$i]["email"];
			echo " ".$NoteList[$i]["text"];
			echo "<br>";
		}
		echo "<hr>";*/

		$NoteList2 = DataHandler::SortNotes($NoteList, $SortType);
		return $NoteList2;
	}

	public static function EditNote($NoteID,$name, $email, $text){
		$myFile = new File(true);		
		$data = $myFile->GetData($NoteID);
		$data = $NoteID . "|`1|" . $name . "|`2|" . $email . "|`3|" . $text . "|`4|" . DataManipualtion::GetValue($data, 5) . "|`5|" . "true" . "|`6|" ; // . "|`|" - Разделитель полей в строке
		$myFile->WriteData($NoteID, $data);
	}

	public static function SetNoteReady($NoteID,$Status){
		$myFile = new File(true);
		$data = $myFile->GetData($NoteID);
		if ($Status=="true"){

			echo "вход в тру";
			$data = $NoteID . "|`1|" . DataManipualtion::GetValue($data, 2) . "|`2|" . DataManipualtion::GetValue($data, 3) . "|`3|" . DataManipualtion::GetValue($data, 4) . "|`4|" . "Statustrue" . "|`5|" . DataManipualtion::GetValue($data, 6) . "|`6|" ;
		}
		else{
			$data = $NoteID . "|`1|" . DataManipualtion::GetValue($data, 2) . "|`2|" . DataManipualtion::GetValue($data, 3) . "|`3|" . DataManipualtion::GetValue($data, 4) . "|`4|" . "Statusfalse" . "|`5|" . DataManipualtion::GetValue($data, 6) . "|`6|" ;
		}
		str_replace("\n","",$data);
		$myFile->WriteData($NoteID, $data);
	}

	public static function SortNotes($NoteList ,$SortType){

		for ($i=0; $i<count($NoteList); $i++){
			$IDArray[$i] = $NoteList[$i]["id"];
			$NameArray[$i] = $NoteList[$i]["name"];
			$EmailArray[$i] = $NoteList[$i]["email"];
			$TextArray[$i] = $NoteList[$i]["text"];
			$IsteadyArray[$i] = $NoteList[$i]["isteady"];
			$ChangedArray[$i] = $NoteList[$i]["changed"];
		}
		
		switch ($SortType) {
			case 0://Сортировка по ID
				array_multisort($IDArray, $IsteadyArray, $NameArray, $EmailArray, $TextArray, $ChangedArray);
				break;
			case 1://Сортировка по Имени
				array_multisort($NameArray, $IDArray, $IsteadyArray, $EmailArray, $TextArray, $ChangedArray);
				break;
			case 2://Сортировка по почте
				array_multisort($EmailArray, $IDArray, $IsteadyArray, $NameArray, $TextArray, $ChangedArray);
				break;
			case 3://Сортировка по выполнению
				array_multisort($IsteadyArray, $IDArray, $NameArray, $EmailArray, $TextArray, $ChangedArray);
				break;
			
			default://Сортировка по ID
				array_multisort($IDArray, $IsteadyArray, $NameArray, $EmailArray, $TextArray, $ChangedArray);
				break;
		}

		for ($i=0; $i<count($NoteList); $i++){
			$NoteList[$i]["id"] = $IDArray[$i];
			$NoteList[$i]["name"]= $NameArray[$i];
			$NoteList[$i]["email"]= $EmailArray[$i];
			$NoteList[$i]["text"]= $TextArray[$i];
			$NoteList[$i]["isteady"]= $IsteadyArray[$i];
			$NoteList[$i]["changed"]= $ChangedArray[$i];
		}
		return $NoteList;
	}

}



//Класс для работы с файлом (Ввод/вывод)
class File
{
	private $File;
	private $FileArray;
	function __construct($Write)
	{
		$this->FileArray = file('data.txt');
		if ($Write){
			$this->File = fopen('data.txt','w+');
		}else{
			$this->File = fopen('data.txt','r');			
		}
	}

	public function WriteData($ID, $Data){
		for ($i = 0; $i < sizeof($this->FileArray)-1; $i++){
			if ($ID==$i){
				$test = fwrite($this->File, $Data."\n");
			}else{
				$test = fwrite($this->File, $this->FileArray[$i]); 
			}	
			if ($test==false){break;} 
		}
		if ($ID>$i-1){
			$test = fwrite($this->File, $Data."\n--END--");
		}else{
			$test = fwrite($this->File, "--END--");
		}
		if ($test) /*echo 'Данные в файл успешно занесены.'*/;
		else echo 'Ошибка при записи в файл.';
		fclose($this->File); 
	}

	public function GetData($ID){
		return $this->FileArray[$ID];	
	}
	public function GetNewID(){
		return sizeof($this->FileArray);
	}

}

class DataManipualtion{
	
	static public function GetValue($data, $mark){
		$mark2 = $mark-1;
		$startmark = strpos($data,"|`". $mark2 . "|") + 4;
		$endmark = strpos($data,"|`". $mark . "|");
		return substr($data, $startmark, $endmark-$startmark);
	}
}







?>