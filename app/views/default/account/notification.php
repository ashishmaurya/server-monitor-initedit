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
                <div class="col-sm-8 account-notification">
                    <h2>Notification</h2>
                    <?php
                    $lastNotificationSeen = ((int) get_user_meta(current_userid(), "notification-last-seen", true, 0));
                    $notifications = get_user_last_notification(get_current_page());
                    foreach ($notifications as $notification) {
                        // $notification_json = $notification;
                        $notification_json = json_decode($notification, true);
                        ?>
                        <div class="notification-box <?php echo ($lastNotificationSeen < $notification_json["time"]) ? "unseen" : "seen" ?>">
                            <div class="title">
                                <?php echo $notification_json["title"]; ?>
                                <small class="right text-muted" title="<?php echo time_detailed($notification_json["time"]);?>">
                                    <?php echo time_elapsed_string($notification_json["time"]); ?>
                                </small>
                            </div>
                            <div class="message">
                                <?php echo $notification_json["message"]; ?>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <div class="col-sm-8">
                    <?php
                    $db_count = db_count("users_meta", [
                        "meta_key" => "notification",
                        "userid" => current_userid(),
                    ]);
                    get_pagination($db_count);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
