	
<table class="table" style="white-space: normal; ">
	<?php 
		$xml = simplexml_load_file("scheduled/logs/status.xml");

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


			$db = $DB_SERVERS["{$key}"];

			echo '<tr><td width="50px"><div><center><span class="label label-'.$class.'">'.$status.'</span></center></td><td>'. $db['nickname'] .' ('.$db['hostname'].')</td></tr></div>';

			if(isset($status_message))
				echo '<tr><td colspan="2" id="><div class="alert alert-error" style="margin-bottom: 0px;">'.$status_message.'</div></td></tr>';
			
			unset($status_message);
			unset($status);
			unset($key);
		}

	?>
</table>
<?php
		$lastupdated = (string)$xml->attributes()->date;

		$lastupdated_minutes = round(abs(strtotime($lastupdated) - strtotime(date('Y-m-d H:i:s'))) / 60,2);

		//echo '<div><i><p style="color: #888" alt="last updated">'.$lastupdated.'</i> <a href="#" id="refresh-status"><i class="icon-refresh"></i></a></p></div>';
		echo '<div><i><p style="color: #888" alt="last updated">'.$lastupdated.' ('.$lastupdated_minutes.' minutes ago)</i></p></div>';

?>

<script type="text/javascript">

      $(document).ready(function(){

      });

	  $("#refresh-status").click(
	    function(event) {
	      event.preventDefault(); 
	    }
	  );


</script>