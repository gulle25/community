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

  function _has_permission($boardid, $action)
  {
    return parent::_has_permission($boardid, $action);
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
    if (!$this->_has_permission($boardid, 'list')) {
      $this->_redirect('/');
      return;
    }

    // $test1 = [ 4, 5, 43, 34];
    // array_splice($test1, 1, 0, [45, 3423]);
    // next($test1);
    // var_dump($test1);
    // echo in_array(4, $test1) == FALSE ? 'F' : 'T';

    $this->view->info = (object) [ 'cafeid' => $cafeid, 'boardid' => $boardid ];
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('list');
  }

  public function api_content_list($cafeid, $boardid, $last_ownerid, $last_sequence)
  {
    if (!$this->_has_permission($boardid, 'list')) return;

    // 게시판 목록
    $result = $this->_api_content_list($cafeid, $boardid, $last_ownerid, $last_sequence);
    echo json_encode($result);
  }
}
?>