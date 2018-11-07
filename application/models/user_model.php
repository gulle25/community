<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends My_Model {

  function __construct()
  {
      parent::__construct();
  }

  /*
    $category : 'email', 'userno', 'name', 'phone'
    $check_cache : availeble when $category is 'userno'
    $is_login : available when $category is 'email'
   */
  function get($category, $value, $check_cache = true, $is_login = false)
  {
    if ($check_cache && $category == 'userno')
    {
      $cache_key = CACHE_KEY_USER . md5($value);
      $cache = $this->cache->get($cache_key);
      if ($cache) return $cache;
    }

    // DB 에서 정보 읽기
    $user = My_Model::call_single_row('CALL get_user_info(?, ?, ?)', [$category, $value, $is_login]);
    if ($user->errno != My_Model::DB_NO_ERROR)
    {
      return null;
    }

    // JSON -> Objet
    $user->user_info = json_decode($user->user_info_json);
    $user->cafe_info = json_decode($user->cafe_info_json);
    $user->admin_info = json_decode($user->admin_info_json);
    unset($user->user_info_json);
    unset($user->cafe_info_json);
    unset($user->admin_info_json);

    if ($check_cache)
    {
      // 캐시에 저장
      // var_dump($cache);
      $cache_key = CACHE_KEY_USER . md5($user->userno);
      $this->cache->save($cache_key, $user, $this->config->item('cache_exp_user'));
    }

    return $user;
  }

  function add($info)
  {
    return My_Model::call_single_row('CALL add_user(?, ?, ?, ?, ?, ?, ?, ?)', [$info->name, $info->email, $info->pwd_hash, $info->birthday, $info->gender, $info->residence_hash, $info->phone, json_encode($info->info)]);
  }

  function update($userno, $data)
  {
    $query = My_Model::make_update_query('user_mast', 'userno', $userno, $data);
    $result = $this->db->query($query);
    echo $query . '<br>';
    var_dump($result);
  }
}
?>
