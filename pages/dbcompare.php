
<h3>Compare Master and Slave data via SQL</h3><br/>

<div class="row-fluid">
	<div class="span10">
		<form action="index.php?p=dbcompare" method="post">
			<textarea type="text" name="query" class="txt-query" style="overflow-y:hidden;" rows="1" onkeyup='this.rows = (this.value.split("\n").length||1);'><?php if(isset($_POST['query'])) echo trim($_POST['query']); else echo 'select * from '; ?></textarea>
			<input type="submit" id="compare_search" value="Compare" align="right"/>
		</form>
			<div>
				<?

				//$expected = loadCSV($csv1);
				//$actual = loadCSV($csv2);

				if(isset($_POST['query']))
				{
					$query = str_replace(array("\n","\r"),' ',$_POST['query']);

					//get db config
					$main_db = $DB_SERVERS['db'];
					$replication_db = $DB_SERVERS['db_repl'];


					$actual = getArray($main_db['hostname'],$main_db['username'],$main_db['password'],$main_db['default_db'],$query);

					if(isset($actual))
						$expected = getArray($replication_db['hostname'],$replication_db['username'],$replication_db['password'],$replication_db['default_db'],$query);

					if(isset($expected))
						compare2DArray($expected, $actual);

				}

				function compare2DArray($expected, $actual){
					$count = 0;
					$rows = 0;
					$echo = "";

					if(sizeof($expected) != sizeof($actual)){
						echo '<div class="alert alert-block"><p>Warning size mismatch</p>';
						echo '<p>Size of expected: '.sizeof($expected).'</p>';
						echo '<p>Size of actual: '.sizeof($actual).'</p></div>';
					} 
					else 
					{
						$maxsize = min(sizeof($expected), sizeof($actual));

						for ($i=0; $i < $maxsize; $i++) { 
						 	foreach ($expected[$i] as $j => $value) {

						 		if(strcmp($expected[$i][$j],$actual[$i][$j]) > 0){
									$echo .= "<b>Missmatch occured in Row: {$i} In column \"{$j}\"</b><br /><br />";
									if($actual[$i][$j] == NULL)
										$actual[$i][$j] = '<i>[NULL]</i>';

									reset($expected[$i]);
									$first_key = key($expected[$i]);

									reset($actual[$i]);
									$first_key2 = key($actual[$i]);

									if($first_key2 == $first_key)
										$echo .= "{$first_key}: {$expected[$i][$first_key]}";
									else
										$echo .= "LINE MISMATCH ----- EXPECTED KEY: {$first_key}: {$expected[$i][$first_key]} - ACTUAL KEY: {$first_key2}: {$actual[$i][$first_key2]}";
									
									$echo .= "<ul>
												<li>EXPECT: {$expected[$i][$j]}</li>
												<li>ACTUAL: {$actual[$i][$j]} </li>
											</ul>";
									$echo .= "<br />";
									$count++;
								}

							}
							$rows++;
						}

						if($count == 0) 
							echo '<div class="alert alert-success">';
						else 
							echo '<div class="alert alert-block">';
						
						echo '<p>Differences found: '. $count . ' -    Rows returned: '. $rows . '</p></div>';
						print $echo;
					}
				}

				function getArray($dbhost,$dbuser,$dbpass,$dbname,$query){

					try
					{
						$dbh    = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
						$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); //Throw PDOException.

						$sql  = $query;
						$stmt   = $dbh->query($sql);
						$array  = $stmt->fetchAll(PDO::FETCH_ASSOC);

						$dbh = null;

						return $array;
					} catch (PDOException $e){ 
						echo '<div class="alert alert-error">'. $e->getMessage() .'</div>'; 
					}
				}

				?>
		</div>
	</div>
</div>