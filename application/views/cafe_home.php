<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

        <main>
          <!-- Flash Message -->
    <?php if ($this->session->flashdata('message')): ?>
          <div class="alert alert-primary" role="alert">
            <div class="alert-message">
              <?php echo $this->session->flashdata('message')['message']; ?>
              <?php $this->session->set_flashdata('message'); ?>
            </div>
          </div>
    <?php endif; ?>

          <div class="clearfix">
            <form class="form-inline float-right mt--1 d-none d-md-flex">
              <button class="btn btn-primary"><i class="align-middle" data-feather="plus"></i> New project</button>
            </form>
            <h1 class="h3 mb-3">apart home</h1>
          </div>
          <a href="/index.php/auth/logout"><button id="logout" class="ui button">Logout</button></a>

        </main>
