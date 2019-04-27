<?php
	include "includes/db.php";
	include "includes/bg.php";

	$data = $_POST;
	

	if(isset($data['do_send'])) {
		if($data['type'] == "all")
			$servers = R::findall('servers', "ORDER BY ID");
		else {

		$servers = array();
		$i = 0;
		$serv = R::findAll('servers', "ORDER BY ID"); 
		foreach($serv as $s) {
			if($s->ownMessagesList) {
				foreach($s->ownMessagesList as $msg) {
					
					if($msg->type == $data['type'] && $msg->level >= $data['level']) {
						$servers[$i] = $s;
						$i++;
						break;
					}
				}
			}
		}				
		}
	}
	else
	$servers = R::findall('servers', "ORDER BY ID"); 	

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
	
	

	

<body>
	<?require "includes/topmenu.php"; ?>
	
<!-- Page content -->
	
<div class="w3-content w3-padding-large">

	<form action="index_server.php" method="post">
	<div class="w3-row-padding">
	<div class="w3-half w3-margin-bottom" >
		<label class="w3-text-indigo w3-large"><b>Дата перезавантаження (можна шукати окремо від 2 наступних):</b></label>
	</div>
<div class="w3-half w3-margin-bottom" >	
	
<input id="date1" class="w3-input w3-border" name="date1" type="date" onchange="myFunction()" required/>
	
</div>
	</div>
	<div class="w3-row-padding">
<div class="w3-third w3-margin-bottom">	
  <select name = "type" id="mySelect" class="w3-select w3-border" required>
    <option value="all" >Будь-який тип</option>
    <option value="debug">debug</option>
    <option value="info">info</option>
    <option value="warning">warning</option>
	<option value="error">error</option>
	<option value="fatal">fatal</option>
  </select>
</div>

	
		<div class="w3-third w3-margin-bottom">
<input id="level" class="w3-input w3-border" name="level" type="number" step="0.001" placeholder="Рівень завантаженості: 0..1" required/>
		</div>
		

		<div class="w3-third">
			<button class="w3-btn w3-teal" name="do_send">Знайти</button>
		</div>
	</div>
</form>
	
	
<table id="myTable" class="w3-table-all">
    <thead>
      <tr class="w3-indigo">
		<th>Номер:</th>
        <th>Назва:</th>
        <th>IP адрес:</th>
		<th>Дата перезавантаження:</th>
		<th>К-ть повідомлень:</th>
      </tr>
    </thead>
	<?php foreach($servers as $server)
		{ ?>
    <tr>
	  <td>	<a href="server.php?id=<?php echo $server->id ?>"><?php echo $server->id ?></a></td>
	  <td><a href="server.php?id=<?php echo $server->id ?>"><?php echo $server->name ?></a></td>
      <td><?php echo $server->ip ?></td>
	  <td><?php echo $server->date_onov ?></td>
	  <td><?php echo count($server->ownMessagesList) ?></td>
    </tr>
	<?php } ?>
  </table>
	
</div>
	

<script>
	
myFunction();
	
function myFunction() {
 var input, filter, select, table, level, date1, date2, currdate, leveltxt, tr, type, td, i, txtValue, selValue;
  date1 = document.getElementById("date1");
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");
 for (i = 1; i < tr.length; i++) {
	currdate = tr[i].getElementsByTagName("td")[3];
	td = tr[i].getElementsByTagName("td")[1];
      if (date1.value > currdate.textContent || !date1.value ) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
  }
}
	
</script>
	
</body>
</html>

