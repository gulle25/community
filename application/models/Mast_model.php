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
  function get_user_mast($category, $value, $check_cache = true)
  {
    if ($check_cache && $category == 'userid')
    {
      $cache_key = CACHE_KEY_USER . $value;
      $cache = $this->cache->get($cache_key);
      if ($cache) return $cache;
    }

    // DB 에서 정보 읽기
    $user = parent::call_single_row('CALL get_user_mast(?, ?)', [$category, $value]);
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
      $cache_key = CACHE_KEY_USER . $user->userid;
      $this->cache->save($cache_key, $user, $this->config->item('cache_exp_user'));
    }

    return $user;
  }

  function get_cafe_mast($cafeid, $visit, $userid)
  {
    // DB 에서 정보 읽기
    $cafe = parent::call_single_row('CALL get_cafe_mast(?, ?, ?)', [$cafeid, $visit, $userid]);
    if ($cafe->errno != parent::DB_NO_ERROR)
    {
      return $cafe;
    }

    // JSON -> Objet
    $cafe->cafe_info = json_decode($cafe->cafe_info_json);
    unset($cafe->cafe_info_json);
    return $cafe;
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
