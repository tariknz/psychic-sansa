<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="./assets/css/bootstrap.css" rel="stylesheet">
<style type="text/css">
  body {
    padding-left: 60px;
    padding-top: 60px;
    padding-bottom: 40px;
  }
  .sidebar-nav {
    padding: 9px 0;
  }
</style>
<link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">
</head>
<body>

<?
$csv1 = "./csv/articles-05-31-db2.csv";
$csv2 = "./csv/articles-05-31-repl.csv";


$expected = loadCSV($csv1);
$actual = loadCSV($csv2);


compare2DArray($expected, $actual);

function compare2DArray($expected, $actual){
	for ($i=1; $i < sizeof($expected) ; $i++) { 
		for ($j=0; $j < sizeof($expected[$i]); $j++) { 
			if(strcmp($expected[$i][$j],$actual[$i][$j]) > 0){
				echo "<b>Missmatch occured in Row: {$i} In column \"{$expected[1][$j]}\"</b><br /><br />";
				if($actual[$i][$j] == NULL)
					$actual[$i][$j] = '[NULL]';

				echo "For ID: {$expected[$i][0]} 
					<ul>
						<li>E: {$expected[$i][$j]}</li>
						<li>A: {$actual[$i][$j]} </li>
					</ul>";
				echo "<br /><br />";
			}
		}
	}
}


function loadCSV($csvfile){
	$row = 1;
	if (($handle = fopen($csvfile, "r")) !== FALSE) {
	    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
	        $csv_arr[$row] = $data;
	        $row++;
	    }
	}

	return $csv_arr;
}


?>
</body>
</html>