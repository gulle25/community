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
    }
}

// Close the sidebar with the close button
function w3_close() {
    mySidebar.style.display = "none";
    overlayBg.style.display = "none";
}

$(document).ready(function() {
  adjust_gnb();
});

$(window).resize(function() {
  adjust_gnb();
});

function adjust_gnb() {
  var ww = $(window).width();
  var top =   document.getElementById('myTop');
  var bottom =   document.getElementById('myBottom');

  if (ww <= 992) {
    top.style.paddingRight = "0";
    bottom.style.paddingRight = "0";
  } else if (ww > 992) {
    top.style.paddingRight = "250px";
    bottom.style.paddingRight = "250px";
  }
}
</script>

</body>
</html>