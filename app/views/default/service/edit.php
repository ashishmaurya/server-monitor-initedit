<?php
$service = $data["service"];
extract($service);
$service_type = get_post_meta($postid, "service");

$downtime_type = get_post_meta($postid,"downtime-type",true,"");
$downtime_type_start = get_post_meta($postid,"start",true,"");
$downtime_type_end = get_post_meta($postid,"end",true,"");
$parentpost = get_post($parentid);
?>
<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3 ">
            <?php get_nav_view(); ?>
        </div>
        <div class="col-sm-6">
            <div class="pb-10">
                <a href="/host/detail?id=<?php echo $parentid; ?>">
                    <button class="btn btn-default">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        &nbsp;
                        back to host
                    </button>
                </a>
                <a href="/service/?id=<?php echo $postid; ?>" class="right">
                    <button class="btn btn-default">
                        view service &nbsp;
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>

                    </button>
                </a>
            </div>
            <form method="POST"
                  action="/posts/updateservice"
                  class="submit-jquery-form"
                  data-success="refreshConditional"
                  novalidate
                  >
                <input type="hidden" value="<?php echo $postid; ?>" name="id"/>
                <div class="form-group errorContainer">
                    <div class="value"></div>
                </div>
                
                <div class="form-group">
                    <label for="usr">
                        Host name/ IP Address
                    </label>
                    <input class="form-control" disabled="true" value="<?php echo value_var($parentpost, "title"); ?>"/>
                </div>
                <div class="form-group">
                    <label for="usr">Service Type</label>
                    <select class="form-control" disabled="true" name="service-type">
                        <option value="tcp" data-target="#form-tcp-monitoring" <?php echo ($service_type == "tcp") ? "selected" : ""; ?>>Port Monitoring(TCP)</option>
                        <option value="udp" data-target="#form-udp-monitoring" <?php echo ($service_type == "udp") ? "selected" : ""; ?>>Port Monitoring(UDP)</option>
                        <option value="url" data-target="#form-url-monitoring" <?php echo ($service_type == "url") ? "selected" : ""; ?>>Url Monitoring</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="usr">
                        <?php echo ($service_type == "tcp") ? "TCP Port Monitoring" : ""; ?>
                        <?php echo ($service_type == "udp") ? "UDP Port Monitoring" : ""; ?>
                        <?php echo ($service_type == "url") ? "URL Monitoring" : ""; ?>
                    </label>
                    <input class="form-control" disabled="true" value="<?php echo ($title); ?>"/>
                </div>


                <div class="form-group">
                    <label for="url">Interval Type</label>
                    <select class="form-control" name="interval">
                        <option value="5" <?php echo ($content == "5") ? "selected" : ""; ?>>5 min</option>
                        <option value="10" <?php echo ($content == "10") ? "selected" : ""; ?>>10 min</option>
                        <option value="15" <?php echo ($content == "15") ? "selected" : ""; ?>>15 min</option>
                        <option value="30" <?php echo ($content == "30") ? "selected" : ""; ?>>30 min</option>
                        <option value="60" <?php echo ($content == "60") ? "selected" : ""; ?>>1 hour</option>
                        <option value="360" <?php echo ($content == "360") ? "selected" : ""; ?>>6 hours</option>
                        <option value="720" <?php echo ($content == "720") ? "selected" : ""; ?>>12 hours</option>
                    </select>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <label>Enable notification</label>
                    </div>
                    <div class="col-sm-6 text-right">
                        <!-- Rounded switch -->
                        <label class="switch">
                            <input type="checkbox"
                            <?php echo get_post_meta($postid, "notification-enable", true, "true") == "true" ? "checked" : ""; ?>
                                   name="notification"/>
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <label for="url">Add Downtime</label>
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="form-control w-100 downtime-type" name="downtime-type">
                                <option value="none">---downtime type---</option>
                                <option value="manual" <?php echo ($downtime_type == "manual") ? "selected" : "" ?> data-target=".manual-form">Manual</option>
                                <option value="everyday" <?php echo ($downtime_type == "everyday") ? "selected" : "" ?>  data-target=".everyday-form">Everyday</option>
                            </select>
                        </div>
                        <div class="col-sm-8">

                            <div class="col-sm-12 manual-form <?php echo ($downtime_type == "manual") ? "" : "none"; ?>">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <input type="text"
                                               required="true"
                                               class="form-control datetime"
                                               placeholder="select start date and time"
                                               name="manual-start"
                                               value="<?php echo ($downtime_type == "manual") ? $downtime_type_start : ""; ?>"
                                               >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <input type="text"
                                               required="true"
                                               class="form-control datetime"
                                               name="manual-end"
                                               placeholder="select end date and time"
                                               value="<?php echo ($downtime_type == "manual") ? $downtime_type_end : ""; ?>"
                                               >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col-sm-12 <?php echo in_array($downtime_type, ["manual", "everyday"]) ? "" : "none"; ?>'>
                                        <button type="button" class="btn btn-default btn-xs right"
                                                onclick="$('.downtime-type').val('none').change();">
                                            cancel downtime
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 everyday-form <?php echo ($downtime_type == "everyday") ? "" : "none"; ?>">
                                <div class="row">
                                    <div class="form-group col-sm-6">
                                        <input type="text"
                                               required="true"
                                               class="form-control time"
                                               placeholder="select start time"
                                               name="everyday-start"
                                               value="<?php echo ($downtime_type == "everyday") ? $downtime_type_start : ""; ?>"
                                               >
                                    </div>
                                    <div class="form-group col-sm-6">
                                        <input type="text"  required="true" class="form-control time" name="everyday-end"
                                               placeholder="select end time"
                                               value="<?php echo ($downtime_type == "everyday") ? $downtime_type_end : ""; ?>"
                                               >
                                    </div>
                                </div>
                                <div class="row">
                                    <div class='col-sm-12 <?php echo in_array($downtime_type, ["manual", "everyday"]) ? "" : "none"; ?>'>
                                        <button type="button" class="btn btn-default btn-xs right"
                                                onclick="$('.downtime-type').val('none').change();">
                                            cancel downtime
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary lg" type="submit">
                        Update Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
