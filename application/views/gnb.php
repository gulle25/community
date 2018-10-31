<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

      <div class="container-fluid">

        <!-- GNB Content -->
        <div class="comm_gnb">
      <?php foreach ($gnb as $item): ?>
      <?php switch ($item->type):
          case 'menubar': ?>
            <button id="menu" class="ui button">Menu</button>
      <?php break; ?>
      <?php endswitch; ?>
      <?php endforeach; ?>
        </div>

        <!-- Flash Message -->
      <?php if ($this->session->flashdata('message')): ?>
        <div class="alert alert-primary" role="alert"><?=$this->session->flashdata('message')['message']?></div>
      <?php endif; ?>

        <!-- Page Content -->
        <div class="comm_page">
