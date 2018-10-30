<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends My_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function index()
	{
		$sidebar = array(
			array('type' => 'text_link', 'value' => 'board1', 'class' => 'item'),
			array('type' => 'text_link', 'value' => 'board2', 'class' => 'item'),
			array('type' => 'text', 'value' => 'text', 'class' => 'item'),
			array('type' => 'text_link', 'value' => 'board3', 'class' => 'item')
		);
		$this->view['sidebar'] = array_merge($this->view['sidebar'], $sidebar);

        // $data = $this->customer_m->custlist_all();
        // $this->cache->redis->save('foo', array('a'=>'aa'));
        // $result = $this->cache->redis->get_metadata('foo');
		// var_dump($result);

		$this->_head();
		$this->load->view('main', $this->view);
		$this->_footer();
	}
}
