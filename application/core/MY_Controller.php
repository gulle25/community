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
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        $this->view = array(
            'gnb' => array(),
            'sidebar' => array(),
            'content' => array()
        );

        $this->user = array(
            'session' => $this->session->all_userdata()
        );
    }

    function _head()
    {
		$this->load->view('head', $this->view);
    }

    function _footer()
    {
		$this->load->view('footer', $this->view);
    }

}
