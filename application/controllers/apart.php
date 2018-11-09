<?php
include ('cafe.php');

class Apart extends Cafe {
  function __construct()
  {
    parent::__construct();
  }

  function _set_gnb()
  {
    $this->_set_common_gnb();
  }

  function _set_sidebar()
  {
    $this->_set_common_sidebar();
  }

  public function home()
  {
    if (!$this->available) return;

    // 카페 방문
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_home();
    $this->_load_view('cafe_home');
  }
}
?>