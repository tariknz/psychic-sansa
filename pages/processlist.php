
    <h3>Processlist for DB2</h3>
    <div class="span11">
      <br />
      <div class="row">
        <div class="span2"><a class="btn" id="refreshbutton" href="#">Pause/Resume</a></div>
        <div class="span6">
          <form action="#">
            <div class="btn-group" data-toggle="buttons-radio">
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

      <div class="row"><div id="results"> ... Loading ... </div></div>
      <div class="row"><p><a class="btn" id="refreshbutton2" href="#">Pause/Resume</a></p></div>
    </div>

    <script type="text/javascript">

      window.setInterval(ajaxProcesslist, 1000);

      var paused = 0;
      var selected_server = 'heeellooo';

      function ajaxProcesslist(){
        if (paused == 0){
          $.post( "./classes/processlist-dbcall.php", { server: selected_server },
          function( data ) {
                $( "#results" ).html(data);
            }
          );
        }

      }

      $(".proccesstoggle").click(
        function(event) {
          event.preventDefault(); 
          selected_server = event.target.name;
          ajaxProcesslist();
        }
      );




      $("#refreshbutton").click(function(event) {
        event.preventDefault(); 
        pauseOrResume();
      });

      $("#refreshbutton2").click(function(event) {
        event.preventDefault(); 
        pauseOrResume();
      });

      function pauseOrResume(){
        if(paused == 0){
          paused = 1;
        }
        else {
          paused = 0;
          ajaxProcesslist();
        }
      }

      $(document).ready(function(){
        ajaxProcesslist();
      });

    </script>