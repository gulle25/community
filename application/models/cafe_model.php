<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cafe_model extends My_Model {

  function __construct()
  {
      parent::__construct();
  }

  function get($cafeid, $visit = false, $userid = '')
  {
    $cache_key = CACHE_KEY_CAFE . md5($cafeid);
    $cafe = $visit ? null : $this->cache->get($cache_key);

    if ($visit || !$cafe)
    {
      // DB 에서 카페 정보를 읽는다
      $cafe = parent::call_single_row('CALL get_cafe_info(?, ?, ?)', [$cafeid, $visit, $userid]);
      if ($cafe->errno != parent::DB_NO_ERROR)
      {
        return $cafe;
      }

      // JSON -> Objet
      $cafe->cafe_info = json_decode($cafe->cafe_info_json);
      $cafe->role_info = json_decode($cafe->role_info_json);
      $cafe->board_info = json_decode($cafe->board_info_json);
      unset($cafe->cafe_info_json);
      unset($cafe->role_info_json);
      unset($cafe->board_info_json);

      // 캐시에 저장
      // var_dump($cafe);
      $cache_key = CACHE_KEY_CAFE . md5($cafeid);
      $this->cache->save($cache_key, $cafe, $this->config->item('cache_exp_cafe'));
    }

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
