<?php
	include "includes/db.php";
	include "includes/bg.php";

date_default_timezone_set('Europe/Kiev');

echo '<pre>';

$time = 0;
$n = 4;
while($time < 10) {

$time_start = microtime_float();


add_msg($n);
add_server($n);

delete_msg($n / 2);

$time_end = microtime_float();
$time = $time_end - $time_start;
$n = $n*2;
echo "Час виконання: $time секунд";
echo '<br>';
}
echo "Значення N(к-ть повідомлень) = ". $n;
echo '<br>';
echo "Було використано ".memory_get_usage()."байтів пам'яті.";
echo '<br>';


function add_server($n) {
	for($i = 0; $i < $n; $i++) {
		$date = date("Y-m-d H:i:s");
		$server = R::dispense('servers');
		$server->name = rand() % 100000;
		$server->ip = rand() % 1000000;
		$server->date_onov = $date;
		R::store($server);
	}
	echo "Було додано ". $n. " серверів!";
	echo "\n";
}

function add_msg($n) {
	for($i = 0; $i < $n; $i++) {
		$date = date("Y-m-d H:i:s");
		$msg = R::dispense('messages');
		$msg->text = rand() % 1000;
		$rand = rand() % 5;
		if($rand == 0)
		$msg->type = "info";
		if($rand == 1)
		$msg->type = "debug";
		if($rand == 2)
		$msg->type = "warning";
		if($rand == 3)
		$msg->type = "error";
		if($rand == 4)
		$msg->type = "fatal";
		$msg->priority = rand() % 200;
		$msg->level = (rand() % 1000) / 1000;
		$msg->added_date = $date;
		$msg->server = "localhost";
		$server = R::findOne('servers', 'name = ?', array("localhost"));
		$server->ownMessagesList[] = $msg;
		R::store($server);
	}
	echo "Було додано ". $n. " повідомлень!";
	echo "\n";
}

function delete_msg($n) {
		$msg = R::findAll( 'messages' , " ORDER BY id DESC LIMIT $n" );
		R::trashAll($msg);
	echo "Було видалено ". $n. " повідомлень!";
	echo "\n";
}

function microtime_float()
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

?>
