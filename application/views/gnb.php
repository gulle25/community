<?php
defined('BASEPATH') OR exit('No direct script access allowed');

?>

  <?php if (ENVIRONMENT == 'development'): ?>
        <div class="alert alert-primary" role="alert">[Development]</div>
  <?php elseif (ENVIRONMENT == 'testing'): ?>
        <div class="alert alert-primary" role="alert">[Testing]</div>
  <?php endif; ?>

