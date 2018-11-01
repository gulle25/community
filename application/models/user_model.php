<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends My_Model {

    function __construct()
    {
        parent::__construct();
    }

    function get ($category, $value, $cafe_type) {
        return $this->db->query('CALL get_user_info(?, ?, ?)', [$category, $value, $cafe_type])->result();
    }

}
?>
