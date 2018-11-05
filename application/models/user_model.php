<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends My_Model {

  function __construct()
  {
      parent::__construct();
  }

  function login($email)
  {
    // 사용자 정보 캐시 확인
    $cache_key = CACHE_KEY_USER . md5($email);
    $cache = $this->cache->get($cache_key);
    if (!$cache)
    {
      // 사용자 캐시가 존재 하지 않으면 DB 에서 정보 읽는다
      $cache = My_Model::call_single_row('CALL get_user_info(?, ?)', ['email', $email]);
      // var_dump($result);
      if ($cache->errno == My_Model::DB_NO_ERROR)
      {
        // 캐시에 저장
        $this->cache->save($cache_key, $cache, $this->config->item('cache_exp_user'));
      }
    }

    return $cache;
  }

  /*
    $category : 'email', 'userno', 'name', 'phone'
   */
  function get($category, $value)
  {
    return My_Model::call_single_row('CALL get_user_info(?, ?)', [$category, $value]);
  }

  function add($info)
  {
    // return My_Model::call_single_row('CALL add_user(?, ?, ?, ?, ?, ?, ?)', [$info->name, $info->email, $info->pwd_hash, $info->birthday, $info->gender, $info->residence_hash, $info->phone]);
  }
}
?>
