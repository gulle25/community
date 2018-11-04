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
      $result = $this->db->query('CALL get_user_info(?, ?)', ['email', $email])->result();
      if (!$result)
      {
        return (object) ['errno' => DB_QUERY_FAIL];
      }
      if ($result[0]->errno != 0)
      {
        // 메일 주소가 가입 되지 않음
        return $result[0];
      }

      // 캐시에 저장
      $cache = $result[0];
      $this->cache->save($cache_key, $cache, $this->config->item('cache_exp_user'));
    }

    return $cache;
  }

  /*
    $category : 'email', 'userno', 'name', 'phone'
   */
  function get($category, $value)
  {
    $result = $this->db->query('CALL get_user_info(?, ?)', [$category, $value])->result();
    return $result ? $result[0] : (object) ['errno' => DB_QUERY_FAIL];
  }

  function add($info)
  {
    return $this->db->query('CALL add_user(?, ?, ?, ?, ?, ?)', [$info->name, $info->email, $info->pwd_hash, $info->birthday, $info->residence_hash, $info->phone])->result();
  }
}
?>
