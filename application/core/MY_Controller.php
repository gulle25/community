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
}
?>