<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cafe extends My_Controller {
  protected $available = false;
  protected $cafeno = 0;
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
    $this->cafeno = $this->router->uri->segments[3];
    $this->cafe = $this->cafe_model->get($this->cafeno, $this->router->method == 'visit', $this->session->userno);
    if ($this->cafe->errno != My_Model::DB_NO_ERROR)
    {
      $this->_set_flash_message(lang($this->cafe->errno == My_Model::DB_QUERY_FAIL ? 'query_fail' : 'unknown_cafe'));
      $this->_redirect('/');
      return;
    }

    $this->available = true;
  }

  public function visit($cafeno)
  {
    if (!$this->available) return;

    $this->_set_flash_message('cafe ' . $this->cafeno . ' 에 오셨습니다');

    // 사용자 캐시 갱신
    $cache_key = CACHE_KEY_USER . md5($this->session->userno);
    $this->user->cafe_info->{$this->cafeno}->last_visit = $this->now;
    $this->cache->save($cache_key, $this->user, $this->config->item('cache_exp_user'));

    $this->_redirect('/' . $this->cafe->type  . '/home/' . $this->cafeno);
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