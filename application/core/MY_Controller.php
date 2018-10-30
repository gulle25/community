<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Controller extends CI_Controller {
    protected $view;

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
    }

    function _head()
    {
		$this->load->view('head', $this->view);

        $this->session->set_userdata(array('logged_in'=> true, 'name' => 'admin'));
        var_dump($this->session->all_userdata());
        // var_dump($_SERVER);



        if ( ! $foo = $this->cache->get('foo'))
        {
            echo 'Saving to the cache!<br />';
            $foo = array('a' => 'foobarbaz!', 'b' => 'fffff');

            // Save into the cache for 5 minutes
            $this->cache->save('foo', $foo, 300);
        }
    }

    function _footer()
    {
		$this->load->view('footer', $this->view);
    }

}
