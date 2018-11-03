<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends My_Controller {

  public function index()
  {
    if ($this->maintaining) return;

    $this->_redirect('/auth/login?returnURL=' . rawurlencode(site_url($this->input->get('returnURL'))));
  }

  public function login()
  {
    if ($this->_is_logged_in()) {
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
    $this->session->set_userdata('is_logged_in', true);
    $this->session->set_userdata('email', $email);
    $this->session->set_userdata('cafe_type', 'apart');
    $this->session->set_userdata('user_grade', $cache->grade);

    redirect('http://' . site_url($this->input->get('returnURL')));
  }

  function logout()
  {
    $this->session->unset_userdata('is_logged_in');
    $this->_redirect('/');
  }

  function signup()
  {
    if ($this->_is_logged_in()) {
      $this->_redirect('/');
      return;
    }

    $this->_set_gnb_unsigned();
    $this->_set_sidebar_unsigned();

    // 회원 가입 폼 출력
    $this->load->library('form_validation');

    $content = $this->view->content;
    $content->signup = (object) [];
    $sess_signup = $this->session->userdata('signup');

    $mode = $this->input->get('mode');
    if (!$mode) $mode = $this->input->post('mode');
    switch ($mode)
    {
      case 'begin':   // 회원 가입 절차 시작
        $this->session->set_userdata('signup', (object) ['mode' => $mode]);
        $this->_load_view('signup_agree');    // 약관 동의
        return;

      case 'agree':   // 약관 동의
        if ($sess_signup->mode != 'begin')
        {
          $this->_redirect('/');
          return;
        }

        $this->form_validation->set_rules('agree_service', lang('agree_service'), 'required');
        $this->form_validation->set_rules('agree_user_info', lang('agree_user_info'), 'required');
        $this->form_validation->set_rules('agree_location_info', lang('agree_location_info'), 'required');

        if ($this->form_validation->run() === false)
        {
          $this->_load_view('signup_agree');
          return;
        }

        $sess_signup->mode = $mode;
        $sess_signup->agreement = (object) ['service' => $this->input->post('agree_service'),
          'agree_user_info' => $this->input->post('agree_user_info'),
          'location_info' => $this->input->post('agree_location_info'),
          'event' => $this->input->post('agree_event') ? $this->input->post('agree_event') : 'off'];
        $this->session->set_userdata('signup', $sess_signup);
        $this->_load_view('signup');    // 회원 가입
        return;

      case 'apply':   // 회원 인증 및 가입
        if ($sess_signup->mode != 'agree' && $sess_signup->mode != 'apply')
        {
          $this->_redirect('/');
          return;
        }

        // var_dump($this->session);
        if (!isset($sess_signup->name_proved))
        {
          $this->form_validation->set_rules('name', lang('name'), 'required|max_length[10]');
          $this->form_validation->set_rules('reg_num1', lang('reg_num1'), 'required|numeric|exact_length[6]');
          $this->form_validation->set_rules('reg_num2', lang('reg_num2'), 'required|numeric|exact_length[7]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup');
            return;
          }

          // [TODO] 실명 인증 후 처리
          $sess_signup->name = $this->input->post('name');
          $sess_signup->regidence_hash = md5($this->input->post('reg_num1').$this->input->post('reg_num2'));
          $sess_signup->name_proved = true;
        }

        if (!isset($sess_signup->email_proved))
        {
          $this->form_validation->set_rules('email', lang('email'), 'required|valid_email|max_length[120]');
          $this->form_validation->set_rules('prove_email', lang('prove_email'), 'required|numeric|exact_length[6]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup');
            return;
          }

          // [TODO] 이메일 인증 후 처리
          $sess_signup->email = $this->input->post('email');
          $sess_signup->email_proved = true;
        }

        if (!isset($sess_signup->phone_proved))
        {
          $this->form_validation->set_rules('phone', lang('phone'), 'required|numeric|min_length[10]|max_length[11]');
          $this->form_validation->set_rules('prove_phone', lang('prove_phone'), 'required|numeric|exact_length[6]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup');
            return;
          }

          // [TODO] 휴대폰 번호 인증 후 처리
          $sess_signup->phone = $this->input->post('phone');
          $sess_signup->phone_proved = true;
        }

        if (!isset($sess_signup->password_proved))
        {
          $this->form_validation->set_rules('password', lang('password'), 'required|min_length[4]|max_length[32]');
          $this->form_validation->set_rules('re_password', lang('re_password'), 'required|matches[password]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup');
            return;
          }

          // [TODO] 비밀번호 인증 후 처리
          $sess_signup->pwd_hash = md5($this->input->post('password'));
          $sess_signup->password_proved = true;
        }

        $sess_signup->mode = $mode;
        $this->session->set_userdata('signup', $sess_signup);
        $this->_load_view('signup');    // 회원 가입
        return;
    }
    echo "wrong mode:" . $mode;
    // $this->_load_view('/');
  }

}
?>