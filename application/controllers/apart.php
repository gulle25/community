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

  public function home($cafeid)
  {
    if (!$this->available) return;

    // 카페 방문
    $this->_home();
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('cafe_home');
  }

  public function list($cafeid, $boardid)
  {
    if (!$this->available) return;

    // 게시판 목록
    $this->_list($boardid);

    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('list');
  }

  public function get_list($cafeid, $boardid, $last_ownerid, $last_sequence)
  {
    if (!$this->available) return;

    // 게시판 목록
    // $this->_list($boardid);

    $this->load->view('get_list', $this->view);
  }
}
?>