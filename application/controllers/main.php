<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends My_Controller {
	function __construct()
  {
      parent::__construct();
  }

	public function index()
	{

		$this->_head();
		$this->load->view('main', $this->view);
		$this->_footer();
	}
}
?>