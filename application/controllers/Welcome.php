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
    // if ($this->maintaining) return;
    if (!$this->_is_logged_in()) {
      $this->_try_login();
      return;
    }

    if ($this->session->is_logged_in)
    {
      // 로그인 되어진 메인 페이지
      $this->_set_gnb_home();
      $this->_set_sidebar_home();
      // $this->_load_view('main');
    }
    else
    {
      // $this->_redirect('/auth/login?returnURL=' . rawurlencode(site_url($this->input->get('returnURL'))));
    }
  }
}
?>