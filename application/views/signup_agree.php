<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <main class="content">
          <div class="container-fluid p-0">

            <!-- Flash Message -->
      <?php if ($this->session->flashdata('message')): ?>
            <div class="alert alert-primary" role="alert">
              <div class="alert-message">
                <?=$this->session->flashdata('message')['message']?>
              </div>
            </div>
      <?php endif; ?>

            <!-- Form validation Error -->
      <?php if (validation_errors()): ?>
            <div class="alert alert-primary" role="alert">
              <div class="alert-message">
                <?php echo validation_errors(); ?>
              </div>
            </div>
      <?php endif; ?>

            <div class="row h-100">
              <div class="col-sm-12 col-md-12 col-lg-12 mx-auto d-table h-100">
                <div class="d-table-cell align-middle">
                  <div class="card">
                    <div class="card-body">
                      <div class="card-header">
                        <h5 class="card-title"><?=lang('agree_term')?></h5>
                        <!-- <h6 class="card-subtitle text-muted">약관 동의</h6> -->
                      </div>
              <?php echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/signup?mode=agree', array('id' => 'validation-form')); ?>

                        <input type="hidden" name="mode" value="agree">

                        <div class="form-group">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_all" name="agree_all">
                            <span class="custom-control-label"><h4><?=lang('agree_all')?></h4></span>
                          </label>
                        </div>

                        <div class="form-group">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_service" name="agree_service">
                            <span class="custom-control-label"><h4><?=lang('agree_service')?></h4></span>
                          </label>
                        </div>
                        <h6 class="card-subtitle text-muted">여러분을 환영합니다.</h6>

                        <div class="form-group">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_user_info" name="agree_user_info" <?php echo set_value('agree_user_info') ? "checked" : ""?>>
                              <span class="custom-control-label"><h4><?=lang('agree_user_info')?></h4></span>
                          </label>
                        </div>
                        <h6>1. 수집하는 개인정보</h6>

                        <div class="form-group">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_location_info" name="agree_location_info" <?php echo set_value('agree_location_info') ? "checked" : ""?>>
                              <span class="custom-control-label"><h4><?=lang('agree_location_info')?></h4></span>
                          </label>
                        </div>
                        <h6>제 1 조 (목적)</h6>

                        <div class="form-group">
                          <label class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="agree_event" name="agree_event" <?php echo set_value('agree_event') ? "checked" : ""?>>
                              <span class="custom-control-label"><h4><?=lang('agree_event')?></h4></span>
                          </label>
                        </div>

                        <a href="/"><label class="btn"><?=lang('cancel')?></label></a>
                        <button type="submit" class="btn btn-default btn-lg btn-primary"><?=lang('agree')?></button>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>


<script type="text/javascript">

  document.addEventListener("DOMContentLoaded", function(event) {
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
    // Initialize validation
    $('#validation-form').validate({
      ignore: '.ignore, .select2-input',
      focusInvalid: false,
      rules: {
        'agree_service': {
          required: true
        },
        'agree_user_info': {
          required: true
        },
        'agree_location_info': {
          required: true
        }
      },
      // Errors
      errorPlacement: function errorPlacement(error, element) {
        var $parent = $(element).parents('.form-group');
        // Do not duplicate errors
        if ($parent.find('.jquery-validation-error').length) {
          return;
        }
        $parent.append(
          error.addClass('jquery-validation-error small form-text invalid-feedback')
        );
      },
      highlight: function(element) {
        var $el = $(element);
        var $parent = $el.parents('.form-group');
        $el.addClass('is-invalid');
        // Select2 and Tagsinput
        if ($el.hasClass('select2-hidden-accessible') || $el.attr('data-role') === 'tagsinput') {
          $el.parent().addClass('is-invalid');
        }
      },
      unhighlight: function(element) {
        $(element).parents('.form-group').find('.is-invalid').removeClass('is-invalid');
      }
    });
  });
</script>
