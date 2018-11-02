<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo validation_errors();
echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/apply');
?>

  <div class="form-group">
    <label for="email"><?=lang('email')?></label>
    <input type="email" class="form-control" id="email" name="email" placeholder="<?=lang('input_email')?>" value="<?=isset($_REQUEST['email']) ? $_REQUEST['email'] : ''?>">
  </div>
  <div class="form-group">
    <label for="password"><?=lang('password')?></label>
    <input type="password" class="form-control" id="password" name="password" placeholder="<?=lang('input_password')?>">
  </div>
  <div class="form-group">
    <input type="checkbox" id="save_pwd" name="save_pwd"><?=lang('save_pwd')?>
  </div>
  <div class="form-group">
    <input type="hidden" id="cafe_type" name="cafe_type" value="apart">
  </div>
  <button type="submit" class="btn btn-default"><?=lang('login')?></button>
</form>