<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Service maintenance
$config['maintenance'] = (object) [
  'enable' => false,
  'comment' => '서비스 점검중 입니다.',
  'begin' => '2018.11.10 00:00:00',
  'end' => '2018.12.01 00:00:00'
  ];

// Cache version
define('CACHE_KEY_USER', 'user_v2_');

$config['cache_exp_user'] = 60;
$config['sidebar_max_direct_cafe_link'] = 5;








?>
