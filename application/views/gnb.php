<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

      <article class="content-without-sidebar" id="article">
        <div class="gnb bg-white" id="gnb">
          <a href="javascript:onToggleMenu()">
            <i class="fas fa-bars"></i>
          </a>

          <div class="">
            <ul class="">
          <?php if ($this->session->is_logged_in): ?>
              <span class="">Logged In</span>
          <?php endif; ?>
              <li class="nav-item">
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

