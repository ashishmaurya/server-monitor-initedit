<?php
$user = get_user(current_userid());
extract($user);
?>
<div class="container-fluid">
  <div class="row pv-10">
    <div class="col-sm-3">
      <?php get_nav_view("account-nav") ?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-12">
          <h2>Current Plan : Free</h2>
        </div>
      </div>
      <?php
      $controller = new Controller();
      $controller->view("billing/plans");
       ?>
    </div>
  </div>
</div>
