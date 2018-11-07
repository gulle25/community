<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <div class="wrapper">
    <div class="d-flex">
      <nav class="sidebar sidebar-sticky">
        <div class="sidebar-content">
          <a class="sidebar-brand" href="/">
            <i class="align-middle" data-feather="box"></i>
            <span class="align-middle">AppStack</span>
          </a>
          <ul class="sidebar-nav">
            <li class="sidebar-header">
              Community
            </li>

        <?php foreach ($sidebar as $item): ?>
        <?php switch ($item->type):
          case 'text': ?>
            <li class="sidebar-item active">
              <a href="#" class="sidebar-link">
                <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?=$item->value?></span>
                <span class="sidebar-badge badge badge-primary">6</span>
              </a>
            </li>
        <?php break; ?>
        <?php case 'text_link': ?>
            <li class="sidebar-item active">
              <a href="#" class="sidebar-link">
                <i class="align-middle" data-feather="sliders"></i> <span class="align-middle"><?=$item->value?></span>
                <span class="sidebar-badge badge badge-primary">6</span>
              </a>
            </li>
        <?php break; ?>
        <?php endswitch; ?>
        <?php endforeach; ?>

          </ul>
        </div>
      </nav>
