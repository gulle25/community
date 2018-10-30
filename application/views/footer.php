<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
        </div>
      </div>
    </div>
    <!-- <script src="/application/libraries/bootstrap/js/bootstrap.min.js"></script> -->
  </body>

  <script type="text/javascript">
    $('#menu').click(function(){
        $('.ui.sidebar').sidebar('setting', 'transition', 'overlay').sidebar('toggle');
    })
    function ToggleMenu() {
      $("sidebar-wrapper").toggleClass("wrapper.toggled");
    }
  </script>
</html>