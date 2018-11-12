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

  public function visit($cafeid)
  {
    if (!$this->available) return;

    $this->_set_flash_message('cafe ' . $this->cafe->name . '(' . $this->cafeid . ')에 오셨습니다');

    // 사용자 캐시 갱신
    $cache_key = CACHE_KEY_USER . md5($this->session->userid);
    $this->user->cafe_info->{$this->cafeid}->last_visit = $this->now;
    $this->cache->save($cache_key, $this->user, $this->config->item('cache_exp_user'));

    $this->_redirect('/' . $this->cafe->type  . '/home/' . $this->cafeid);
  }

  function _set_common_gnb()
  {
  }

  function _set_common_sidebar()
  {
    // var_dump($this->cafe->cafe_info);
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
          $this->view->sidebar = array_merge($this->view->sidebar,
            [ (object) ['type' => $menu->groupid ? 'text_link' : 'group_link', 'value' => $menu->name, 'class' => '', 'feather' => 'book-open', 'groupid' => $menu->groupid, 'link' => '/index.php/cafe/board/' . $menu->boardid]]
          );
          break;
      }
    }
  }
  }

  function _home()
  {
    // 카페 방문
}
}
?>