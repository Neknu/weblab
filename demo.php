<?php
	include "includes/db.php";
	include "includes/bg.php";

date_default_timezone_set('Europe/Kiev');

add_msg(5);
add_server(5);

delete_msg(2);

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
	for($i = 0; $i < $n; $i++) {
		$msg = R::findOne( 'messages' , ' ORDER BY id DESC LIMIT 1 ' );
		R::trash($msg);
	}
	echo "Було видалено ". $n. " повідомлень!";
	echo "\n";
}


?>
