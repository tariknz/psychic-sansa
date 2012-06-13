<?php
	$host = 'localhost';
	$user = 'root';
	$password = '';

	try {
		$db = new PDO("mysql:dbname=analysis;host=$host", $user, $password );
		
		$sql = "SELECT TIME_STAMP, SUM(DATA_LENGTH)/1024/1024 AS 'Database Size' FROM analysis_tables WHERE TABLE_SCHEMA = 'mw08' GROUP BY TIME_STAMP";
		
		$i = 0;
		foreach ($db->query($sql) as $row)
		{
			$timestamp[$i] = $row["TIME_STAMP"];
       		$dbsize[$i] = $row["Database Size"];
       		$i++;
		}

		$db = null; //close connection


		$myFile = "F:/xampp/htdocs/dba/logs/graph.csv";
		$fh = fopen($myFile, 'w') or die("can't open file");
		$stringData = "Date,DB Size (MB)\n" ;
		fwrite($fh, $stringData);

		for($i = 0; $i < sizeof($timestamp); $i++)
		{
			fwrite($fh, $timestamp[$i] . "," . $dbsize[$i] . "\n" );
		}


		fclose($fh);


	}
	catch(PDOException $e)
	{
		echo $e->getMessage();
	}
?>

<div>
<h3>DB Growth (DB2)</h3>
<br />
<div id="graphdiv" style="width: 100%; height: 100%; max-height: 400px"></div>
<button id="restore" class="btn">Reset</button>
</div>



<script type="text/javascript" src="assets/js/dygraph-combined.js"></script>
<script src="./assets/js/interaction-api.js"></script>
<script type="text/javascript">

	var once = 1;
	$(document).ready(function(){


		Dygraph.addEvent(document, "mousewheel", function() { lastClickedGraph = null; });
	    Dygraph.addEvent(document, "click", function() { lastClickedGraph = null; });

		g = new Dygraph(
		    // containing div
		    document.getElementById("graphdiv"),
		    // CSV or path to a CSV file.
		    "./logs/graph.csv",
		    {
				axes: { 
				  x: { 
				    ticker: function(a, b, pixels, opts, dygraph, vals) { 
				      return Dygraph.getDateAxis(a, b, Dygraph.DAILY, opts, dygraph); 
				    } 
				  } 
				},
				//rollPeriod: 7,
				labelsDivStyles: {
		                'text-align': 'left',
		                'background': 'white',
		                'width' : '300px',
		                'margin-top' : '-20px'
		              },
		        showRangeSelector: true,
		        rangeSelectorHeight: 30,

		        interactionModel: {
		            'mousedown' : downV3,
		            'mousemove' : moveV3,
		            'mouseup' : upV3,
		            'click' : clickV3,
		            'dblclick' : dblClickV3,
		            'mousewheel' : scrollV3
		        },
		        
		        //dateWindow: [ Date.parse("2012/05/31 00:00:00"), Date.parse("2012/06/07 00:00:00") ],

		        strokeWidth: 1.5
		    	,  
		    	drawCallback: function(is_initial, g) { 
		    		if (!is_initial) return; 
		    			if(once == 1) {
		    				initRange();
		    		    }
		    	}
		    }

  		);



	});
	
	

	function initRange() {
        once = 0;
        var axis = g.xAxisRange();
        var dateWindow = [ axis[1] - 86400000 * 10, axis[1]];
        g.updateOptions({
             dateWindow: dateWindow
        });

        g.setAnnotations([
		{
			series: "DB Size (MB)",
			x: "2012-05-29 08:59:20",
			shortText: "L",
			text: "Migrated 2012 Logs to Log2 (due to Log table corruption)"
		}
		]);

		g.resize(600,400);
	}

     
    document.getElementById("restore").onclick = function() {
        restorePositioning(g);
        initRange();

    }

</script>