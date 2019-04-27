<?php
    require "includes/db.php";
	require "includes/bg.php";

	date_default_timezone_set('Europe/Kiev');
	$date = date("Y-m-d H:i:s");

$message = R::load('messages', $_GET['id']);

$data = $_POST;
	if( isset($data['do_delete']) ) {
		R::trash($message);
		echo '<div class="w3-text w3-large w3-green">Повідомлення успішно видалено!</div>';
	}
		
    if( isset($data['do_send']) ) {
		$message->text = $data['text'];
		$message->type = $data['type'];
		$message->priority = $data['priority'];
		$message->level = $data['level'];
		$message->added_date = $date;
		R::store($message);
		echo '<div class="w3-text w3-large w3-green">Повідомлення успішно змінено!</div>';
		}
?>

	<?require "includes/topmenu.php"; ?>




<link rel="stylesheet" href="includes/w3.css">
<div class="w3-content" >

	<form class="w3-container" method="post" action="message.php?id=<?php echo $_GET['id'] ?>">
  <h1>Змінити повідомлення:</h1>
  <p>      
    <label class="w3-text-indigo"><b>Текст повідомлення:</b></label>
    <input class="w3-input w3-border" name="text" type="text" value="<?php echo $message->text ?>" required></p>
		
  <p>      
    <label class="w3-text-indigo"><b>Тип повідомлення (<?php echo $message->type ?>):</b></label>
  
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
    <input class="w3-input w3-border" name="priority" type="number" onchange="handleChange(this);" value="<?php echo $message->priority ?>" required />
	  
	  <p>      
    <label class="w3-text-indigo"><b>Рівень завантженості системи:</b></label>
    <input class="w3-input w3-border" name="level" type="number" step="0.001" onchange="handleChange2(this);" value="<?php echo $message->level ?>" required />
 	
  <div class="w3-half">
	  <p><button class="w3-btn w3-teal" name="do_send">Змінити</button></p>
  </div>
  <div class="w3-half">
   	  <p><button class="w3-btn w3-red" name="do_delete">Видалити</button></p>
  </div>
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