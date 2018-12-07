<?php
include ('cafe.php');

class Apart extends Cafe {
  function __construct()
  {
    parent::__construct();
  }

  function _set_gnb()
  {
    $this->_set_common_gnb();
  }

  function _set_sidebar()
  {
    $this->_set_common_sidebar();
  }

  function _has_permission($boardid, $action)
  {
    return parent::_has_permission($boardid, $action);
  }

}
?>