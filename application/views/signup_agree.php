<?php
defined('BASEPATH') OR exit('No direct script access allowed');

echo validation_errors();
echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/signup', array('id' => 'form'));
?>

  <input type="hidden" name="mode" value="agree">
  <input type="checkbox" id="agree_all" name="agree_all">
  <?=lang('agree_all')?><br><p>
  <input type="checkbox" id="agree_service" name="agree_service" <?php echo set_value('agree_service') ? "checked" : ""?>>
  <?=lang('agree_service')?><br>
  여러분을 환영합니다.<br><p>
  <input type="checkbox" id="agree_user_info" name="agree_user_info" <?php echo set_value('agree_user_info') ? "checked" : ""?>>
  <?=lang('agree_user_info')?><br>
  1. 수집하는 개인정보<br><p>
  <input type="checkbox" id="agree_location_info" name="agree_location_info" <?php echo set_value('agree_location_info') ? "checked" : ""?>>
  <?=lang('agree_location_info')?><br>
  제 1 조 (목적)<br><p>
  <input type="checkbox" id="agree_event" name="agree_event" <?php echo set_value('agree_event') ? "checked" : ""?>>
  <?=lang('agree_event')?><br><p>
  <a href="/"><label class="btn"><?=lang('cancel')?></label></a>
  <button type="submit" class="btn btn-default"><?=lang('agree')?></button>
</form>

<script type="text/javascript">
$(document).ready(function() {
  $('#agree_all').click(function() {
    var checked = $('#agree_all').is(":checked");
    $('#agree_service').prop('checked', checked);
    $('#agree_user_info').prop('checked', checked);
    $('#agree_location_info').prop('checked', checked);
    $('#agree_event').prop('checked', checked);
    })

  $('#agree_service').click(function() {
    if (!$('#agree_service').is(":checked")) {
      $('#agree_all').prop('checked', false);
    }})

  $('#agree_user_info').click(function() {
    if (!$('#agree_user_info').is(":checked")) {
      $('#agree_all').prop('checked', false);
    }})

  $('#agree_location_info').click(function() {
    if (!$('#agree_location_info').is(":checked")) {
      $('#agree_all').prop('checked', false);
    }})

  $('#agree_event').click(function() {
    if (!$('#agree_event').is(":checked")) {
      $('#agree_all').prop('checked', false);
    }})
  });
</script>
