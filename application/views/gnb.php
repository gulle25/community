<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

      <article class="my-content-without-sidebar" id="article">
        <div class="gnb bg-white" id="gnb">
          <a class="sidebar-toggle d-flex mr-2">
            <i class="hamburger align-self-center"></i>
          </a>

          <div class="navbar-collapse collapse">
            <ul class="navbar-nav ml-auto">
          <?php if ($this->session->is_logged_in): ?>
              <span class="align-self-left">Logged In</span>
          <?php endif; ?>
              <li class="nav-itemk">
                <a class="" href="/">
                  <i class="align-middle" data-feather="home"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
  

  <?php if (ENVIRONMENT == 'development'): ?>
        <div class="alert alert-primary" role="alert">[Development]</div>
  <?php elseif (ENVIRONMENT == 'testing'): ?>
        <div class="alert alert-primary" role="alert">[Testing]</div>
  <?php endif; ?>


        <!-- GNB Content -->
<!--       <?php foreach ($gnb as $item): ?>
      <?php switch ($item->type):
          case 'menubar': ?>
            <button id="menu" class="ui button">Menu</button>
      <?php break; ?>
      <?php endswitch; ?>
      <?php endforeach; ?>
 -->
