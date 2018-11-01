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
    $this->form_validation->set_rules('cafe_type', 'cafe_type', 'required');

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

    // 사용자 정보 캐시 확인
    $email = $this->input->post('email');
    $cafe_type = $this->input->post('cafe_type');
    $cache_key = CACHE_KEY_USER . md5($email);
    $cache = $this->cache->get($cache_key);
    if (!$cache)
    {
      // 사용자 캐시가 존재 하지 않으면 DB 에서 정보 읽는다
      $this->load->database();
      $this->load->model('user_model');
      $result = $this->user_model->get('email', $email, $cafe_type);
      if (!$result)
      {
        // DB 읽기 실패
        $this->_set_flash_message(lang('query_fail'));
        $this->_set_gnb_unsigned();
        $this->_set_sidebar_unsigned();
        $this->_load_view('login');
        return;
      }
      if ($result[0]->errno != 0)
      {
        // 메일 주소가 가입 되지 않음
        $this->_set_flash_message(lang('email_not_found'));
        $this->_set_gnb_unsigned();
        $this->_set_sidebar_unsigned();
        $this->_load_view('login');
        return;
      }

      // 캐시에 저장
      $cache = $result[0];
      $this->cache->save($cache_key, $cache, $this->config->item('cache_exp_user'));
    }

    if ($cache->pwd_hash != $pwd_hash)
    {
      // 비밀번호 틀림
      $this->_set_flash_message(lang('wrong_password'));
      $this->_set_gnb_unsigned();
      $this->_set_sidebar_unsigned();
      $this->_load_view('login');
      return;
    }

    // 인증 성공, 세션에 저장
    $this->_set_flash_message(lang('login_success'));
    // echo site_url($this->input->get('returnURL'));
    $this->session->set_userdata('is_logged_in', true);
    redirect(site_url($this->input->get('returnURL')));
  }
}
?>