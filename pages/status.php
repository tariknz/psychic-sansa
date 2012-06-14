	
	<table class="table" style="white-space: normal; ">
<?php 
	$xml = simplexml_load_file("{$_SERVER['DOCUMENT_ROOT']}/dba/logs/status.xml");

	for ($i = 0; $i < sizeof($xml->server); $i++) { 
		foreach($xml->server[$i]->attributes() as $a => $b) {
		    switch ($a) {
		    	case 'key':
		    		$key = $b;
		    		break;
		    	case 'status':
		    		$status = $b;
		    		break;
		    	case 'message':
		    		$status_message = $b;
		    		break;
		    }
		}
		if(isset($status))
			if($status == 'OK')
				$class = 'success';
			else if($status == 'ERROR')
				$class = 'important';
			else
				$class = 'warning';
		else
			$class = 'warning';


		$db = $_config["{$key}"];

		echo '<tr><td width="50px"><div><center><span class="label label-'.$class.'">'.$status.'</span></center></td><td>'. $db['nickname'] .' ('.$db['hostname'].')</td></tr></div>';

		if(isset($status_message))
			echo '<tr><td colspan="2"><div class="alert alert-error" style="margin-bottom: 0px;">'.$status_message.'</div></td></tr>';
		
		unset($status_message);
		unset($status);
		unset($key);
	}



/*
	foreach ($_config as $key => $server) {
		$file = $_SERVER['DOCUMENT_ROOT'] . '/dba/logs/'.$server['hostname'].'.last';
		
		$data = @file($file);
		$line = $data[0];

		$pattern = "\[(.*?)\]";

		$string = $line;
		$regex = '#\[(.*?)\]#';
		if (preg_match_all($regex, $string ,$matches)) {
		    $lastchecked = $matches[1];
			if (preg_match("/OK/", $line)) {
			    echo '<tr><td width="50px"><div><span class="label label-success">OK</span></td><td>'.$server['nickname'].'</td></tr></div>';
			} else {
			    echo '<tr><td><div><span class="label label-important">ERROR</span></td><td>'.$server['nickname'].'</td></tr></div>';
			}

		} else {
			    echo '<tr><td><div><span class="label label-warning">WARN</span></td><td>'.$server['nickname']. ' (cannot read log)</td></tr></div>';
		}
	}
*/

?>
	</table>