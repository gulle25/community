<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
  <!--   <link href="/application/libraries/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link href="/application/libraries/bootstrap/css/bootstrap-grid.min.css" rel="stylesheet">
    <link href="/application/libraries/bootstrap/css/bootstrap-reboot.min.css" rel="stylesheet"> -->
    <link href="/application/libraries/etc/css/simple-sidebar.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" type="text/css" href="/application/libraries/semantic/semantic.min.css">
    <script src="http://code.jquery.com/jquery.js"></script>
    <script src="/application/libraries/semantic/semantic.min.js"></script>
  </head>
  <body>
    <div class="ui sidebar inverted vertical menu">
<a class="item">
    <i class="home icon"></i>
    Home
  </a>
  <a class="item">
    <i class="block layout icon"></i>
    Topics
  </a>
  <a class="item">
    <i class="smile icon"></i>
    Friends
  </a>
  <?php
    foreach ($sidebar as $item) {
      switch ($item['type']) {
        case 'text':
          echo "<i class='" . $item['class'] . "'>" . $item['value'] . "</i>";
          break;  

        case 'text_link':
          echo "<a class='" . $item['class'] . "'>" . $item['value'] . "</a>";
          break;
      }
    }
  ?>
    </div>

      <!-- Page Content -->
    <div id="page-content-wrapper" class="dimmed pusher">
  <?php
    if (ENVIRONMENT == 'development') {
  ?>
    <div class="alert alert-primary" role="alert">[Development]</div>
  <?php
    }
    elseif (ENVIRONMENT == 'testing') {
  ?>
    <div class="alert alert-primary" role="alert">[Testing]</div>
  <?php
    }
  ?>
      <div class="container-fluid">
        <button id="menu" class="ui button">Menu</button>
