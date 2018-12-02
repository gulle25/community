<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mast_model extends My_Model {

  function __construct()
  {
      parent::__construct();
  }

  /*
    $category : 'email', 'userid', 'name', 'phone'
    $check_cache : availeble when $category is 'userid'
    $is_login : available when $category is 'email'
   */
  function get_user_mast($category, $value, $check_cache = true, $is_login = false)
  {
    if ($check_cache && $category == 'userid')
    {
      $cache_key = CACHE_KEY_USER . md5($value);
      $cache = $this->cache->get($cache_key);
      if ($cache) return $cache;
    }

    // DB 에서 정보 읽기
    $user = parent::call_single_row('CALL get_user_mast(?, ?, ?)', [$category, $value, $is_login]);
    if ($user->errno != parent::DB_NO_ERROR)
    {
      return $user;
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
      $cache_key = CACHE_KEY_USER . md5($user->userid);
      $this->cache->save($cache_key, $user, $this->config->item('cache_exp_user'));
    }

    return $user;
  }

  function get_cafe_mast($cafeid)
  {
    // DB 에서 정보 읽기
    $cafe = parent::call_single_row('CALL get_cafe_mast(?)', $cafeid);
    if ($cafe->errno != parent::DB_NO_ERROR)
    {
      return $cafe;
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
      $cache_key = CACHE_KEY_USER . md5($user->userid);
      $this->cache->save($cache_key, $user, $this->config->item('cache_exp_user'));
    }

    return $user;
  }

  function add($info)
  {
    return parent::call_single_row('CALL add_user(?, ?, ?, ?, ?, ?, ?, ?, ?)', [$info->userid, $info->name, $info->email, $info->pwd_hash, $info->birthday, $info->gender, $info->residence_hash, $info->phone, json_encode($info->info)]);
  }

  function update($userid, $data)
  {
    $query = parent::make_update_query('user_mast', (object) ['userid' => $userid], $data);
    $result = $this->db->query($query);
  }
}
?>
