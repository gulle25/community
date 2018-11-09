<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cafe extends My_Controller {
  protected $available = false;

  function __construct()
  {
    parent::__construct();

    if ($this->maintaining) {
      if (!$this->administrator) {
        $this->_redirect('/');
        return;
      }
    }

    if (!$this->_is_logged_in()) {
      $this->_redirect('/');
    }

    $this->available = true;
  }

  public function visit()
  {
    if (!$this->available) return;

    $this->_redirect('/apart/home');
  }

  function _set_common_gnb()
  {
  }

  function _set_common_sidebar()
  {
  }

  function _home()
  {
    // 카페 방문
}
}
?>