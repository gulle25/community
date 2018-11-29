<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Sidebar -->
<style>
.w3-sidebar {
  z-index: 3;
  width: 250px;
  top: 0;
  bottom: 0;
  height: inherit;
}
</style>

<nav class="w3-sidebar w3-bar-block w3-collapse w3-theme-<?=SIDEBAR_THEME?> w3-animate-left" id="mySidebar">
    <a class="w3-bar-item w3-button w3-hover-black" href="/">Home</a>
  <?php $item_cnt = 0; ?>
  <?php foreach ($sidebar as $item): ?>
  <?php $item_cnt++; ?>
  <?php switch ($item->type):
    case 'text': ?>
      <a class="w3-bar-item w3-button w3-hover-<?=SIDEBAR_HOVER?>" href="/"><?=$item->value?></a>
  <?php break; ?>
  <?php case 'text_link': ?>
      <a class="w3-bar-item w3-button w3-theme-<?=SIDEBAR_THEME?> w3-hover-<?=SIDEBAR_HOVER?>" href="<?=$item->link?>"><?=$item->value?></a>
  <?php break; ?>
  <?php case 'item_group': ?>
      <button class="w3-button w3-block w3-hover-<?=SIDEBAR_HOVER?> w3-left-align" onclick="onClickGroup('<?=$item->groupid?>')"><?=$item->value?><i class="w3-right fa fa-caret-down"></i></button>
      <div class="w3-hide" id="<?=$item->groupid?>">
  <?php break; ?>
  <?php case 'group_link': ?>
        <a class="w3-bar-item w3-button w3-theme-<?=SIDEBAR_THEME?> w3-hover-<?=SIDEBAR_HOVER?>" href="<?=$item->link?>" style="padding-left:30px"><?=$item->value?></a>
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
<div class="w3-main" style="margin-left:250px;margin-top:43px" id="myMain">

<?php if (ENVIRONMENT == 'development'): ?>
  <div class="w3-container w3-cyan w3-round">[Development]</div>
<?php elseif (ENVIRONMENT == 'testing'): ?>
  <div class="w3-container w3-blue w3-round">[Testing]</div>
<?php endif; ?>

<script>
function onClickGroup(id) {
  var div = $("#"+id);
  div.toggleClass("w3-hide");
  div.prev().toggleClass("w3-theme-l2");
}
</script>