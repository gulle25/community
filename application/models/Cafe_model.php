<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cafe_model extends My_Model {

  function __construct()
  {
      parent::__construct();
  }

  function get_cafe_info($cafeid)
  {
    // DB 에서 카페 정보를 읽는다
    $cafe = parent::call_single_row('CALL get_cafe_info(?)', [$cafeid]);
    if ($cafe->errno != parent::DB_NO_ERROR)
    {
      return $cafe;
    }

    // JSON -> Objet
    $cafe->role_info = json_decode($cafe->role_info_json);
    $cafe->board_info = json_decode($cafe->board_info_json);
    unset($cafe->role_info_json);
    unset($cafe->board_info_json);

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

  function board_list($cafeid, $boardid, $last_ownerid, $last_sequence, $size, $srch_type, $srch_str)
  {
    // DB 에서 게시물 리스트 읽기
    $list = parent::call_multi_row('CALL get_content_list(?, ?, ?, ?, ?, ?, ?, ?)', [$cafeid, $boardid, 'userid', $size, $srch_type, $srch_str, $last_ownerid, $last_sequence]);
    if ($list[0]->errno != parent::DB_NO_ERROR)
    {
      return $list;
    }

    return $list;
  }

}
?>
