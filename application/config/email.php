<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol'] = 'sendmail';
$config['mailpath'] = $_SERVER['DOCUMENT_ROOT'] . '/sendmail/sendmail.exe';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
?>
