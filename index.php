<?php
	include "includes/db.php";
	include "includes/bg.php";
?>
<link rel="stylesheet" href="includes/w3.css">
<!doctype html>
<html>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<meta name="viewport" content="width=device-width, initial-scale=1">
<head>
<meta charset="utf-8">
<title>Журнал повідомлень</title>
</head>

<?php


function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}


function radixSort($array){
	//Create a bucket of arrays
	$bucket = array_fill(0, 9, array());
	$maxDigits = 0;
	//Determine the maximum number of digits in the given array.
	foreach($array as $value){
		$numDigits = strlen((string)$value);
		if($numDigits > $maxDigits)
			$maxDigits = $numDigits;
	}
	$nextSigFig = false;
	for($k=0; $k<$maxDigits; $k++){
		foreach($array as $a){
			if(!$nextSigFig)
				$bucket[($a->priority)%10][] =  $a;
			else
				$bucket[floor((($a->priority)/pow(10,$k)))%10][] =  $a;
		}
		//Reset array and load back values from bucket.
		$array = array();
		for($j=0; $j<count($bucket); $j++){
			foreach($bucket[$j] as $value){
				$array[] = $value;
			}
		}
		//Reset bucket
		$bucket = array_fill(0, 9, array());
		$nextSigFig= true;
	}
	return $array;
}

//Finishes sorting 10000 elements in ~0.68 seconds


function countSort($arr)
{

		$RANGE = 10000;
		$len = count($arr);
		$i = 0;

    $count = array_fill(0, $RANGE + 1, 0);

		$i =0;
    foreach($arr as $a) {
				if($a->type == "debug")
					$count[$i] = 1;
				if($a->type == "error")
					$count[$i] = 2;
				if($a->type == "fatal")
					$count[$i] = 3;
				if($a->type == "info")
					$count[$i] = 4;
				if($a->type == "warning")
					$count[$i] = 5;
				$i++;
		}
		$len = $i;


			$j = 0;
			$k = 0;
			while($j < $len) {
				$k++;
				$i = 0;
				foreach ($arr as $a) {
					if($count[$i] == $k) {
						$output[] = $a;
						$j++;
					}
					$i++;
				}
			}

			return $output;
}



function heapify(&$arr, $n, $i)
{
    $largest = $i;
    $l = 2*$i + 1;
    $r = 2*$i + 2;

    if ($l < $n && $arr[$l] > $arr[$largest])
        $largest = $l;

    if ($r < $n && $arr[$r] > $arr[$largest])
        $largest = $r;

    if ($largest != $i)
    {
        $swap = $arr[$i];
        $arr[$i] = $arr[$largest];
        $arr[$largest] = $swap;

        heapify($arr, $n, $largest);
    }
}


function heapSort(&$arr, $n)
{

    for ($i = $n / 2 - 1; $i >= 0; $i--)
        heapify($arr, $n, $i);

    for ($i = $n-1; $i >= 0; $i--)
    {

        $temp = $arr[0];
            $arr[0] = $arr[$i];
            $arr[$i] = $temp;


        heapify($arr, $i, 0);
    }
}


?>


<?php
$data = $_POST;


	if(isset($data['do_send'])) {
		$first = $data['field1'];
		$second = $data['field2'];
		$time_start = microtime_float();
		if($data['field1']) {
			if($data['field2']) {
				$messages = R::findAll('messages', "ORDER BY $first, $second");
			}
			elseif ($first == "type") {
				$messages = R::findAll('messages');
				$messages = countSort($messages);
			}
			elseif ($first == "priority") {
				$messages = R::findAll('messages');
				$messages = radixSort($messages);
			}
			else
			$messages = R::findAll('messages', "ORDER BY $first");
		} else
				$messages = R::findAll('messages', "ORDER BY ID");

				$time_end = microtime_float();
				$duration = $time_end - $time_start;
				echo "Сортування за полями: $first"." $second";
				echo '<br>';
				echo "Час, витрачений на сортування(c): ".$duration;
				echo '<br>';
				echo "Пам'ять витрачена на сортування(кб): ".memory_get_usage() /1024;
	} else
	$messages = R::findAll('messages', "ORDER BY ID");

?>


<body>
	<?require "includes/topmenu.php"; ?>

<!-- Page content -->

<div class="w3-content w3-padding-large">

	<div class="w3-row-padding">

<div class="w3-third" >

<input type="text" id="myInput" class = "w3-padding w3-margin-bottom" onkeyup="myFunction()" placeholder="Шукати за текстом..." title="Type in a name">

</div>

<div class="w3-third">
  <select id="mySelect" class="w3-select w3-border" onChange="myFunction()">
    <option value="" >Будь-який тип</option>
    <option value="debug">debug</option>
    <option value="info">info</option>
    <option value="warning">warning</option>
	<option value="error">error</option>
	<option value="fatal">fatal</option>
  </select>
</div>

		<div class="w3-third">
<input id="level" class="w3-input w3-border" name="level" type="number" step="0.001" onchange="myFunction()" placeholder="Рівень завантаженості: 0..1"/>
		</div>

		</div>

	<div class="w3-row-padding">
		<div class="w3-half w3-margin-bottom">
			<label class="w3-text-indigo"><b>Початкова дата повідомлення:</b></label>
<input id="date1" class="w3-input w3-border" name="date1" type="date" onchange="myFunction()"/>
		</div>

		<div class="w3-half w3-margin-bottom">
			<label class="w3-text-indigo"><b>Кінцева дата повідомлення:</b></label>
<input id="date2" class="w3-input w3-border" name="date2" type="date" onchange="myFunction()"/>
		</div>
	</div>

	<form action="index.php" method="post">
	<div class="w3-third w3-padding">
		<label class="w3-text-indigo"><b>Поле №1:</b></label>
	  <select name = "field1" class="w3-select w3-border">
			<option value="" >Будь-яке поле</option>
	    <option value="id">Номер</option>
	    <option value="text">Текст</option>
	    <option value="type">Тип</option>
		<option value="priority">Пріоритет</option>
		<option value="level">Завантаженість</option>
		<option value="added_date">Дата внесення</option>
	  </select>
	</div>

	<div class="w3-third w3-padding">
		<label class="w3-text-indigo"><b>Поле №2:</b></label>
	  <select name = "field2" class="w3-select w3-border">
	    <option value="" >Будь-яке поле</option>
	    <option value="id">Номер</option>
	    <option value="text">Текст</option>
	    <option value="type">Тип</option>
		<option value="priority">Пріоритет</option>
		<option value="level">Завантаженість</option>
		<option value="added_date">Дата внесення</option>
	  </select>
	</div>

	<div class="w3-third w3-padding">
		<button class="w3-margin w3-btn w3-teal" name="do_send">Сортувати</button>
	</div>
</form>


<table id="myTable" class="w3-table-all">
    <thead>
      <tr class="w3-indigo">
		<th>Номер:</th>
        <th>Текст:</th>
        <th>Тип:</th>
		<th>Пріоритет:</th>
		<th>Завантаженість:</th>
		<th>Дата внесення:</th>
      </tr>
    </thead>
	<?php foreach($messages as $msg)
		{ ?>
    <tr>
	  <td>	<a href="message.php?id=<?php echo $msg->id ?>"><?php echo $msg->id ?></a></td>
	  <td><a href="message.php?id=<?php echo $msg->id ?>"><?php echo $msg->text ?></a></td>
      <td><?php echo $msg->type ?></td>
	  <td><?php echo $msg->priority ?></td>
	  <td><?php echo $msg->level ?></td>
	  <td><?php echo $msg->added_date ?></td>
    </tr>
	<?php } ?>
  </table>

</div>


<script>
function myFunction() {
  var input, filter, select, table, level, date1, date2, currdate, leveltxt, tr, type, td, i, txtValue, selValue;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  level = document.getElementById("level");
  select = document.getElementById("mySelect");
  date1 = document.getElementById("date1");
  date2 = document.getElementById("date2");
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
	type = tr[i].getElementsByTagName("td")[2];
	leveltxt = tr[i].getElementsByTagName("td")[4];
	currdate = tr[i].getElementsByTagName("td")[5];
    if (td || type) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1 && (select.value == type.textContent || !select.value) && level.value <= leveltxt.textContent && (((date1.value < currdate.textContent) &&
	     (date2.value > currdate.textContent)) || !date1.value
		  || !date2.value )) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

</script>

</body>
</html>
