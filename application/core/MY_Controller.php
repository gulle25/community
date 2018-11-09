<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
  protected $maintaining = false;
  protected $administrator = false;
  protected $now;
  protected $view;
  protected $user;        // 사용자 캐시 정보
  protected $cafe_type;   // 카페 종류

  function __construct()
  {
    parent::__construct();

    $this->load->config('environment');
    $this->load->driver('cache', ['adapter' => 'apc', 'backup' => 'file']);
    $this->load->library('session');

    $this->lang->load('main','korean');

    $this->view = (object) [
      'sidebar' => [],
      'gnb' => [],
      'content' => (object) []
    ];

    if ($this->session->grade && ($this->session->grade == 'admin' || $this->session->grade == 'operator'))
    {
      // 서비스 관리자
      $this->administrator = true;
    }

    $this->now = date('Y.m.d H:i:s');
    if ($this->config->item('maintenance')->enable)
    {
      if ($this->now >= $this->config->item('maintenance')->begin && $this->now < $this->config->item('maintenance')->end)
      {
        // 서비스 점검 중
        $this->maintaining = true;
        $this->_set_flash_message(lang('maintenance'));
      }
    }
  }

  function _is_logged_in()
  {
    // 사용자 세션 확인
    if (!$this->session->is_logged_in || !$this->session->userno)
    {
      return false;
    }

    // 사용자 캐시 확인
    $cache_key = CACHE_KEY_USER . md5($this->session->userno);
    $this->user = $this->cache->get($cache_key);
    $this->cafe_type = $this->session->cafe_type;
    if (!$this->user)
    {
      // 사용자 캐시가 존재 하지 않으면 DB 에서 정보를 읽는다
      $this->load->database();
      $this->load->model('user_model');
      $user = $this->user_model->get('userno', $this->session->userno, false, false);
      if ($user->errno == My_Model::DB_QUERY_FAIL)
      {
        // DB 읽기 실패, 세션 로그인 해제
        $this->_set_flash_message(lang('query_fail'));
        $this->session->unset_userdata('is_logged_in');
        return false;
      }

      if ($user->errno != My_Model::DB_NO_ERROR)
      {
        // 사용자를 찾을 수 없음, 세션 로그인 해제
        $this->_set_flash_message(lang('unknown_user'));
        $this->session->unset_userdata('is_logged_in');
        return false;
      }

      // 캐시에 저장
      $this->user = $user;
      $this->cache->save($cache_key, $this->user, $this->config->item('cache_exp_user'));
    }

    return true;
  }

  function _exec_available()
  {
    if (!$this->_is_logged_in())
    {
      $this->_try_login();
      return false;
    }

    if ($this->maintaining)
    {
      $this->_set_flash_message(lang('maintenance'));
      if  (!$this->administrator)
      {
        $this->session->unset_userdata('is_logged_in');
        $this->_redirect('/');
        return false;
      }
    }

    return true;
  }

  function _load_view($page)
  {
    $this->load->view('head', $this->view);
    $this->load->view('sidebar', $this->view);
    $this->load->view('gnb', $this->view);
    $this->load->view($page, $this->view);
    $this->load->view('footer', $this->view);
  }

  function _set_flash_message($message, $class = 'info', $popup = false)
  {
    $this->session->set_flashdata('message', ['class' => $class, 'message' => $message, 'popup' => $popup]);
  }

  function _try_login()
  {
    $this->_redirect('/auth/login?returnURL=' . rawurlencode(site_url($this->input->get('returnURL'))));
  }

  function _redirect($url)
  {
    redirect('http://' . $_SERVER['HTTP_HOST'] . '/index.php' . $url);
  }

  function _set_gnb()
  {
    if ($this->session->is_logged_in)
    {
      $this->_set_gnb_home();
    }
    else
    {
      $this->_set_gnb_unsigned();
    }
  }

  function _set_gnb_unsigned()
  {
  }

  function _set_gnb_home()
  {
    $gnb = [
      (object) ['type' => 'menubar', 'value' => 'menu', 'class' => '']
    ];
    $this->view->gnb = array_merge($this->view->gnb, $gnb);
  }

  function _set_sidebar()
  {
    if ($this->session->is_logged_in)
    {
      $this->_set_sidebar_home();
    }
    else
    {
      $this->_set_sidebar_unsigned();
    }
  }

  function _set_sidebar_unsigned()
  {
    // 로그인 하지 않은 상태 에서의 메뉴
    $sidebar = [
      (object) ['type' => 'text_link', 'value' => '서비스 소개', 'class' => '', 'link' => '/index.php/main/introduce', 'feather' => 'book-open'],
      (object) ['type' => 'text_link', 'value' => '회원 가입', 'class' => '', 'link' => '/index.php/auth/signup?mode=begin', 'feather' => 'target'],
      // (object) ['type' => 'text', 'value' => 'text', 'class' => 'item', 'feather' => 'book-open'],
      (object) ['type' => 'text_link', 'value' => '비밀번호 찾기', 'class' => 'fas fa-fw fa-question', 'link' => '/index.php/auth/find_password?mode=begin', 'feather' => 'book-open']
    ];
    $this->view->sidebar = array_merge($this->view->sidebar, $sidebar);
  }

  function _set_sidebar_home()
  {
    // 로그인 상태 에서의 메뉴
    // 카페 방문 시각 순 정렬
    $cafe_cnt_total = count($this->user->cafe_info->list);
    $cafe_bookmark = [];
    $cafe_normal = [];
    $groupped = $cafe_cnt_total > $this->config->item('sidebar_max_direct_cafe_link');
    foreach ($this->user->cafe_info->list as $cafe_idx => $cafe) {
      $idx = 0;
      if ($cafe->bookmark == 1) {
        foreach ($cafe_bookmark as $cafeno) {
          if ($cafe->last_visit > $this->user->cafe_info->list[$cafe_bookmark[$idx]]->last_visit) {
            break;
          }
          $idx++;
        }
        array_splice($cafe_bookmark, $idx, 0, $cafe_idx);
      }
      else {
        foreach ($cafe_normal as $cafeno) {
          if ($cafe->last_visit > $this->user->cafe_info->list[$cafe_normal[$idx]]->last_visit) {
            break;
          }
          $idx++;
        }
        array_splice($cafe_normal, $idx, 0, $cafe_idx);
      }
    }

    // 즐겨 찾는 카페 목록 구성
    if (count($cafe_bookmark)) {
      if ($groupped)
      {
        $this->view->sidebar = array_merge($this->view->sidebar,
          [ (object) ['type' => 'item_group', 'value' => '즐겨 찾는 카페', 'class' => '', 'feather' => 'folder', 'groupid' => 'cafe_bookmark', 'expand' => true]]
        );
      }

      foreach ($cafe_bookmark as $idx) {
        $cafe = $this->user->cafe_info->list[$idx];
        $this->view->sidebar = array_merge($this->view->sidebar,
          [ (object) ['type' => $groupped ? 'group_link' : 'text_link', 'value' => $cafe->name, 'class' => '', 'link' => '/index.php/cafe/visit?cafeno=' . $cafe->cafeno, 'feather' => 'book-open']]
        );
      }
    }

    // 그 외 가입 한 카페 목록 구성
    if (count($cafe_normal)) {
      if ($groupped)
      {
        $this->view->sidebar = array_merge($this->view->sidebar,
          [ (object) ['type' => 'item_group', 'value' => '가입 한 카페', 'class' => '', 'feather' => 'folder', 'groupid' => 'cafe_normal', 'expand' => false]]
        );
      }

      foreach ($cafe_normal as $idx) {
        $cafe = $this->user->cafe_info->list[$idx];
        $this->view->sidebar = array_merge($this->view->sidebar,
          [ (object) ['type' => $groupped ? 'group_link' : 'text_link', 'value' => $cafe->name, 'class' => '', 'link' => '/index.php/cafe/visit?cafeno=' . $cafe->cafeno, 'feather' => 'book-open']]
        );
      }
    }
  }

}
?>