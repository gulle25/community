<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends My_Controller {

  public function index()
  {
    $this->_redirect('/auth/login?returnURL=' . rawurlencode(site_url($this->input->get('returnURL'))));
  }

  public function login()
  {
    if ($this->session->is_logged_in)
    {
      // 로그인 되어진 메인 페이지
      $this->_redirect('/');
      return;
    }

    // 로그인 폼 출력
    $this->load->library('form_validation');

    $this->_set_gnb_unsigned();
    $this->_set_sidebar_unsigned();
    $this->_load_view('login');
  }

  public function authenticate()
  {
    if ($this->session->is_logged_in)
    {
      // 로그인 되어진 메인 페이지
      $this->_redirect('/');
      return;
    }

    $this->load->library('form_validation');

    $this->form_validation->set_rules('email', lang('email'), 'required|valid_email|max_length[120]');
    $this->form_validation->set_rules('password', lang('password'), 'required|min_length[4]|max_length[30]');

    if ($this->form_validation->run() === false)
    {
      // 폼 검증이 완료 되지 않으면 다시 로그인 폼 출력
      $this->_set_gnb_unsigned();
      $this->_set_sidebar_unsigned();
      $this->_load_view('login');
      return;
    }

    // form validatoin 완료
    $pwd_hash = md5($this->input->post('password'));

    // [TODO] 사용자 인증

    // redirect('/');
  }
}
?>