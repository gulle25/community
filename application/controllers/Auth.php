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

    $this->_set_gnb();
    $this->_set_sidebar();
    $this->_load_view('login', MENU_LOGOUT);
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

    if ($this->form_validation->run() === false)
    {
      // 폼 검증이 완료 되지 않으면 다시 로그인 폼 출력
      $this->_set_gnb();
      $this->_set_sidebar();
      $this->_load_view('login', MENU_LOGOUT);
      return;
    }

    // form validatoin 완료
    $this->cafe_type = $this->input->post('cafe_type');
    $this->load->database('mast');
    $this->load->model('mast_model');
    $user = $this->mast_model->get_user_mast('email', $this->input->post('email'), false, true);
    if ($user->errno != My_Model::DB_NO_ERROR)
    {
      $this->_set_flash_message(lang($user->errno == My_Model::DB_QUERY_FAIL ? 'query_fail' : 'email_not_found'));
      $this->_set_gnb();
      $this->_set_sidebar();
      $this->_load_view('login', MENU_LOGOUT);
      return;
    }

    $pwd_hash = md5($this->input->post('password'));
    if ($user->pwd_hash != $pwd_hash)
    {
      // 비밀번호 틀림
      $this->_set_flash_message(lang('wrong_password'));
      $this->_set_gnb();
      $this->_set_sidebar();
      $this->_load_view('login', MENU_LOGOUT);
      return;
    }

    // 인증 성공, 세션에 저장
    $this->_set_flash_message(lang('login_success'));
    $this->session->set_userdata('is_logged_in', true);
    $this->session->set_userdata('userid', $user->userid);
    $this->session->set_userdata('email', $this->input->post('email'));
    $this->session->set_userdata('grade', $user->grade);
    // echo json_encode($user);
    // echo json_encode($this->session->userdata());
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

    $this->_set_gnb();
    $this->_set_sidebar();

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
        $this->_load_view('signup_agree', MENU_LOGOUT);    // 약관 동의
        return;

      case 'agree':   // 약관 동의
        if (!$sess_signup || $sess_signup->mode != 'begin')
        {
          $this->_redirect('/');
          return;
        }

        $this->form_validation->set_rules('agree_service', lang('agree_service'), 'required');
        $this->form_validation->set_rules('agree_user_info', lang('agree_user_info'), 'required');
        $this->form_validation->set_rules('agree_location_info', lang('agree_location_info'), 'required');

        if ($this->form_validation->run() === false)
        {
          $this->_load_view('signup_agree', MENU_LOGOUT);
          return;
        }

        $sess_signup->mode = $mode;
        $sess_signup->agree_service = $this->input->post('agree_service') ? true : false;
        $sess_signup->agree_user_info = $this->input->post('agree_user_info') ? true : false;
        $sess_signup->agree_location_info = $this->input->post('agree_location_info') ? true : false;
        $sess_signup->agree_event = $this->input->post('agree_event') ? true : false;
        $this->session->set_userdata('signup', $sess_signup);
        // $this->_load_view('signup', MENU_LOGOUT);    // 회원 가입
        $this->_load_view('signup_email', MENU_LOGOUT);    // 회원 가입
        return;

      case 'email':   // 이메일 주소 검증
        if (!$sess_signup || $sess_signup->mode != 'agree')
        {
          $this->_redirect('/');
          return;
        }

        $this->form_validation->set_rules('email', lang('email'), 'required|valid_email|max_length[120]');
        if ($this->form_validation->run() === false)
        {
          $this->_load_view('signup_email', MENU_LOGOUT);
          return;
        }

        // 이메일 주소 중복 검사
        $email = $this->input->post('email');
        $this->load->database('mast');
        $this->load->model('mast_model');
        $user = $this->mast_model->get_user_mast('email', $email, false, false);
        if ($user->errno == My_Model::DB_NO_ERROR)
        {
          // 이미 가입된 이메일 주소
          $this->_set_flash_message(lang('user_already_exist'));
          $this->_load_view('signup_email', MENU_LOGOUT);
          return;
        }

        $email_auth = mt_rand(100000, 999999);
        $sess_signup->email_auth = $email_auth;

        // [TODO] 인증 메일 발송

        $sess_signup->mode = $mode;
        $sess_signup->email = $email;
        $this->session->set_userdata('signup', $sess_signup);
        $this->_load_view('signup_email_auth', MENU_LOGOUT);    // 인증 메일 확인
        return;

      case 'email_auth':   // 이메일 인증 문자 확인
        if (!$sess_signup || $sess_signup->mode != 'email')
        {
          $this->_redirect('/');
          return;
        }

        $this->form_validation->set_rules('email_auth', lang('auth_num'), 'required|exact_length[6]|numeric');
        if ($this->form_validation->run() === false)
        {
          $this->_load_view('signup_email_auth', MENU_LOGOUT);
          return;
        }

        // 이메일 인증 문자 검사
        $email_auth = $this->input->post('email_auth');
        if ($email_auth != $sess_signup->email_auth)
        {
          // 인증 문자 다름
          $this->_set_flash_message(lang('auth_fail'));
          $this->_load_view('signup_email_auth', MENU_LOGOUT);
          return;
        }

        $sess_signup->mode = $mode;
        $this->session->set_userdata('signup', $sess_signup);
        $this->_load_view('signup_password', MENU_LOGOUT);    // 비밀번호 등록
        return;

      case 'password':   // 비밀번호 등록
        if (!$sess_signup || $sess_signup->mode != 'email_auth')
        {
          $this->_redirect('/');
          return;
        }

        $this->form_validation->set_rules('password', lang('password'), 'required|exact_length[4]|numeric');
        $this->form_validation->set_rules('re_password', lang('re_password'), 'required|matches[password]');
        if ($this->form_validation->run() === false)
        {
          $this->_load_view('signup_password', MENU_LOGOUT);
          return;
        }

        $this->load->database('mast');
        $this->load->model('mast_model');

        // user ID 생성
        $duplicated = false;
        do {
          $sess_signup->userid = $this->_make_random_base36(BASE36_LEN_USERID);
          $result = $this->mast_model->get_user_mast('userid', $sess_signup->userid, false, false);
          $duplicated = $result->errno == My_Model::DB_NO_ERROR;
        }
        while ($duplicated);

        // 계정 생성
        $pwd_hash = md5($this->input->post('password'));
        $sess_signup->name = 'unregistered  ';
        $sess_signup->pwd_hash = $pwd_hash;
        $sess_signup->residence_hash = md5($sess_signup->email);
        $sess_signup->birthday = 99999999;
        $sess_signup->gender = 'N';
        $sess_signup->phone = '';
        $sess_signup->info = (object)[];
        $this->session->unset_userdata('signup', false);
        $this->session->set_userdata('is_logged_in', false);

        $user = $this->mast_model->add($sess_signup);
        if ($user->errno != My_Model::DB_NO_ERROR)
        {
          // 오류
          $this->_set_flash_message(lang('query_fail'));
          $this->_redirect('/');
          return;
        }

        // 가입 성공
        $this->_set_flash_message(lang('signup_success'));
        $this->_redirect('/');
        return;

      case 'apply':   // 회원 인증 및 가입
        if (!$sess_signup || ($sess_signup->mode != 'agree' && $sess_signup->mode != 'apply'))
        {
          $this->_redirect('/');
          return;
        }

        $this->load->database('mast');
        $this->load->model('mast_model');
        $sess_signup->mode = $mode;

        // var_dump($this->session);
        if (!isset($sess_signup->name_proved))
        {
          $this->form_validation->set_rules('name', lang('name'), 'required|max_length[10]');
          $this->form_validation->set_rules('residence_num1', lang('residence_num1'), 'required|numeric|exact_length[6]');
          $this->form_validation->set_rules('residence_num2', lang('residence_num2'), 'required|numeric|exact_length[7]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup', MENU_LOGOUT);
            return;
          }

          // 주민번호로 중복 가입 검사
          $residence_hash = md5($this->input->post('residence_num1') . $this->input->post('residence_num2'));
          $user = $this->mast_model->get_user_mast('residence', $residence_hash, false, false);
          if ($user->errno == My_Model::DB_NO_ERROR)
          {
            $this->_set_flash_message(lang('user_already_exist'));
            $this->_set_gnb();
            $this->_set_sidebar();
            $this->_load_view('signup', MENU_LOGOUT);
            return;
          }

          // [TODO] 실명 인증 후 처리
          $sess_signup->name = $this->input->post('name');
          $sess_signup->residence_num1 = $this->input->post('residence_num1');
          $sess_signup->residence_hash = md5($this->input->post('residence_num1').$this->input->post('residence_num2'));
          $sess_signup->name_proved = true;
        }

        if (!isset($sess_signup->email_proved))
        {
          $this->form_validation->set_rules('email', lang('email'), 'required|valid_email|max_length[120]');
          $this->form_validation->set_rules('prove_email', lang('prove_email'), 'required|numeric|exact_length[6]');

          if ($this->form_validation->run() === false)
          {
            $this->_load_view('signup', MENU_LOGOUT);
            return;
          }

          // 이메일 주소 중복 가입 검사
          $user = $this->mast_model->get_user_mast('email', $this->input->post('email'), false, false);
          if ($user->errno == My_Model::DB_NO_ERROR)
          {
            $this->_set_flash_message(lang('email_already_exist'));
            $this->_set_gnb();
            $this->_set_sidebar();
            $this->_load_view('signup', MENU_LOGOUT);
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
            $this->_load_view('signup', MENU_LOGOUT);
            return;
          }

          // 휴대폰 번호 주소 중복 가입 검사
          $user = $this->mast_model->get_user_mast('phone', $this->input->post('phone'), false, false);
          if ($user->errno == My_Model::DB_NO_ERROR)
          {
            $this->_set_flash_message(lang('phone_already_exist'));
            $this->_set_gnb();
            $this->_set_sidebar();
            $this->_load_view('signup', MENU_LOGOUT);
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
            $this->_load_view('signup', MENU_LOGOUT);
            return;
          }

          // 비밀번호 인증 후 처리
          $sess_signup->pwd_hash = md5($this->input->post('password'));
          $sess_signup->password_proved = true;
        }

        // 회원 가입 진행
        $residence_class = substr($sess_signup->residence_num1, 0, 1);
        $sess_signup->gender = ($residence_class == '1' || $residence_class == '3') ? 'M' : 'F';
        $sess_signup->birthday = sprintf('%s%s', ($residence_class == '1' || $residence_class == '2') ? '19' : '20', $sess_signup->residence_num1);
        // $this->session->unset_userdata('signup', false);
        $this->session->set_userdata('is_logged_in', false);
        // var_dump($sess_signup);
        $result = $this->mast_model->add($sess_signup);
        // $this->session->set_userdata('signup', $sess_signup);
        $this->_load_view('signup', MENU_LOGOUT);    // 회원 가입
        return;
    }

    $this->_redirect('/');
  }

}
?>