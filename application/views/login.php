<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<form action="/index.php/">
  <div class="form-group">
    <label for="email"><?=lang('main_email_addr')?></label>
    <input type="email" class="form-control" id="email" placeholder="<?=lang('main_input_email')?>">
  </div>
  <div class="form-group">
    <label for="password"><?=lang('main_password')?></label>
    <input type="password" class="form-control" id="password" placeholder="<?=lang('main_input_password')?>">
  </div>
  <div class="form-group">
    <input type="checkbox" id="save_pwd"><?=lang('main_save_pwd')?>
  </div>
  <button type="submit" class="btn btn-default"><?=lang('main_login')?></button>
  <button type="submit" class="btn"><?=lang('main_sign_up')?></button>
</form>