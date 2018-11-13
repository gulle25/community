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

          <div class="card-body">
            <ul id="scroll">
              <li>Text</li>
              <li>Text</li>

  <!--             <div class="row">
                <span class="col-sm-6" id="line1">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
              <div class="row">
                <span class="col-sm-6">Text</span>
              </div>
 -->
            </ul>
          </div>
        </main>

<script type="text/javascript">
  $(function(e) {
    append_list();

    $(window).scroll(function() {
      var dh = $(document).height();
      var wh = $(window).height();
      var wt = $(window).scrollTop();
      if (dh - 2000 < (wh + wt)) {
        append_list();
      }
    })
  });

  var start = 0;
  var lsit = 5;
  function append_list() {
    $.get("/index.php/apart/get_list/ab93f/sdf/5/34", function(data) {
      $("#scroll").append(data);
      start += list;
    });
  }
</script>
