<?php
    require "includes/db.php";
	require "includes/bg.php";


$data = $_POST;


    if( isset($data['do_send']) ) {
		$name = R::find('servers', 'name = ?', array($data['name']));
		$ip = R::find('servers', 'ip = ?', array($data['ip']));
		if ($name || $ip) {
			echo '<div class="w3-text w3-large w3-red">IP та(або) назва мають бути унікальними!</div>';
		}
		else{
		$server = R::dispense('servers');
		$server->name = $data['name'];
		$server->ip = $data['ip'];
		$server->date_onov = $data['date'];
		R::store($server);
		echo '<div class="w3-text w3-large w3-green">Сервер успішно доданий!</div>';
		}
		}
?>

	<?require "includes/topmenu.php"; ?>




<link rel="stylesheet" href="includes/w3.css">
<div class="w3-content" >

	<form class="w3-container" method="post" action="add_server.php">
  <h1>Додати новий сервер:</h1>
  <p>      
    <label class="w3-text-indigo"><b>Назва серверу:</b></label>
    <input class="w3-input w3-border" name="name" type="text"  placeholder="Назва..." value="<?php echo $data['name'] ?>" required></p>
		
  <p>      
    <label class="w3-text-indigo"><b>IP адрес серверу:</b></label>
	  <input class="w3-input w3-border" name="ip" type="text" minlength="7" maxlength="15"  value="<?php echo $data['ip'] ?>" placeholder="xxx.xxx.x.x" size="15" pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
 required></p>     
    
 	<p>
		<label class="w3-text-indigo"><b>Дата останнього оновлення:</b></label>
<input class="w3-input w3-border" name="date" type="date" value = "<?php echo $data['date'] ?>" onchange="myFunction()" required/>
	</p>
  
  <p><button class="w3-btn w3-teal" name="do_send">Додати</button></p>
</form>
</div>

