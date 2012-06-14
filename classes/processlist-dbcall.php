<?php

include_once('../_config.php');

$server = $_POST['server'];

if(isset($_config[$server])){
  $db = $_config[$server];
}else{
  $db = $_config['db'];
}

$result = connect($db);

if(isset($result))
  output($result);


function connect($db)
{
  $debug = 1;

  try
  {
    $dbh    = new PDO("mysql:host={$db['hostname']}", $db['username'], $db['password'], array(PDO::ATTR_TIMEOUT => "5"));

    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Throw PDOException.

    $sql  = "show full processlist;";
    $stmt   = $dbh->query($sql);
    $array  = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $array;

    $dbh = null;

    return $array;
  } catch (PDOException $e){ 
    echo "<div class=\"alert alert-error\"><p><b>Seems like your server ({$db['hostname']}) could be down</b></p>"; 
    echo "<p>{$e->getMessage()}</p></div>"; 
  }

}


function output($array){

	foreach ($array as $key => $row) {
	    $time[$key]  = $row['Time']; 
	}


	array_multisort($time, SORT_DESC, $array);
	
	//echo '<pre>';
  echo '<div id="content">';
  echo '<br /><table id="proclist" class="process table" width = "100%">';
  echo '<col width="55px">';
  echo '<col width="100px">';
  echo '<col width="160px">';
  echo '<col width="65px">';
  echo '<col width="65px">';
  echo '<col width="45px">';
  echo '<col width="120px">';
  echo '<tr><th>ID</th><th>User</th><th>Host</th><th>DB</th><th>Status</th><th>Time</th><th>State</th><th>Query</th></tr>';

	foreach ($array as $rows => $row)
	{
    if ($row['Time'] > 30)
		  echo '<tr class="red">';
    else if ($row['Time'] > 10)
       echo '<tr class="yellow">';
    else if ($row['Time'] > 1)
       echo '<tr class="light">';
    else
      echo '<tr>';

		foreach ($row as $col => $cell)
		{
      $long = $cell;
      if(strlen($cell) > 100)
        $cell = substr($cell, 0, 100) . '..';

      $cell = trim( preg_replace( '/\s+/', ' ', $cell ) ); 

			if($col == 'Query') echo '<td class="query" title="'.$long.'">';
      else echo '<td>';
      if($col == 'State' && strlen($cell) > 1) echo '<b>[';
      echo $cell;
      if($col == 'State' && strlen($cell) > 1) echo ']</b>';
      echo '</td>';
		}	
	  	echo "</tr>";
	}
	echo '</table><br />';
    echo '</div><p>Total Processes: ' . count($array) .'';	
	//echo '</pre>';
}

/*
function output_old($result){

    echo '<div id="content">';
    echo '<br /><table cellpadding="0" cellspacing="0" class="db-table" width = "100%">';
    echo '<tr><th>ProcessID</th><th>User</th><th>Host</th><th>Database</th><th>Status</th><th>Time<th>Query</th></tr>';
    while($row = mysql_fetch_row($result)) {
      echo '<tr>';
      foreach($row as $key=>$value) {
        echo '<td>',$value,'</td>';
      }
      echo '</tr>';
    }
    echo '</table><br />';
    echo '</div>';


}
*/
?>