<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
    protected $maintaining = false;
    protected $administrator = false;
    protected $now;
    protected $view;
    protected $user_cache;  // 사용자 캐시 정보
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

        if ($this->session->user_grade && ($this->session->user_grade == 'admin' || $this->session->user_grade == 'operator'))
        {
            // 서비스 관리자
            $this->administrator = true;
        }

        $now = date('Y.m.d H:i:s');
        if ($this->config->item('maintenance')->enable)
        {
            if ($now >= $this->config->item('maintenance')->begin && $now < $this->config->item('maintenance')->end)
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
        $this->user_cache = $this->cache->get($cache_key);
        $this->cafe_type = $this->session->cafe_type;
        if (!$this->user_cache)
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
          $this->user_cache = $user;
          $this->cache->save($cache_key, $this->user_cache, $this->config->item('cache_exp_user'));
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

    function _set_gnb_unsigned()
    {
    }

    function _set_gnb_home()
    {
        $gnb = [
          (object) ['type' => 'menubar', 'value' => 'menu', 'class' => 'item']
        ];
        $this->view->gnb = array_merge($this->view->gnb, $gnb);
    }

    function _set_gnb_cafe()
    {
        $gnb = [
          (object) ['type' => 'menubar', 'value' => 'menu', 'class' => 'item']
        ];
        $this->view->gnb = array_merge($this->view->gnb, $gnb);
    }

    function _set_sidebar_unsigned()
    {
        $sidebar = [
          (object) ['type' => 'text_link', 'value' => lang('board'), 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board2', 'class' => 'item'],
          (object) ['type' => 'text', 'value' => 'text', 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board3', 'class' => 'item']
        ];
        $this->view->sidebar = array_merge($this->view->sidebar, $sidebar);
    }

    function _set_sidebar_home()
    {
        $sidebar = [
          (object) ['type' => 'text_link', 'value' => lang('board'), 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board2', 'class' => 'item'],
          (object) ['type' => 'text', 'value' => 'text', 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board3', 'class' => 'item']
        ];
        $this->view->sidebar = array_merge($this->view->sidebar, $sidebar);
    }

    function _set_sidebar_cafe()
    {
        $sidebar = [
          (object) ['type' => 'text_link', 'value' => lang('board'), 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board2', 'class' => 'item'],
          (object) ['type' => 'text', 'value' => 'text', 'class' => 'item'],
          (object) ['type' => 'text_link', 'value' => 'board3', 'class' => 'item']
        ];
        $this->view->sidebar = array_merge($this->view->sidebar, $sidebar);
    }

}
?>