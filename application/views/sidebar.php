<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Sidebar -->
<nav class="w3-sidebar w3-bar-block w3-collapse w3-large w3-theme-l5 w3-animate-left" id="mySidebar">
    <a class="w3-bar-item w3-button w3-hover-black" href="/">Link</a>
  <?php $item_cnt = 0; ?>
  <?php foreach ($sidebar as $item): ?>
  <?php $item_cnt++; ?>
  <?php switch ($item->type):
    case 'text': ?>
      <a class="w3-bar-item w3-button w3-hover-black" href="/"><?=$item->value?></a>
  <?php break; ?>
  <?php case 'text_link': ?>
      <a class="w3-bar-item w3-button w3-hover-black" href="<?=$item->link?>"><?=$item->value?></a>
  <?php break; ?>
  <?php case 'item_group': ?>
      <button class="w3-button w3-block w3-left-align" onclick="myAccFunc('<?=$item->groupid?>')"><?=$item->value?><i class="w3-right fa fa-caret-down"></i></button>
      <div class="w3-hide w3-white w3-card" id="<?=$item->groupid?>">
  <?php break; ?>
  <?php case 'group_link': ?>
        <a class="w3-bar-item w3-button" href="<?=$item->link?>"><?=$item->value?></a>
  <?php break; ?>
  <?php case 'group_end': ?>
      </div>
  <?php break; ?>
  <?php endswitch; ?>
  <?php endforeach; ?>

</nav>

<!-- Overlay effect when opening sidebar on small screens -->
<div class="w3-overlay w3-hide-large" onclick="w3_close()" style="cursor:pointer" title="close side menu" id="myOverlay"></div>

<!-- Main content: shift it to the right by 250 pixels when the sidebar is visible -->
<div class="w3-main" style="margin-left:250px">

<script>
function myAccFunc(id) {
    var x = document.getElementById(id);
    if (x.className.indexOf("w3-show") == -1) {
        x.className += " w3-show";
        x.previousElementSibling.className += " w3-green";
    } else { 
        x.className = x.className.replace(" w3-show", "");
        x.previousElementSibling.className = 
        x.previousElementSibling.className.replace(" w3-green", "");
    }
}
</script>