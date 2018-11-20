<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!--   <div class="wrapper">
    <div class="d-flex">
      <nav class="sidebar sidebar-sticky">
        <div class="sidebar-content">
          <a class="sidebar-brand" href="/">
            <i class="align-middle" data-feather="home"></i>
            <span class="align-middle">Home</span>
          </a>
          <ul class="sidebar-nav">
        <?php $item_cnt = 0; ?>
        <?php foreach ($sidebar as $item): ?>
        <?php $item_cnt++; ?>
        <?php switch ($item->type):
          case 'text': ?>
            <li class="sidebar-item" id="item_<?=$item_cnt?>">
              <a href="/" class="sidebar-link">
                <i class="align-middle <?=$item->class?>" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
              </a>
            </li>
        <?php break; ?>
        <?php case 'text_link': ?>
            <li class="sidebar-item" id="item_<?=$item_cnt?>">
              <a href="<?=$item->link?>" class="sidebar-link">
                <i class="align-middle <?=$item->class?>" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
              </a>
            </li>
        <?php break; ?>
        <?php case 'item_group': ?>
            <li class="sidebar-item" id="item_<?=$item_cnt?>">
              <a href="#<?=$item->groupid?>" data-toggle="collapse" class="sidebar-link <?=$item->expand ? '' : 'collapsed'?>" aria-expanded="<?=$item->expand ? 'true' : 'false'?>">
                <i class="align-middle" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
              </a>
              <ul id="<?=$item->groupid?>" class="sidebar-dropdown list-unstyled collapse <?=$item->expand ? 'show' : ''?>">
        <?php break; ?>
        <?php case 'group_link': ?>
              <li class="sidebar-item" id="item_<?=$item_cnt?>">
                <a class="sidebar-link" href="<?=$item->link?>"><?=$item->value?></a>
              </li>
        <?php break; ?>
        <?php case 'group_end': ?>
              </ul>
            </li>
        <?php break; ?>
        <?php endswitch; ?>
        <?php endforeach; ?>
          </ul>
        </div>
      </nav>
 -->
  <section>
    <nav class="my-sidebar-small-hide" id="sidebar">
      <a class="sidebar-brand" href="/">
        <i class="align-middle" data-feather="home"></i>
        <span class="align-middle">Home</span>
      </a>
      <ul class="sidebar-nav">
    <?php $item_cnt = 0; ?>
    <?php foreach ($sidebar as $item): ?>
    <?php $item_cnt++; ?>
    <?php switch ($item->type):
      case 'text': ?>
        <li class="sidebar-item" id="item_<?=$item_cnt?>">
          <a href="/" class="sidebar-link">
            <i class="align-middle <?=$item->class?>" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
            <!-- <span class="sidebar-badge badge badge-primary">6</span> -->
          </a>
        </li>
    <?php break; ?>
    <?php case 'text_link': ?>
        <li class="sidebar-item" id="item_<?=$item_cnt?>">
          <a href="<?=$item->link?>" class="sidebar-link">
            <i class="align-middle <?=$item->class?>" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
          </a>
        </li>
    <?php break; ?>
    <?php case 'item_group': ?>
        <li class="sidebar-item" id="item_<?=$item_cnt?>">
          <a href="#<?=$item->groupid?>" data-toggle="collapse" class="sidebar-link <?=$item->expand ? '' : 'collapsed'?>" aria-expanded="<?=$item->expand ? 'true' : 'false'?>">
            <i class="align-middle" data-feather="<?=$item->feather?>"></i> <span class="align-middle"><?=$item->value?></span>
          </a>
          <ul id="<?=$item->groupid?>" class="sidebar-dropdown list-unstyled collapse <?=$item->expand ? 'show' : ''?>">
    <?php break; ?>
    <?php case 'group_link': ?>
          <li class="sidebar-item" id="item_<?=$item_cnt?>">
            <a class="sidebar-link" href="<?=$item->link?>"><?=$item->value?></a>
          </li>
    <?php break; ?>
    <?php case 'group_end': ?>
          </ul>
        </li>
    <?php break; ?>
    <?php endswitch; ?>
    <?php endforeach; ?>
      </ul>
    </nav>
