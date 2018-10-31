<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

    <div class="ui sidebar inverted vertical menu">
  <!--       <a class="item">
        <i class="home icon"></i>
        Home
      </a>
      <a class="item">
        <i class="block layout icon"></i>
        Topics
      </a>
      <a class="item">
        <i class="smile icon"></i>
        Friends
      </a>
  -->

  <?php foreach ($sidebar as $item): ?>
  <?php switch ($item->type):
    case 'text': ?>
      <i class="<?=$item->class?>"><?=$item->value?></i>
  <?php break; ?>
  <?php case 'text_link': ?>
      <a class="<?=$item->class?>"><?=$item->value?></a>
  <?php break; ?>
  <?php endswitch; ?>
  <?php endforeach; ?>
    </div>

    <div id="page-content-wrapper" class="dimmed pusher">
  <?php if (ENVIRONMENT == 'development'): ?>
      <div class="alert alert-primary" role="alert">[Development]</div>
  <?php elseif (ENVIRONMENT == 'testing'): ?>
      <div class="alert alert-primary" role="alert">[Testing]</div>
  <?php endif; ?>
