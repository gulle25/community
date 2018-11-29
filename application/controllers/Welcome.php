<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends My_Controller {
  public function index()
  {
    if (!$this->_is_logged_in())
    {
      $this->_try_login();
      return;
    }

    if ($this->maintaining)
    {
      $this->_set_flash_message(lang('maintenance'));
      if  (!$this->administrator)
      {
       $this->session->unset_userdata('is_logged_in');
       $this->_try_login();
       return;
      }
    }

    // 로그인 되어진 메인 페이지
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('main', MENU_LOGOUT);
  }
}
?>