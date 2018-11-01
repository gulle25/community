<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends My_Model {
 
    function __construct()
    {       
        parent::__construct();
    }
 
    function gets () {
        return $this->db->query("SELECT * FROM topic")->result();
    }
 
    function get($topic_id){
        return $this->db->get_where('topic', array('id'=>$topic_id))->row();
    }
}
?>
