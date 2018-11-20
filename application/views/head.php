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
  <link href="/application/libraries/appstack/dist/css/app.css" rel="stylesheet">
</head>
<body>
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
    }
    .content {
      padding-bottom: 1000px;
    }
    .bottom {
      bottom:0;
      position: sticky;
      background-color: red;
    }
  </style>
