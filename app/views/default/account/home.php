<?php
$user = get_user(current_userid());
extract($user);
?>
<div class="container-fluid">
  <div class="row pv-10">
    <div class="col-sm-3">
      <?php get_nav_view("account-nav"); ?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-6">
          <div class="form-group">
            <label for="usr">Name</label>
            <input type="text"
            disabled="true"
            value="<?php echo $name;?>"
            class="form-control" />
          </div>
          <div class="form-group">
            <label for="usr">Email</label>
            <input type="text"
            disabled="true"
            value="<?php echo $email;?>"
            class="form-control" />
          </div>

          <div class="form-group">
            <label for="usr">Account Created</label>
            <input type="text"
            disabled="true"
            value="<?php echo time_detailed(strtotime($time));?>"
            class="form-control" />
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
