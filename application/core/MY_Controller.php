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
        $this->lang->load('main','korean');

        $this->view = (object) [
            'sidebar' => [],
            'gnb' => [],
            'content' => []
        ];

        // $this->view = { a = 'gg' };
        // $this->view->sidebar = array();
        // $this->view->gnb = array();
        // $this->view->flash_msg = array();
        // $this->view->content = array();

    }

    function _load_view($page)
    {
        $this->load->view('head', $this->view);
        $this->load->view('sidebar', $this->view);
        $this->load->view('gnb', $this->view);
        $this->load->view($page, $this->view);
        $this->load->view('footer', $this->view);
    }
}
?>