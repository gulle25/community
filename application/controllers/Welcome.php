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
		// $sidebar = array(
		// 	array('type' => 'text_link', 'value' => lang('main_board'), 'class' => 'item'),
		// 	array('type' => 'text_link', 'value' => 'board2', 'class' => 'item'),
		// 	array('type' => 'text', 'value' => 'text', 'class' => 'item'),
		// 	array('type' => 'text_link', 'value' => 'board3', 'class' => 'item')
		// );
		// $this->view['sidebar'] = array_merge($this->view['sidebar'], $sidebar);

		// $gnb = array(
		// 	array('type' => 'menubar', 'value' => 'menu', 'class' => 'item')
		// );
		// $this->view['gnb'] = array_merge($this->view['gnb'], $gnb);

        // $this->session->set_userdata(array('logged_in'=> true, 'name' => 'admin'));
        // $this->session->set_userdata('nickname', 'titan');
        // $this->session->mark_as_temp('name', 10);
        // $this->session->mark_as_flash('nickname');
        // var_dump($this->session->all_userdata());

/*
        echo $this->session->userdata('name') . "." . $this->session->name;

        if ( ! $foo = $this->cache->get('foo'))
        {
            echo 'Saving to the cache!<br />';
            $foo = array('a' => 'foobarbaz!', 'b' => 'fffff');

            // Save into the cache for 5 minutes
            $this->cache->save('foo', $foo, 300);
        }
*/

        if ($this->session->is_logged_in)
        {
        	// 로그인 되어진 메인 페이지
			$this->_load_view('main');
        }
        else
        {
        	// 로그인 하지 않은 상태
			$gnb = [
				(object) ['type' => 'menubar', 'value' => 'menu', 'class' => 'item']
			];
			$this->view->gnb = array_merge($this->view->gnb, $gnb);

			$this->view->flash = (object) ['activate' => true, 'class' => 'warning', 'message' => 'Flash message'];

			$this->session->set_flashdata('message', ['class' => 'warning', 'message' => 'Flash message']);

			$sidebar = [
				(object) ['type' => 'text_link', 'value' => lang('main_board'), 'class' => 'item'],
				(object) ['type' => 'text_link', 'value' => 'board2', 'class' => 'item'],
				(object) ['type' => 'text', 'value' => 'text', 'class' => 'item'],
				(object) ['type' => 'text_link', 'value' => 'board3', 'class' => 'item']
			];
			$this->view->sidebar = array_merge($this->view->sidebar, $sidebar);

			$this->_load_view('login');
        }
	}
}
?>