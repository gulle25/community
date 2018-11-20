<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
      </div>
        <footer class="footer">
          <div class="container-fluid">
            <div class="row text-muted">
              <div class="col-6 text-left">
                <ul class="list-inline">
                  <li class="list-inline-item">
                    <a class="text-muted" href="#">Support</a>
                  </li>
                  <li class="list-inline-item">
                    <a class="text-muted" href="#">Help Center</a>
                  </li>
                  <li class="list-inline-item">
                    <a class="text-muted" href="#">Privacy</a>
                  </li>
                  <li class="list-inline-item">
                    <a class="text-muted" href="#">Terms of Service</a>
                  </li>
                </ul>
              </div>
              <div class="col-6 text-right">
                <p class="mb-0">
                  &copy; 2018 - <a href="index.html" class="text-muted">AppStack</a>
                </p>
              </div>
            </div>
          </div>
        </footer></li></ul>
      </section>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="/application/libraries/appstack/dist/js/app.js"></script>
  <script src="/application/libraries/appstack/dist/js/charts.js"></script>
  <script src="/application/libraries/appstack/dist/js/forms.js"></script>
  <script src="/application/libraries/appstack/dist/js/maps.js"></script>
  <script src="/application/libraries/appstack/dist/js/tables.js"></script>

<script>
  var sidebar_mode = 1;

  $(document).ready(function() {
    window.addEventListener("resize", function() {
      check_sidebar();
    });

    check_sidebar();

    function check_sidebar() {
      var width = window.innerWidth ;
      var new_mode;

      if (width < 600) {
        new_mode = 1;
      } else {
        new_mode = 3;
      }

      set_sidebar(new_mode);
    }

    function set_sidebar(new_mode) {
      if (sidebar_mode == new_mode) {
        return;
      }
      var old_mode = sidebar_mode;
      sidebar_mode = new_mode;

      switch (old_mode) {
        case 1:
          $("#sidebar").removeClass("sidebar-small-hide");
          $("#article").removeClass("content-without-sidebar");
          break;
        case 3:
          $("#sidebar").removeClass("sidebar-large-show");
          $("#article").removeClass("content-with-sidebar");
          break;
      }

      switch (sidebar_mode) {
        case 1:
          $("#sidebar").addClass("sidebar-small-hide");
          $("#article").addClass("content-without-sidebar");
          break;
        case 3:
          $("#sidebar").addClass("sidebar-large-show");
          $("#article").addClass("content-with-sidebar");
          break;
      }
    }
  });
</script>

</body>
</html>