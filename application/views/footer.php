<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

  <footer id="myFooter">
    <div class="w3-container w3-theme-l2 w3-padding-10">
      <h4>Footer</h4>
    </div>
  </footer>

<!-- END MAIN -->
</div>

<script>
// Get the Sidebar
var mySidebar = document.getElementById("mySidebar");

// Get the DIV with overlay effect
var overlayBg = document.getElementById("myOverlay");

// Toggle between showing and hiding the sidebar, and add overlay effect
function w3_open() {
    if (mySidebar.style.display === 'block') {
        mySidebar.style.display = 'none';
        overlayBg.style.display = "none";
    } else {
        mySidebar.style.display = 'block';
        overlayBg.style.display = "block";
        // mySidebar.style.padding = "0 250px";
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
    // mySidebar.style.padding = "0";
}

var small_width = false;
$(window).resize(function() {
  var ww = $(window).width();
  document.getElementById('label').innerHTML = ww;
  // $('#label').value(ww);
  var main =   document.getElementById('myMain');
  var top =   document.getElementById('myTop');
  var bottom =   document.getElementById('myBottom');
  if (ww <= 992 && !small_width) {
    // main.style.margin = "0";
    top.style.paddingRight = "0";
    small_width = true;
  } else if (ww > 992 && small_width)
    // main.style.margin = "0 250px 0 0";
    top.style.paddingRight = "250px";
    small_width = false;
  }
);
</script>

</body>
</html>