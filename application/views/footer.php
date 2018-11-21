<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
      </article>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

<!--   <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
  <script src="/application/libraries/appstack/dist/js/app.js"></script>
  <script src="/application/libraries/appstack/dist/js/charts.js"></script>
  <script src="/application/libraries/appstack/dist/js/forms.js"></script>
  <script src="/application/libraries/appstack/dist/js/maps.js"></script>
  <script src="/application/libraries/appstack/dist/js/tables.js"></script>
 -->
<script>
  var sidebar_mode = 1;

  $(document).ready(function() {
    window.addEventListener("resize", function() {
      check_sidebar();
    });

    check_sidebar();
});

  function check_sidebar() {
    var width = window.innerWidth ;
    var new_mode;

    if (width < <?=(SIDEBAR_WIDTH/0.3)?>) {
      new_mode = 1;
    } else {
      var show_menu = getCookie("show_menu");
      if (show_menu != "off") {
        new_mode = 3;
      } else {
        new_mode = 4;
      }
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
      case 2:
        $("#sidebar").removeClass("sidebar-small-show");
        $("#article").removeClass("content-disabled");
        break;
      case 3:
        $("#sidebar").removeClass("sidebar-large-show");
        $("#article").removeClass("content-with-sidebar");
        break;
      case 4:
        $("#sidebar").removeClass("sidebar-large-hide");
        $("#article").removeClass("content-without-sidebar");
        break;
    }

    switch (sidebar_mode) {
      case 1:
        $("#sidebar").addClass("sidebar-small-hide");
        $("#article").addClass("content-without-sidebar");
        break;
      case 2:
        $("#sidebar").addClass("sidebar-small-show");
        $("#article").addClass("content-disabled");
        break;
      case 3:
        $("#sidebar").addClass("sidebar-large-show");
        $("#article").addClass("content-with-sidebar");
        break;
      case 4:
        $("#sidebar").addClass("sidebar-large-hide");
        $("#article").addClass("content-without-sidebar");
        break;
    }
  }

  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }

  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  function onToggleMenu() {
    var show_menu = getCookie("show_menu");
    if (sidebar_mode < 3) {
      set_sidebar(2);
    } else {
      set_sidebar(7 - sidebar_mode);
      if (show_menu != "off") {
        setCookie("show_menu", "off", 1);
      } else {
        setCookie("show_menu", "on", 1);
      }
    }
  }
</script>

</body>
</html>