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
    $this->view->info = (object) [ 'cafeid' => $cafeid, 'boardid' => ALL_BOARD ];
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('list');
    // echo json_encode($this->cafe->cafe_info->menu);
  }

  public function list($cafeid, $boardid)
  {
    if (!$this->_has_permission($boardid, ACTION_LIST)) {
      $this->_redirect('/');
      return;
    }

    $this->view->info = (object) [ 'cafeid' => $cafeid, 'boardid' => $boardid ];
    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('list');
  }

  public function api_content_list($cafeid, $boardid, $last_ownerid, $last_sequence, $srch_type, $srch_str)
  {
    if ($boardid != ALL_BOARD && !$this->_has_permission($boardid, ACTION_LIST)) return;

    // 게시판 목록
    $result = $this->_api_content_list($cafeid, $boardid, $last_ownerid, $last_sequence, $srch_type, $srch_str);
    echo json_encode($result);
  }
}
?>