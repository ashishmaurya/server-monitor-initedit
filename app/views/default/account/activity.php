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
        <div class="col-sm-8 account-activity">
          <h2>Activity</h2>
          <?php
          $lastactivitySeen = 0;
          $activitys = get_user_last_activity(get_current_page());
          foreach($activitys as $activity){
            $activity_json = json_decode($activity,true);
            ?>
            <div class="activity-box <?php echo ($lastactivitySeen<$activity_json["time"])?"unseen":"seen"?>">
              <div class="title">
                <?php echo $activity_json["title"];?>
              </div>
              <div class="message">
                <?php echo $activity_json["message"];?>
              </div>
            </div>
            <?php
          }
          ?>
        </div>
        <div class="col-sm-8">
          <?php

          $db_count = db_count("users_meta",[
            "meta_key"=>"activity",
            "userid"=>current_userid(),
          ]);
          get_pagination($db_count);
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
