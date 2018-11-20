<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="kr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Responsive Bootstrap 4 Admin Template">
  <meta name="author" content="Bootlab">
  <title>Responsive Admin Template</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<body>
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

  <style>
    .sidebar-small-show {
      left: 0;
      top: 0;
      bottom: 0;
      height: 100%;
      width: 180px;
      background: #ccc;
      position: fixed;
      z-index: 100;
    }
    .sidebar-small-hide {
      left: 0;
      top: 0;
      bottom: 0;
      height: 100%;
      width: 180px;
      background: #ccc;
      position: fixed;
      display: none;
      z-index: 100;
    }
    .sidebar-large-show {
      left: 0;
      top: 0;
      bottom: 0;
      min-height: 100%;
      width: 180px;
      background: #ccc;
      position: absolute;
      z-index: 100;
    }
    .sidebar-large-hide {
      left: 0;
      top: 0;
      bottom: 0;
      min-height: 100%;
      width: 180px;
      background: #ccc;
      position: absolute;
      z-index: 100;
      display: none;
    }
    .content-with-sidebar {
        margin-left: 180px;
        background-color: #f1f1f1;
    }
    .content-without-sidebar {
        background-color: #f1f1f1;
    }
    .gnb {
      top:0;
      position: sticky;
      background-color: red;
      z-index: 100;
    }
    .content {
      padding-bottom: 1000px;
    }
    .bottom {
      bottom:0;
      position: sticky;
      background-color: red;
      z-index: 100;
    }
  </style>
