<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class My_Model extends CI_Model {
  public const DB_QUERY_FAIL = '-999';
  public const DB_NO_ERROR = '0';

	function __construct()
  {
      parent::__construct();
  }

  public function call_multi_row($query, $param)
  {
    $result = $this->db->query($query, $param);
    mysqli_next_result($this->db->conn_id);

    $error = $this->db->error();
    if ($error['code'] != 0)
    {
      return [(object) ['errno' => sprintf('%d', $error['code']), 'error' => $error['message']]];
    }

    $rows = $result->result();
    $result->free_result();

    return $rows ? $rows : [(object) ['errno' => DB_QUERY_FAIL]];
  }

  public function call_single_row($query, $param)
  {
    return $this->call_multi_row($query, $param)[0];
  }

  public function make_update_query($table, $key_col, $key_val, $data)
  {
    $first = true;
    $values = [];
    $query = 'UPDATE ' . $table . ' SET ';

    foreach($data as $col => $val)
    {
      if ($first)
      {
        $query = $query . ', ';
      }
      else
      {
        $first = false;
      }

      $query = $query . $col . ' = ';
      if (is_float($val) || is_int($val) || is_numeric($val))
      {
        $query = $query . $val;
      }
      else if (is_string($val))
      {
        $query = $query . '\'' . $val . '\'';
      }
      else if (is_bool($val))
      {
        $query = $query . ($val ? 'TRUE' : 'FALSE');
      }
      else if (is_array($val) || is_object($val))
      {
        $query = $query . '\'' . json_encode($val) . '\'';
      }
    }

    $query = $query . ' WHERE ' . $key_col . ' = "' . $key_val . '"';
  }
}
?>
