<?php
    require "includes/db.php";
	require "includes/bg.php";

	date_default_timezone_set('Europe/Kiev');
	$date = date("Y-m-d H:i:s");

$servers = R::findAll('servers');

$data = $_POST;

    if( isset($data['do_send']) ) {	
		$server = R::findOne('servers', 'name = ?', array($data['server']));
    $msg = R::dispense('messages');
		$msg->text = $data['text'];
		$msg->type = $data['type'];
		$msg->priority = $data['priority'];
		$msg->level = $data['level'];
		$msg->added_date = $date;
		$msg->server = $data['server'];
    $server->ownMessagesList[] = $msg;
   R::store($server);
		echo '<div class="w3-text w3-large w3-green">Повідомлення успішно додано!</div>';
		}
?>

	<?require "includes/topmenu.php"; ?>




<link rel="stylesheet" href="includes/w3.css">
<div class="w3-content" >

	<form class="w3-container" method="post" action="add_msg.php">
  <h1>Додати нове повідомлення:</h1>
  <p>      
    <label class="w3-text-indigo"><b>Текст повідомлення:</b></label>
    <input class="w3-input w3-border" name="text"  autocomplete="off" type="text" placeholder="Текст..." required></p>
		
  <p>      
    <label class="w3-text-indigo"><b>Тип повідомлення:</b></label>
  
  <select class="w3-select w3-border" name="type" required>
    <option value="" disabled selected>Оберіть тип повідомлення...</option>
    <option value="debug">debug</option>
    <option value="info">info</option>
    <option value="warning">warning</option>
	<option value="error">error</option>
	<option value="fatal">fatal</option>
  </select>
 </p>	  
  <p>      
    <label class="w3-text-indigo"><b>Пріоритет повідомлення:</b></label>
    <input class="w3-input w3-border" name="priority" type="number" onchange="handleChange(this);" placeholder="0..200" required />
	  
	  <p>      
    <label class="w3-text-indigo"><b>Рівень завантженості системи:</b></label>
    <input class="w3-input w3-border" name="level" type="number" step="0.001" onchange="handleChange2(this);" placeholder="0..1" required />
 	
    <p>      
    <label class="w3-text-indigo"><b>Оберіть сервер повідомлення:</b></label>
  
  <select class="w3-select w3-border" name="server" required>
    <option value="" disabled selected>Оберіть сервер повідомлення...</option>
	  <?php foreach($servers as $serv) { ?>
    <option value="<?php echo $serv->name ?>"><?php echo $serv->name ?></option>
	  <?php } ?>
  </select>
 </p>
		  
  <p><button class="w3-btn w3-teal" name="do_send">Додати</button></p>
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