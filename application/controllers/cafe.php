<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cafe extends My_Controller {
  protected $available = false;
  protected $cafeid = '';
  protected $cafe;

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
      return;
    }

    if (count($this->router->uri->segments) < 3) {
      $this->_redirect('/');
      return;
    }

    // 카페 정보 확인
    $this->load->database();
    $this->load->model('cafe_model');
    $this->cafeid = $this->router->uri->segments[3];
    $this->cafe = $this->cafe_model->get($this->cafeid, $this->router->method == 'visit', $this->session->userid);
    if ($this->cafe->errno != My_Model::DB_NO_ERROR)
    {
      $this->_set_flash_message(lang($this->cafe->errno == My_Model::DB_QUERY_FAIL ? 'query_fail' : 'unknown_cafe'));
      $this->_redirect('/');
      return;
    }

    $this->available = true;
  }

  function _has_permission($boardid, $action)
  {
    if (!$this->available) return false;

    // 잘못된 boardid 혹은 action 이면 false
    if (!array_key_exists($boardid, $this->cafe->board_info)) return false;

    // 잘못된 action 이면 false
    if (!array_key_exists($action, $this->cafe->board_info->{$boardid}->permission)) return false;

    // 서비스 운영자 이면 true
    if ($this->user->grade == 'admin' || $this->user->grade == 'operator') return true;

    $permission = $this->cafe->board_info->{$boardid}->permission->{$action};

    // 모든 사용자에게 허용 되어 있으면 true
    if (in_array(PERMISSION_ALL, $permission)) return true;

    // 카페 회원이 아니면 false
    if (!array_key_exists($this->cafeid, $this->user->cafe_info)) return false;

    // 카페 회원 모두에게 허용 되어 있으면 true
    if (in_array(PERMISSION_MEMBER, $permission)) return true;

    // 사용자 역할 조회
    foreach ($this->user->cafe_info->role as $roleno) {
      // 카페 운영자 이면 true
      if ($roleno < ROLE_CAFE_FIXED) return true;

      // 사용자 역할이 게시판 권한과 일치 하면 true
      if (in_array($roleno, $permission)) return true;
    }

    return false;
  }

  function _set_common_gnb()
  {
  }

  function _set_common_sidebar()
  {
    if (isset($this->cafe->cafe_info->menu)) {
    foreach ($this->cafe->cafe_info->menu as $menu) {
      switch ($menu->type) {
        case 'group':
          $this->view->sidebar = array_merge($this->view->sidebar,
            [ (object) ['type' => 'item_group', 'value' => $menu->name, 'class' => '', 'feather' => 'folder', 'groupid' => $menu->groupid, 'expand' => false]]
          );
          break;
        case 'group_end':
          $this->view->sidebar = array_merge($this->view->sidebar,
            [ (object) ['type' => 'group_end' ]]
          );
          break;
        case 'board':
          if ($this->_has_permission($menu->boardid, 'list')) {
            $this->view->sidebar = array_merge($this->view->sidebar,
              [ (object) ['type' => $menu->groupid ? 'text_link' : 'group_link', 'value' => $menu->name, 'class' => '', 'feather' => 'book-open', 'groupid' => $menu->groupid, 'link' => '/index.php/' . $this->cafe->type . '/list/' . $this->cafe->cafeid . '/' . $menu->boardid]]
            );
          }
          break;
      }
    }
  }
  }

  public function visit($cafeid)
  {
    if (!$this->available) return;

    $this->_set_flash_message('cafe ' . $this->cafe->name . '(' . $this->cafeid . ')에 오셨습니다');

    // 사용자 캐시 갱신
    $cache_key = CACHE_KEY_USER . md5($this->session->userid);
    if (array_key_exists($this->cafeid, $this->user->cafe_info)) {
      // 카페 회원인 경우 방문 정보 갱신
      $this->user->cafe_info->{$this->cafeid}->last_visit = $this->now;
      $this->cache->save($cache_key, $this->user, $this->config->item('cache_exp_user'));
    }

    $this->_redirect('/' . $this->cafe->type  . '/home/' . $this->cafeid);
  }

  function _home()
  {
    // 카페 방문
  }

  function _api_content_list($cafeid, $boardid, $last_ownerid = MAX_CONTENTNO, $last_sequence = 0)
  {
    $result_count = 0;
    $result = [];
    $board_permission = [];
    $global_seq = $last_ownerid * MAX_COMMENT + $last_sequence;
    $list_index = 0;
    $end_of_list = false;

    // 캐시 확인
    if (!array_key_exists('list_mast', $this->cafe)) {
      // content list 캐시 생성
      $this->cafe->list_mast = (object) [];
      $this->cafe->board_seq = (object) [];

      $this->cafe->board_seq->{ALL_BOARD} = [];
      foreach ($this->cafe->board_info as $bid => $info) {
        $this->cafe->board_seq->{$bid} = [];
      }

      // 캐시 갱신
      $cache_key = CACHE_KEY_CAFE . md5($this->cafeid);
      $this->cache->save($cache_key, $this->cafe, $this->config->item('cache_exp_cafe'));

      if ($last_ownerid != MAX_CONTENTNO || $last_sequence = 0) {
        // 처음 목록을 요청한 상태가 아닌 경우 무응답
        return null;
      }
    }

    // 리스트 캐시에서 읽기
    foreach ($this->cafe->board_seq->{$boardid} as $gseq) {
      $list_index++;

      if ($gseq == 0) {
        // 게시물 전체 검색 완료
        $end_of_list = true;
        break;
      }

      if ($gseq > $global_seq) {
        $content = $this->cafe->list_mast->{$gseq};
        if ($boardid == ALL_BOARD) {
          $bid = $content->boardid;
          if (!in_array($bid, $board_permission)) {
            // 게시판 목록 접근 권한 검사
            if ($this->_has_permission($bid, 'list')) {
              array_push($board_permission, $bid);
            } else {
              // 목록 접근 권한이 없음
              continue;
            }
          }
        }

        // 리스트에 포함 할 게시물 찾음
        array_push($result, $content);
        $global_seq = $gseq;
        if (++$result_count >= LIST_FETCH_SIZE) {
          // 리스트를 채웠으므로 return
          return $result;
        }
      }
    }

    if ($result_count < LIST_FETCH_SIZE) {
      $this->load->database();
      $this->load->model('cafe_model');
      $sql = 'SELECT boardid, globalno, contentno, ownerno, sequence, userid, nickname, title, deleted, reg_time, edit_time, view_cnt, comment_cnt, info, target_nickname FROM content WHERE cafeid = ? AND (99999999 - ownerno) * 100000 + sequence > ((99999999 - ?) * 100000) + ? ORDER BY ownerno DESC, sequence LIMIT ?';

      // $list = $this->cafe_model->board_list($cafeid, ALL_BOARD, $last_ownerid, $last_sequence, 5, 'none', '');
      $list = $this->cafe_model->call_multi_row($sql, [$this->cafeid, $last_ownerid, $last_sequence, LIST_FETCH_SIZE - $result_count]);
      echo json_encode($list);
      // if ($list[0]->errno != My_Model::DB_NO_ERROR)
      // {
      //   return null;
      // }
    }

    // $this->view->list = [];
    // $ono = $last_ownerid;
    // $seq = $last_sequence;
    // $cno = $ono + $seq;
    // $result = [];

    // for ($i = 0; $i < 100; $i++) {
    //   $cno--;
    //   if (++$seq >= 3) {
    //     $ono = $cno;
    //     $seq = 0;
    //   }
    //   array_push($result, (object) ['cno' => $cno, 'ono' => $ono, 'seq' => $seq, 'title' => 'tit\'",+=_\\le_' . $cno]);
    // }

    return $result;
  }
}
?>