<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
    protected $view;
    protected $user;

    function __construct()
    {
        parent::__construct();

        $this->load->config('environment');
        $this->load->config('community');
        $this->load->driver('cache', ['adapter' => 'apc', 'backup' => 'file']);
        $this->load->library('session');

        $this->lang->load('main','korean');

        $this->view = (object) [
            'sidebar' => [],
            'gnb' => [],
            'content' => (object) []
        ];
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

    function _redirect($url)
    {
      redirect('http://' . $_SERVER['HTTP_HOST'] . '/index.php' . $url);
    }

    function _set_gnb_unsigned()
    {
        // $gnb = [
        //   (object) ['type' => 'menubar', 'value' => 'menu', 'class' => 'item']
        // ];
        // $this->view->gnb = array_merge($this->view->gnb, $gnb);
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