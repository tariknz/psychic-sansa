
    <h3>Processlist for DB2</h3>
    <div class="span11">
      <br />
      <div class="row">
        <div class="span1"><a class="btn refreshbutton" href="#">Pause</a></div>
        <div class="span6">
          <form action="#">
            <div class="btn-group" id="proclistgroup" data-toggle="buttons-radio">
              <?php 
                foreach ($_config as $key => $server) { 
                  echo '<button class="proccesstoggle btn" name="'.$key.'">'.$server['nickname'].'</button>';
                }
              ?>
            </div>
          </form>
        </div>
      </div>
      <div class="row">

      </div>
      <div class="row" id="loading" style="display: none;">
        <center>
          <br /><br />
          <p><b>Loading</b></p>
          <div class="progress progress-warning progress-striped active" style="width: 50%;">
            <div class="bar" style="width: 100%;"></div>
          </div>
          <br /><br />
        </center>
      </div>
      <div class="row"><div id="results"></div></div>
      <div class="row"><p><a class="btn refreshbutton" href="#">Pause</a></p></div>
    </div>

    <script type="text/javascript">

      window.setInterval(ajaxProcesslist, 1000);

      var paused = 0;
      var selected_server = '';

      function ajaxProcesslist(){
        if (paused == 0){
          $.post( "./classes/processlist-dbcall.php", { server: selected_server },
          function( data ) {
              $( "#results" ).html(data);
              $("#loading").css({ 'display': 'none'});
              $(".proccesstoggle").removeAttr("disabled");
            }
          );
        }

      }

      $(".proccesstoggle").click(
        function(event) {
          event.preventDefault(); 
          selected_server = event.target.name;
          $(".proccesstoggle").attr("disabled", "disabled");
          $("#results").html("");
          $("#loading").css({ 'display': 'block'});
          if(paused == 1)
            pauseOrResume();
          //ajaxProcesslist();
        }
      );


      $(".refreshbutton").click(function(event) {
        event.preventDefault(); 
        pauseOrResume();
      });



      function pauseOrResume(){
        $(".refreshbutton").toggleClass("btn-success");
        if(paused == 0){
          $(".refreshbutton").text("Resume");
          paused = 1;
        }
        else {
          $(".refreshbutton").text("Pause");
          paused = 0;
          ajaxProcesslist();
        }
      }


      $(document).ready(function(){
        $(".proccesstoggle").attr("disabled", "disabled");
        $("#results").html("");
        $("#loading").css({ 'display': 'block'});
        if(paused == 1)
          pauseOrResume();
        ajaxProcesslist();
      });

    </script>