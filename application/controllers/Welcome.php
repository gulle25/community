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

  public function login()
  {
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

      $sidebar = [
        (object) ['type' => 'text_link', 'value' => lang('main_board'), 'class' => 'item'],
        (object) ['type' => 'text_link', 'value' => 'board2', 'class' => 'item'],
        (object) ['type' => 'text', 'value' => 'text', 'class' => 'item'],
        (object) ['type' => 'text_link', 'value' => 'board3', 'class' => 'item']
      ];
      $this->view->sidebar = array_merge($this->view->sidebar, $sidebar);

      $this->load->library('form_validation');

      $this->form_validation->set_rules('email', '이메일 주소', 'required|valid_email|is_unique[user.email]');
      $this->form_validation->set_rules('password', '비밀번호', 'required|min_length[4]|max_length[30]');

      if ($this->form_validation->run() === false)
      {
          $this->_load_view('login');
      }
      else
      {
        // form validatoin 완료
        if (!function_exists('password_hash'))
        {
          $this->load->helper('password');
        }
        $hash = password_hash($this->input->post('password'), PASSWORD_BCRYPT);

        redirect('/');
      }
    }
  }
}
?>