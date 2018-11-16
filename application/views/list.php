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
  var list_fetch_running = false;
  var all_list_fetched = false;
  var first_page = true;

  $(document).ready(function() {
    append_list();

    $(window).scroll(function() {
      if (list_fetch_running || all_list_fetched) return;

      var dh = $(document).height();
      var wh = $(window).height();
      var wt = $(window).scrollTop();
      if (dh - <?=SCROLL_BUFFER?> < (wh + wt)) {
        append_list();
      }
    })
  });

  function append_list() {
    list_fetch_running = true;

    var list = $("#scroll");
    var last = list.children().last();
    var last_ownerno = 99999999;
    var last_sequence = 0;

    if (first_page) {
      first_page = false;
    } else {
      last_ownerno = last.attr("ono");
      last_sequence = last.attr("seq");
    }

    var url = "/index.php/apart/api_content_list/<?=$info->cafeid?>/<?=$info->boardid?>/" + last_ownerno + "/" + last_sequence + "/none/_";
    // alert(url);
    $.getJSON(url, function(json) {
      $.each(json, function() {
        if (this[1] > 0) {
          list.append('<div cno="' + this[1] + '" ono="' + this[2] + '" seq="' + this[3] + '">' + this[0] + '.' + this[1] + '.' + this[6] + '</div>');
        } else {
          list.children().last().attr('last', 1);
          all_list_fetched = true;
        }
      });
      list_fetch_running = false;
    });
  }
</script>
