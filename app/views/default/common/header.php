<div class="header-site pv-10">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4">
                <a href="/">
                    <?php echo WEBSITE_NAME; ?>
                </a>
                <?php if (is_login()) { ?>
                    <i class="vertical-divider">|</i>
                    <a href="/dashboard">Dashboard</a>
                <?php } ?>
            </div>
            <div class="col-sm-8">
                <ul class="hl right">
                    <?php if (!is_login()) { ?>
                        <li>
                            <span><a href="/login" class="headerMenuItem"> login</a> <span class="vertical-divider">|</span> <a href="/signup" class="headerMenuItem">signup</a></span> </li>
                    <?php } else { ?>
                        <li><a href="/account" class="headerMenuItem">Hello, <span><?php echo SessionManagement::getSession("name"); ?></span></a></li>
                        <li class="vertical-divider">|</li>
                        <li onclick="logout()"><span class="logoutButton cursor">logout</span></li>
                        <li class="vertical-divider">|</li>
                        <?php $count = get_user_notification_count(current_userid()); ?>
                        <li onclick="showNotification()" class="cursor notification-count">
                            <i class="fa fa-bell"></i>
                            <span class="count <?php echo $count > 0 ? "show" : "hide"; ?>">
                                <?php echo ($count > 9) ? "9+" : $count; ?>
                            </span>
                        </li>
                    <?php } ?>
                    <li class="vertical-divider">|</li>
                </ul>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<div class="rightMessageBox" id="rightMessageBox">

</div>
<div class="notification-container " onclick="hideNotification()">
    <div class="notification prevent-propagation">
        <div class="notification-title bold">
            <a href="/account/notification"> Notification</a>
            <div class="right">
                <button class="btn btn-default btn-xs" onclick="hideNotification()">close</button>

                <?php
                if ($count > 0) {
                    ?>
                    <form method="POST"
                          action="/account/clearnotification"
                          data-success="clearedNotification"
                          class="submit-jquery-form left">

                        <button class="btn btn-default btn-xs clear-notification">
                            clear
                        </button>
                        &nbsp;
                        &nbsp;
                    </form>
                <?php } ?>
            </div>
        </div>
        <div class="notification-body scroll-bar">
            <div class="well text-center notification-empty <?php echo $count > 0 ? "hide" : "show"; ?>">
                Notification tray is empty<br/>
                <a href="/account/notification">see all</a>
            </div>
            <?php
            if ($count > 0) {
                $lastNotificationSeen = ((int) get_user_meta(current_userid(), "notification-last-seen", true, 0));
                $notifications = get_user_last_notification();

                foreach ($notifications as $notification) {
                    // $notification_json = $notification;
                    $notification_json = json_decode($notification, true);
                    ?>
                    <div class="notification-box <?php echo ($lastNotificationSeen < $notification_json["time"]) ? "unseen" : "seen" ?>">
                        <div class="title">
                            <?php echo $notification_json["title"]; ?>
                            <small class="right text-muted">
                                <?php echo time_elapsed_string($notification_json["time"]);?>
                            </small>
                        </div>
                        <div class="message text-muted">
                            <?php echo $notification_json["message"]; ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {

        if (window.location.hash.length > 0) {
            $("a[href='" + window.location.hash + "']").click();
        }
        $('[data-toggle="tooltip"]').tooltip({container: 'body'});

        $(".btn-group-select button").click(function () {
            var $parent = $(this).closest(".btn-group-select");
            $parent.find(".btn").removeClass("bg-primary").addClass("btn-default");
            $(this).removeClass("btn-default").addClass("bg-primary");
        });

        $(".js-data-select-ajax").each(function () {
            var placeholder = $(this).attr("data-placeholder");
            $(this).select2({
                width: "100%",
                // minimumInputLength: 3,
                ajax: {
                    url: $(this).attr("data-url"),
                    type: "POST",
                    dataType: 'json',
                    placeholder: placeholder,
                    delay: 250,
                    cache: true,
                    data: function (params) {
                        return {
                            q: params.term, // search term
                            page: params.page,
                            search: $(this).attr("data-type"),
                        };
                    },
                    error: handleAjaxError,
                    processResults: function (data, params) {
                        // parse the results into the format expected by Select2
                        // since we are using custom formatting functions we do not need to
                        // alter the remote JSON data, except to indicate that infinite
                        // scrolling can be used
                        params.page = params.page || 1;
                        log(data);
                        return {
                            results: data.items,
                            pagination: {
                                more: (params.page * 30) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                escapeMarkup: function (markup) {
                    return markup;
                }, // let our custom formatter work
                minimumInputLength: 0,
                templateResult: formatRepo, // omitted for brevity, see the source of this page
                templateSelection: formatRepoSelection // omitted for brevity, see the source of this page
            });

            function formatRepo(repo) {
                log(repo);
                if (repo.loading)
                    return repo.text;
                //
                // var markup = "<div class='select2-result-repository clearfix'>" +
                // "<div class='select2-result-repository__avatar'><img src='" + repo.owner.avatar_url + "' /></div>" +
                // "<div class='select2-result-repository__meta'>" +
                // "<div class='select2-result-repository__title'>" + repo.full_name + "</div>";
                var markup = repo.text;


                return markup;
            }

            function formatRepoSelection(repo) {
                return repo.full_name || repo.text;
            }

        });
    });
</script>
