<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends My_Controller {
  function __construct()
  {
    parent::__construct();
  }

  public function introduce()
  {
    // 서비스 소개 페이지
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('introduce', MENU_LOGOUT);
  }
}
?>