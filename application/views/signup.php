<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <main>
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

              <?php echo form_open('http://' . $_SERVER['HTTP_HOST'] . '/index.php/auth/signup'); ?>

                        <input type="hidden" name="mode" value="apply">

                        <div class="form-group">
                          <label for="name"><?=lang('name')?></label>
                          <?php if (isset($this->session->userdata('signup')->name_proved)): ?>
                            <?=$this->session->userdata('signup')->name?>
                          <?php else: ?>
                            <input type="name" class="form-control" id="name" name="name" value="<?php echo set_value('name'); ?>" placeholder="<?=lang('input_name')?>">
                        </div>
                          <div class="form-group">
                            <label for="residence_num"><?=lang('residence_num')?></label>
                            <input type="text" class="" id="residence_num1" name="residence_num1" placeholder="<?=lang('input_residence_num1')?>"> -
                            <input type="password" class="" id="residence_num2" name="residence_num2" placeholder="<?=lang('input_residence_num2')?>">
                            <input type="button" class="btn" value="<?=lang('prove_name')?>">
                          </div>
                          <?php endif; ?>
                        <div class="form-group">
                          <label for="email"><?=lang('email')?></label>
                          <?php if (isset($this->session->userdata('signup')->email_proved)): ?>
                            <?=$this->session->userdata('signup')->email?>
                          <?php else: ?>
                            <input type="email" class="" id="email" name="email" value="<?php echo set_value('email'); ?>" placeholder="<?=lang('input_email')?>">
                            <input type="button" class="btn" value="<?=lang('send_email_auth')?>">
                          </div>
                          <div class="form-group">
                            <input type="text" class="" id="prove_email" name="prove_email" placeholder="<?=lang('input_email_auth')?>">
                            <input type="button" class="btn" value="<?=lang('prove_email')?>">
                          <?php endif; ?>
                        </div>
                        <div class="form-group">
                          <label for="phone"><?=lang('phone')?></label>
                          <?php if (isset($this->session->userdata('signup')->phone_proved)): ?>
                            <?=$this->session->userdata('signup')->phone?>
                          <?php else: ?>
                          <input type="text" class="" id="phone" name="phone" value="<?php echo set_value('phone'); ?>" placeholder="<?=lang('input_phone')?>">
                          <input type="button" class="btn" value="<?=lang('send_phone_auth')?>">
                        </div>
                        <div class="form-group">
                          <input type="text" class="" id="prove_phone" name="prove_phone" placeholder="<?=lang('input_phone_auth')?>">
                          <input type="button" class="btn" value="<?=lang('prove_phone')?>">
                          <?php endif; ?>
                        </div>
                          <?php if (!isset($this->session->userdata('signup')->password_proved)): ?>
                          <div class="form-group">
                            <label for="password"><?=lang('password')?></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="<?=lang('input_password')?>">
                          </div>
                          <div class="form-group">
                            <label for="re_password"><?=lang('re_password')?></label>
                            <input type="password" class="form-control" id="re_password" name="re_password" placeholder="<?=lang('input_re_password')?>">
                          </div>
                          <a href="/"><label class="btn"><?=lang('cancel')?></label></a>
                          <button type="submit" class="btn btn-default"><?=lang('sign_up')?></button>
                          <?php else: ?>
                          <a href="/"><label class="btn"><?=lang('login')?></label></a>
                          <?php endif; ?>
                      </form>

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </main>
