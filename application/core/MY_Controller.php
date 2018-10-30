<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
    protected $view;

	function __construct()
    {
        parent::__construct();

        $this->load->config('environment');
        $this->load->config('community');
        // $this->load->library('session');
        // $this->load->driver('cache', array('redis'));

        $this->view = array(
            'gnb' => array(),
            'sidebar' => array(),
            'content' => array()
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
