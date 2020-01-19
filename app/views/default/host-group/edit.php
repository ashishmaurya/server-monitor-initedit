<?php
$post = $data["post"];
extract($post);
$downtime_type = get_post_meta($postid,"downtime-type",true,"");
$downtime_type_start = get_post_meta($postid,"start",true,"");
$downtime_type_end = get_post_meta($postid,"end",true,"");
?>
<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view(); ?>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <form method="POST"
                          action="/posts/updatehostgroup"
                          class="submit-jquery-form"
                          data-success="refreshConditional"
                          novalidate
                          >
                        <input type="hidden" value="<?php echo $postid; ?>" name="id"/>
                        <div class="form-group errorContainer">
                            <div class="value"></div>
                        </div>
                        <div class="form-group">
                            <label for="usr">Host Group Name</label>
                            <input type="hidden" name="name" value="<?php echo $title;?>"/>
                            <input type="text" placeholder="type group name"
                                   value="<?php echo $title; ?>" required="true"
                                   <?php echo $title=="default-group"?"disabled":""; ?>
                                   data-required="true"
                                   data-empty="Host Group is required"
                                   data-msg="Invalid Host Group"
                                   class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label for="usr">Description</label>
                            <textarea
                                placeholder="write about this group"
                                class="form-control resize-none" 
                                rows="5"
                                name="description"><?php echo $content; ?></textarea>
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
                                Update Host Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
