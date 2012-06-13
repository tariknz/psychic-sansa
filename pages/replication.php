<?php

$db = $_config['db_repl']; 

//TODO: Support more than one slave
//foreach($hosts as $server) {
$errors = '';

echo '<h3>Replication status</h3>';
echo '<br />';
try
{
	$db = new PDO("mysql:host={$db['hostname']}", $db['username'], $db['password'] );
	$sql = "SHOW SLAVE STATUS";
	$stmt = $db->query($sql);
	$array  = $stmt->fetchAll(PDO::FETCH_ASSOC);


	if($array[0]['Slave_IO_Running'] == 'No') {
		$errors .= "Slave IO not running on $server\n";
		$errors .= "Error number: {$array[0]['Last_IO_Errno']}\n";
		$errors .= "Error message: {$array[0]['Last_IO_Error']}\n\n";
	}
	if($array[0]['Slave_SQL_Running'] == 'No') {
		$errors .= "Slave SQL not running on $server\n";
		$errors .= "Error number: {$array[0]['Last_SQL_Errno']}\n";
		$errors .= "Error message: {$array[0]['Last_SQL_Error']}\n\n";
	}

	echo '<div style="max-width:500px">';
	foreach ($array as $key => $status) {
		
		echo '<table class="table">';
		foreach ($status as $key => $stat) {
			echo '<tr><td>'. $key .'</td><td>'.$stat.'</td></tr>';
		}
		echo '</table>';
	}
	echo '</div>';

	//;echo '<pre>';
	//print_r($array);
	//echo '</pre>';
	
	$db = null;
}
catch(PDOException $e) {
	$errors .= $e->getMessage() ; 
}
//}

if (strlen($errors) > 0)
	echo '<div class="alert alert-error">'.$errors.'</div>';

?>