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
    $this->cafe_type = $this->input->post('cafe_type');
    $this->load->database();
    $this->load->model('user_model');
    $cache = $this->user_model->login($this->input->post('email'));
    if ($cache->errno != 0)
    {
      $this->_set_flash_message(lang($cache->errno == My_Model::DB_QUERY_FAIL ? 'query_fail' : 'email_not_found'));
      $this->_set_gnb_unsigned();
      $this->_set_sidebar_unsigned();
      $this->_load_view('login');
      return;
    }

    $pwd_hash = md5($this->input->post('password'));
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
    $this->session->set_userdata('email', $this->input->post('email'));

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

        $this->load->database();
        $this->load->model('user_model');

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

          // 주민번호로 중복 가입 검사
          $residence_hash = md5($this->input->post('reg_num1') . $this->input->post('reg_num2'));
          $user = $this->user_model->get('residence', $residence_hash);
          if ($user->errno == 0)
          {
            $this->_set_flash_message(lang('user_already_exist'));
            $this->_set_gnb_unsigned();
            $this->_set_sidebar_unsigned();
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

          // 이메일 주소 중복 가입 검사
          $user = $this->user_model->get('email', $this->input->post('email'));
          if ($user->errno == 0)
          {
            $this->_set_flash_message(lang('email_already_exist'));
            $this->_set_gnb_unsigned();
            $this->_set_sidebar_unsigned();
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

          // 휴대폰 번호 주소 중복 가입 검사
          $user = $this->user_model->get('phone', $this->input->post('phone'));
          if ($user->errno == 0)
          {
            $this->_set_flash_message(lang('phone_already_exist'));
            $this->_set_gnb_unsigned();
            $this->_set_sidebar_unsigned();
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

        $result = $this->user_model->add($this->input->post());
        var_dump($result);
        // 회원 가입 진행
        // $sess_signup->mode = $mode;
        // $this->session->set_userdata('signup', $sess_signup);
        // $this->_load_view('signup');    // 회원 가입
        return;
    }
    echo "wrong mode:" . $mode;
    // $this->_load_view('/');
  }

}
?>