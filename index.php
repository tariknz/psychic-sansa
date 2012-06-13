<?php include_once("_config.php"); ?>
<?php 

  //Navigational Pages
  $_pages = array(
    array(
      "Title" => "Processlist",
      "Page" => "processlist",
      "URL" => "processlist.php"
      ),
    array(
      "Title" => "Database Growth",
      "Page" => "dbgrowth"
      ),                   
    array(
      "Title" => "Database Compare",
      "Page" => "dbcompare"
      ),
    array(
      "Title" => "Replication status",
      "Page" => "replication"

      )
  );

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">

    <title>Interactive DBA</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <link href="./assets/css/bootstrap.css" rel="stylesheet">
    <link href="./assets/css/style.css" rel="stylesheet">

    <link href="./assets/css/bootstrap-responsive.css" rel="stylesheet">
    <script src="./assets/js/jquery-1.7.2.min.js"></script>

    <!-- IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="../assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="../assets/ico/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="../assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="../assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="../assets/ico/apple-touch-icon-57-precomposed.png">
  </head>

  <body>

    <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="#">DBA Dash</a>
         
        <?php //this is the username/sign out top right ?> 
        <!--
          <div class="btn-group pull-right">
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> Username
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li><a href="#">Profile</a></li>
              <li class="divider"></li>
              <li><a href="#">Sign Out</a></li>
            </ul>
          </div>
        -->

        <?php //navigation on black bar ?> 

        <!--
          <div class="nav-collapse">
            <ul class="nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="processlist.php">Process List</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
          </div><!--/.nav-collapse -->

        </div>
      </div>
    </div>

    <div class="container-fluid">
      <div class="row-fluid">
        <div class="span3 offset4">
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Quick Stats</li>

              <?php 

              foreach ($_pages as $key => $page) {
                echo '<li><a href="index.php?p=' . $page['Page'] . '">'. $page['Title'] .'</a></li>';
              } 

              ?>

            </ul>
          </div><!--/.well -->
          <div class="well sidebar-nav">
            <ul class="nav nav-list">
              <li class="nav-header">Status</li>

              <?php 

              include("./pages/status.php");

              ?>

            </ul>
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9 offset4">
          <div class="row-fluid">


            <div class="span12">
              
              <?php 

                if(isset($_GET['p']))
                {
                 
                  $p = $_GET['p'];

                  foreach ($_pages as $key => $page) {
                    if($p == $page['Page']){
                      include('./pages/'.$page['Page'].'.php');
                      break;
                    }   
                  }
                } else {
                  include('./pages/processlist.php'); 
                }
                
              ?>

            </div><!--/span-->



          </div><!--/row-->
        </div><!--/span-->
      </div><!--/row-->

      <hr>

      <footer>
        <p>&copy; 2012</p>
      </footer>

    </div><!--/.fluid-container-->

    <script src="./assets/js/bootstrap.min.js"></script>

  </body>
</html>
