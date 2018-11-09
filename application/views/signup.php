<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <main>
          <!-- <div class="container-fluid p-0"> -->

          <!-- Flash Message -->
    <?php if ($this->session->flashdata('message')): ?>
          <div class="alert alert-primary" role="alert">
            <div class="alert-message">
              <?php echo $this->session->flashdata('message')['message']; ?>
              <?php $this->session->set_flashdata('message'); ?>
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

            <!-- <div class="row h-100"> -->
              <!-- <div class="col-sm-12 col-md-12 col-lg-12 mx-auto d-table h-100"> -->
                <!-- <div class="d-table-cell align-middle"> -->
                  <!-- <div class="card"> -->
                    <div class="card-body">
                      <div class="card-header">
                        <h5 class="card-title"><?=lang('agree_term')?></h5>
                        <!-- <h6 class="card-subtitle text-muted">약관 동의</h6> -->
                      </div>
              <?php echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/signup', array('id' => 'validation-form')); ?>

                        <input type="hidden" name="mode" value="apply">

                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="name"><?=lang('name')?></label>
                          <div class="form-group col-sm-9">
                          <?php if (isset($this->session->userdata('signup')->name_proved)): ?>
                            <span><?=$this->session->userdata('signup')->name?></span>
                          <?php else: ?>
                            <input type="text" class="form-control col-sm-6" id="name" name="name" value="<?php echo set_value('name'); ?>" placeholder="<?=lang('input_name')?>">
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="residence_num"><?=lang('residence_num')?></label>
                          <div class="form-group col-sm-9">
                            <input type="text" class="col-sm-3" id="residence_num1" name="residence_num1" placeholder="<?=lang('input_residence_num1')?>"> -
                            <input type="password" class="col-sm-3" id="residence_num2" name="residence_num2" placeholder="<?=lang('input_residence_num2')?>">
                            <input type="button" class="btn" value="<?=lang('prove_name')?>">
                          <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="email"><?=lang('email')?></label>
                          <div class="form-group col-sm-9">
                          <?php if (isset($this->session->userdata('signup')->email_proved)): ?>
                            <?=$this->session->userdata('signup')->email?>
                          <?php else: ?>
                            <input type="email" class="col-sm-6" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="<?=lang('input_email')?>">
                            <input type="button" class="btn" value="<?=lang('send_email_auth')?>">
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right"></label>
                          <div class="form-group col-sm-9">
                            <input type="text" class="col-sm-6" id="prove_email" name="prove_email" placeholder="<?=lang('input_email_auth')?>">
                            <input type="button" class="btn" value="<?=lang('prove_email')?>">
                        <?php endif; ?>
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="phone"><?=lang('phone')?></label>
                          <div class="form-group col-sm-9">
                          <?php if (isset($this->session->userdata('signup')->phone_proved)): ?>
                            <?=$this->session->userdata('signup')->phone?>
                          <?php else: ?>
                            <input type="text" class="col-sm-6" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="<?=lang('input_phone')?>">
                            <input type="button" class="btn" value="<?=lang('send_phone_auth')?>">
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right"></label>
                          <div class="form-group col-sm-9">
                            <input type="text" class="col-sm-6" id="prove_phone" name="prove_phone" placeholder="<?=lang('input_phone_auth')?>">
                            <input type="button" class="btn" value="<?=lang('prove_phone')?>">
                          <?php endif; ?>
                          </div>
                        </div>
                          <?php if (!isset($this->session->userdata('signup')->password_proved)): ?>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="password"><?=lang('password')?></label>
                          <div class="form-group col-sm-9">
                            <input type="password" class="form-control col-sm-6" id="password" name="password" placeholder="<?=lang('input_password')?>">
                          </div>
                        </div>
                        <div class="row">
                          <label class="col-form-label col-sm-3 text-sm-right" for="re_password"><?=lang('re_password')?></label>
                          <div class="form-group col-sm-9">
                            <input type="password" class="form-control col-sm-6" id="re_password" name="re_password" placeholder="<?=lang('input_re_password')?>">
                          </div>
                        </div>
                        <a href="/"><label class="btn"><?=lang('cancel')?></label></a>
                        <button type="submit" class="btn btn-default"><?=lang('sign_up')?></button>
                        <?php else: ?>
                        <a href="/"><label class="btn"><?=lang('login')?></label></a>
                        <?php endif; ?>
                      </form>
                    </div>
                  <!-- </div> -->
                <!-- </div> -->
              <!-- </div> -->
            <!-- </div> -->
          <!-- </div> -->
        </main>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {
    // Initialize validation
    $('#validation-form').validate({
      ignore: '.ignore, .select2-input',
      focusInvalid: false,
      rules: {
        'email': {
          required: true,
          email: true
        },
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
