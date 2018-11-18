<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Service maintenance
$config['maintenance'] = (object) [
  'enable' => false,
  'comment' => '서비스 점검중 입니다.',
  'begin' => '2018.11.10 00:00:00',
  'end' => '2019.12.31 00:00:00'
  ];

// Cache version
define('CACHE_KEY_USER', 'user_v2_');
define('CACHE_KEY_CAFE', 'cafe_v1_');

// Constants
define('BASE36_LEN_USERID', 6);
define('BASE36_LEN_CAFEID', 5);
define('BASE36_LEN_BOARDID', 3);

define('SCROLL_BUFFER', 1000);
define('LIST_FETCH_SIZE', 50);
define('ALL_BOARD', 'total');
define('ACTION_LIST', 'list');
define('ACTION_READ', 'read');
define('ACTION_WRITE', 'write');
define('ACTION_COMMENT', 'comment');

define('PERMISSION_ALL', 999);
define('PERMISSION_MEMBER', 998);

define('GRADE_ADMIN', 'admin');
define('GRADE_OPERATOR', 'operator');

define('ROLE_CAFE_ADMIN', 201);
define('ROLE_CAFE_OPERATOR', 202);
define('ROLE_CAFE_FIXED', 301);
define('ROLE_CAFE_CUSTOM', 401);

define('MAX_CONTENTNO', 99999999);
define('MAX_COMMENT', 100000);



$config['cache_exp_user'] = 600000;
$config['cache_exp_cafe'] = 600000;
$config['sidebar_max_direct_cafe_link'] = 5;








?>
