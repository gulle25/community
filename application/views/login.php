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

                      <!-- Sign-in Form -->
            <?php echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/authenticate', array('id' => 'validation-form')); ?>
                        <div class="form-group row">
                          <label class="col-form-label col-sm-3 text-sm-right"><?=lang('email')?></label>
                          <div class="col-sm-9">
                            <input type="text" class="form-control" name="email" placeholder="<?=lang('input_email')?>" value="<?=set_value('email')?>">
                          </div>
                        </div>
                        <div class="form-group row">
                          <label class="col-form-label col-sm-3 text-sm-right"><?=lang('password')?></label>
                          <div class="col-sm-9">
                            <input type="password" class="form-control" name="password" placeholder="<?=lang('input_password')?>">
                          </div>
                        </div>
                        <div class="text-center mt-3">
                          <button type="submit" class="btn btn-lg btn-primary"><?=lang('login')?></button>
                        </div>
                        <div class="form-group">
                          <a href="#"><?=lang('forgot_password')?></a><p>
                        </div>
                        <div class="form-group">
                          <a href="/index.php/auth/signup?mode=begin"><?=lang('request_sign_up')?></a><p>
                        </div>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
