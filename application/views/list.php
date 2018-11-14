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

          <div class="card-body p-0" id="scroll">
          </div>
        </main>

<script type="text/javascript">
  var timer;
  var running = false;

  $(document).ready(function() {
    append_list();

    $(window).scroll(function() {
      // if (timer) {
      //   clearTimeout(timer);
      // }

      if (running) {
        return;
      }
      running = true;

      var dh = $(document).height();
      var wh = $(window).height();
      var wt = $(window).scrollTop();
      if (dh - 500 < (wh + wt)) {
        append_list();
      }

      // timer = setTimeout(function() { }, 1);

      running = false;
    })
  });

  var first_page = true;

  function append_list() {
    var ownerno = 99999999;
    var sequence = 0;
    var list = $("#scroll");
    var last = list.children().last();

    if (first_page) {
      first_page = false;
    } else {
      ownerno = last.attr("ono");
      sequence = last.attr("seq");
    }

    var url = "/index.php/apart/api_get_list/<?=$info->cafeid?>/<?=$info->boardid?>/" + ownerno + "/" + sequence;
    alert(url);
    $.getJSON(url, function(json) {
      $.each(json, function() {
        list.append('<div cno="' + this["cno"] + '" ono="' + this["ono"] + '" seq="' + this["seq"] + '">' + this["title"] + '</div>');
      });
    });
  }
</script>
