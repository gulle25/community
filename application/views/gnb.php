<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- Top menu -->
  <div class="w3-top w3-bar w3-theme w3-left-align w3-large w3-main" style="" id="myTop">
    <a class="w3-bar-item w3-button w3-left w3-hide-large w3-hover-white w3-large w3-theme-l1" href="javascript:void(0)" onclick="w3_open()"><i class="fa fa-bars"></i></a>
<!--     <a href="#" class="w3-right w3-bar-item w3-button w3-hover-white">Logo</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">About</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Values</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">News</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Contact</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">Clients</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">Partners</a>
 -->  <?php switch ($meta->menu):
    case MENU_LOGOUT: ?>
    <div class="w3-center">Login</div>
  <?php break; ?>
  <?php case MENU_SVC_HOME: ?>
    <div class="w3-center">Service Home</div>
  <?php break; ?>
  <?php case MENU_LIST: ?>
    <div class="w3-center">List</div>
  <?php break; ?>
  <?php case MENU_CONTENT: ?>
    <div class="w3-center">Content</div>
  <?php break; ?>
  <?php endswitch; ?>
  </div>

<!-- Bottom menu -->
  <div class="w3-bottom w3-bar w3-theme w3-left-align w3-large w3-main" style="" id="myBottom">
  <?php switch ($meta->menu):
    case MENU_LOGOUT: ?>
    <a href="#" class="w3-right w3-bar-item w3-button w3-hover-white">Logo</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">About</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Values</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">News</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hover-white">Contact</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">Clients</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-hide-medium w3-hover-white">Partners</a>
  <?php break; ?>
  <?php case MENU_SVC_HOME: ?>
  <?php break; ?>
  <?php case MENU_LIST: ?>
    <a href="#" class="w3-bar-item w3-button w3-hover-white">Write</a>
  <?php break; ?>
  <?php case MENU_CONTENT: ?>
  <?php break; ?>
  <?php endswitch; ?>
  </div>
