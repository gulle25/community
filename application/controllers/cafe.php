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

    // 카페 캐시 읽기
    $this->cafeid = $this->router->uri->segments[3];
    $cache_key = CACHE_KEY_CAFE . md5($this->cafeid);
    $this->cafe = $this->cache->get($cache_key);

    if (!$this->cafe || $this->router->method == 'visit') {
      // 카페 정보 확인
      $this->load->database();
      $this->load->model('cafe_model');
      $this->cafe = $this->cafe_model->get($this->cafeid, $this->router->method == 'visit', $this->session->userid, $this->cafe);
      if ($this->cafe->errno != My_Model::DB_NO_ERROR)
      {
        $this->_set_flash_message(lang($this->cafe->errno == My_Model::DB_QUERY_FAIL ? 'query_fail' : 'unknown_cafe'));
        $this->_redirect('/');
        return;
      }
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

  function _get_content_permission($action)
  {
    $permission = [];
    foreach ($this->cafe->board_info as $boardid => $info) {
      if ($this->_has_permission($boardid, $action)) {
        array_push($permission, $boardid);
      }
    }
    return $permission;
  }

  function _set_common_gnb()
  {
  }

  function _set_common_sidebar()
  {
    // 카페 홈
    $this->view->sidebar = array_merge($this->view->sidebar,
      [ (object) ['type' => 'text_link', 'value' => '카페 전체 글', 'class' => '', 'link' => "/index.php/" . $this->cafe->type . "/home/$this->cafeid", 'feather' => 'book-open']]
    );

    // 게시판 메뉴
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
            if ($this->_has_permission($menu->boardid, ACTION_LIST)) {
              $this->view->sidebar = array_merge($this->view->sidebar,
                [ (object) ['type' => $menu->groupid == '' ? 'text_link' : 'group_link', 'value' => $menu->name, 'class' => '', 'feather' => 'book-open', 'groupid' => $menu->groupid, 'link' => '/index.php/' . $this->cafe->type . '/list/' . $this->cafe->cafeid . '/' . $menu->boardid]]
              );
            }
            break;
        }
      }
    }

    // 내 정보 메뉴
    $this->view->sidebar = array_merge($this->view->sidebar,
      [ (object) ['type' => 'item_group', 'value' => '내 정보', 'class' => '', 'feather' => 'folder', 'groupid' => 'my_info', 'expand' => false],
        (object) ['type' => 'group_link', 'value' => '정보 조회', 'class' => '', 'feather' => 'book-open', 'groupid' => 'my_info', 'link' => "index.php/myinfo/cafe_info"],
        (object) ['type' => 'group_link', 'value' => '실명 인증', 'class' => '', 'feather' => 'book-open', 'groupid' => 'my_info', 'link' => "index.php/myinfo/auth_name"],
        (object) ['type' => 'group_link', 'value' => '주소 인증', 'class' => '', 'feather' => 'book-open', 'groupid' => 'my_info', 'link' => "index.php/myinfo/auth_addr"],
        (object) ['type' => 'group_link', 'value' => '휴대폰 인증', 'class' => '', 'feather' => 'book-open', 'groupid' => 'my_info', 'link' => "index.php/myinfo/auth_phone"],
        (object) ['type' => 'group_end' ]
    ]);

    // 카페 관리 메뉴
    if (array_key_exists($this->cafeid, $this->user->cafe_info)) {
      if (in_array(ROLE_CAFE_ADMIN, $this->user->cafe_info->{$this->cafeid}->role) ||
        in_array(ROLE_CAFE_OPERATOR, $this->user->cafe_info->{$this->cafeid}->role)) {
        $this->view->sidebar = array_merge($this->view->sidebar,
          [ (object) ['type' => 'item_group', 'value' => '카페 관리', 'class' => '', 'feather' => 'folder', 'groupid' => 'admin_cafe', 'expand' => false],
            (object) ['type' => 'group_link', 'value' => '게시판 관리', 'class' => '', 'feather' => 'book-open', 'groupid' => 'admin_cafe', 'link' => "index.php/admin/board/$this->cafeid"],
            (object) ['type' => 'group_link', 'value' => '회원 관리', 'class' => '', 'feather' => 'book-open', 'groupid' => 'admin_cafe', 'link' => "index.php/admin/member/$this->cafeid"],
            (object) ['type' => 'group_end' ]
        ]);
      }
    }

    // 로그아웃
    $this->view->sidebar = array_merge($this->view->sidebar,
      [ (object) ['type' => 'text_link', 'value' => '로그아웃', 'class' => '', 'link' => "/index.php/auth/logout", 'feather' => 'book-open']]
    );
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

  function _api_content_list($cafeid, $boardid, $last_ownerid, $last_sequence, $srch_type, $srch_str)
  {
    $result_count = 0;
    $result = [];
    $end_of_list = ['', 0, 0, 0];
    $global_seq = (MAX_CONTENTNO - $last_ownerid) * MAX_COMMENT + $last_sequence;
    $board_list = [];
    $boardid == ALL_BOARD ? $this->_get_content_permission(ACTION_LIST) : $boardid;

    if ($boardid == ALL_BOARD) {
      $board_list = $this->_get_content_permission(ACTION_LIST);
      if (!count($board_list)) {
        // 목록 읽기 가능한 게시판이 없는 경우
        array_push($result, $end_of_list);
        return $result;
      }
    }

    // 캐시 확인
    if (!array_key_exists('list_mast', $this->cafe)) {
      // content list 캐시 생성
      $this->cafe->list_mast = (object) [];
      $this->cafe->content_list = (object) [];
      $this->cafe->content_list->{ALL_BOARD} = [];

      foreach ($this->cafe->board_info as $bid => $info) {
        $this->cafe->content_list->{$bid} = [];
      }

      // 캐시 갱신
      $cache_key = CACHE_KEY_CAFE . md5($this->cafeid);
      $this->cache->save($cache_key, $this->cafe, $this->config->item('cache_exp_cafe'));

      if ($last_ownerid != MAX_CONTENTNO || $last_sequence = 0) {
        // 처음 목록을 요청한 상태가 아닌 경우
        array_push($result, $end_of_list);
        return $result;
      }
    }

    // 리스트 캐시에서 읽기
    foreach ($this->cafe->content_list->{$boardid} as $gseq) {
      if ($gseq == MAX_CONTENTNO * MAX_COMMENT + MAX_COMMENT - 1) {
        // 게시물 전체 검색 완료
        array_push($result, $end_of_list);
        return $result;
      }

      if ($gseq > $global_seq) {
        $content = $this->cafe->list_mast->{$gseq};
        if ($boardid == ALL_BOARD) {
          if (!in_array($content->bid, $board_list)) {
            // 목록 접근 권한이 없음
            continue;
          }
        }

        // 리스트에 포함 할 게시물 찾음
        // $content->title = $content->title . '_cache_' . $content->cno;
        array_push($result, [$content->bid, $content->cno, $content->ono, $content->seq, $content->nick, $content->tgt_nick, $content->title, $content->del, $content->edit, $content->view, $content->cmt, $content->info]);
        $global_seq = $gseq;
        if (++$result_count >= LIST_FETCH_SIZE) {
          // 리스트를 모두 채웠으므로 return
          return $result;
        }
      }
    }

    // DB 에서 리스트 가져 오기
    $this->load->database();
    $this->load->model('cafe_model');
    $cache_key = CACHE_KEY_CAFE . md5($this->cafeid);
    $board_sql = $boardid == ALL_BOARD ? '' : 'AND boardid = ?';
    $db_fetch = false;

    while ($result_count < LIST_FETCH_SIZE) {
      $sql = "SELECT boardid bid, globalno gno, contentno cno, ownerno ono, sequence seq, userid uid, nickname nick, title, deleted del, reg_time reg, edit_time edit, view_cnt view, comment_cnt cmt, info, target_nickname tgt_nick FROM content WHERE cafeid = ? $board_sql AND (" . MAX_CONTENTNO . " - ownerno) * " . MAX_COMMENT. " + sequence > ? ORDER BY ownerno DESC, sequence LIMIT ?";

      if ($boardid == ALL_BOARD) {
        $list = $this->cafe_model->call_multi_row($sql, [$this->cafeid, $global_seq, LIST_FETCH_SIZE]);
      } else {
        $list = $this->cafe_model->call_multi_row($sql, [$this->cafeid, $boardid, $global_seq, LIST_FETCH_SIZE]);
      }

      if (count($list)) {
        $db_fetch = true;
        foreach ($list as $info) {
          $global_seq = (MAX_CONTENTNO - $info->ono) * MAX_COMMENT + $info->seq;
          if (!in_array($global_seq, $this->cafe->content_list->{$boardid})) {
            // 캐시에 global sequence 및 리스트 정보 추가
            array_push($this->cafe->content_list->{$boardid}, $global_seq);
            if (!array_key_exists($global_seq, $this->cafe->list_mast)) {
              $this->cafe->list_mast->{$global_seq} = $info;
            }

            if ($boardid == ALL_BOARD) {
              if (!in_array($info->bid, $board_list)) {
                // 목록 접근 권한이 없음
                continue;
              }
            }

            array_push($result, [$info->bid, $info->cno, $info->ono, $info->seq, $info->nick, $info->tgt_nick, $info->title, $info->del, $info->edit, $info->view, $info->cmt, $info->info]);
            $result_count++;
          }
        }

        if (count($list) < LIST_FETCH_SIZE) {
          // 리스트를 모두 읽음
          array_push($this->cafe->content_list->{$boardid}, MAX_CONTENTNO * MAX_COMMENT + MAX_COMMENT - 1);
          array_push($result, $end_of_list);
          break;
        }
      } else {
        // 더 이상 목록이 없음
        array_push($this->cafe->content_list->{$boardid}, MAX_CONTENTNO * MAX_COMMENT + MAX_COMMENT - 1);
        array_push($result, $end_of_list);
        break;
      }
    }

    if ($db_fetch) {
      $this->cache->save($cache_key, $this->cafe, $this->config->item('cache_exp_cafe'));
    }
    return $result;
  }
}
?>