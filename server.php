<?php
    require "includes/db.php";
	require "includes/bg.php";

$server = R::load('servers', $_GET['id']);

$data = $_POST;
	if( isset($data['do_delete']) ) {
		R::trash($server);
		echo '<div class="w3-text w3-large w3-green">Сервер успішно видалено!</div>';
	}
		
     if( isset($data['do_send']) ) {
		$server->name = $data['name'];
		$server->ip = $data['ip'];
		$server->date_onov = $data['date'];
		R::store($server);
		echo '<div class="w3-text w3-large w3-green">Сервер успішно змінений!</div>';
	}
?>

	<?require "includes/topmenu.php"; ?>




<link rel="stylesheet" href="includes/w3.css">
<div class="w3-content" >
	<form class="w3-container" method="post" action="server.php?id=<?php echo $_GET['id'] ?>">
  <h1>Змінити сервер:</h1>
  <p>      
    <label class="w3-text-indigo"><b>Назва серверу:</b></label>
    <input class="w3-input w3-border" name="name"  autocomplete="off" type="text" value="<?php echo $server->name ?>" placeholder="Назва..." required></p>
		
  <p>      
    <label class="w3-text-indigo"><b>IP адрес серверу:</b></label>
	  <input class="w3-input w3-border" name="ip" type="text" minlength="7" maxlength="15"  value="<?php echo $server->ip ?>" placeholder="xxx.xxx.x.x" size="15" pattern="^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$"
 required></p>     
    
 	<p>
		<label class="w3-text-indigo"><b>Дата останнього оновлення:</b></label>
<input class="w3-input w3-border" name="date" value="<?php echo $server->date_onov ?>" type="date" onchange="myFunction()" required/>
	</p>
 <?php if($server->name != 'localhost') { ?> 
  <div class="w3-half">
	  <p><button class="w3-btn w3-teal" name="do_send">Змінити</button></p>
  </div>

  <div class="w3-half">
   	  <p><button class="w3-btn w3-red" name="do_delete">Видалити</button></p>
  </div>
<?php } ?>
</form>
</div>



<script>
  function handleChange(input) {
    if (input.value < 0) input.value = 0;
    if (input.value > 200) input.value = 200;
  }
	
  function handleChange2(input) {
    if (input.value < 0) input.value = 0;
    if (input.value > 1) input.value = 1;
  }
</script>