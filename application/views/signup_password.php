<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <main>
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

          <div class="card-body">
            <div class="card-header">
              <h5 class="card-title">비밀번호 등록</h5>
            </div>
    <?php echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/signup', array('id' => 'validation-form')); ?>
              <input type="hidden" name="mode" value="password">
              <div class="form-group">
                <label class="col-form-label" for="email">비밀번호</label>
                <input type="text" class="form-control col-sm-6" id="password" name="password" placeholder="<?=lang('input_password')?>">
              </div>
              <a href="/"><label class="btn"><?=lang('cancel')?></label></a>
              <button type="submit" class="btn btn-default"><?=lang('sign_up')?></button>
            </form>
          </div>
        </main>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    // Initialize validation
    $('#validation-form').validate({
      ignore: '.ignore, .select2-input',
      focusInvalid: false,
      rules: {
        'password': {
          required: true,
          minlength: 4,
          maxlength: 20
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
