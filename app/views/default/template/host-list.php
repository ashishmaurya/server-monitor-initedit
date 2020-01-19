<?php
$posts = $data["posts"];
$fav_list = get_user_meta(current_userid(), "host-fav", false, []);
if (count($posts) == 0) {
    echo "<h3 class='text-center'>Host list is empty</h3>";
    return;
}
?>
<table class="table table-bordered">
    <tr>
        <th>Sr No.</th>
        <th>Name</th>
        <th>Status</th>
        <th>Services</th>
        <th>Last Updated</th>
        <th class="min-width-150">Action</th>
    </tr>
    <?php
    $i = 1;
    foreach ($posts as $post) {
        // update_host_status($post);
        extract($post);
        $meta_str = get_post_meta($postid, "status", true);
        $meta = json_decode($meta_str, true);
        ?>
        <tr class="domain_<?php echo $postid; ?>">
            <td>
                <?php echo $i++; ?>
            </td>

            <td>
                <a href="/host/detail?id=<?php echo $postid; ?>">
                    <?php echo $title; ?>
                </a>
                <label class="badge">
                    <?php echo $content; ?>
                </label>
            </td>
            <td class="status <?php echo value_var($meta, "status", "Unknown"); ?>">
                <?php echo value_var($meta, "status", "Unknown"); ?>
            </td>
            <td>
                <a href="/host/detail?id=<?php echo $postid; ?>#service-list">
                    <?php echo get_post_parent_count($postid); ?>
                </a>
            </td>
            <td>
                <span title="<?php echo time_detailed(value_var($meta, "time", time())); ?>">
                    <?php echo time_elapsed_string(value_var($meta, "time", time())); ?><br/>
                    <small><?php echo time_detailed(value_var($meta, "time", time())); ?></small>
                </span>
            </td>
            <td>
                <div class="btn-group">
                    <form method="POST"
                          action="/posts/deletehost"
                          data-success="deletedwebsite"
                          data-confirm="confirmDeleteHost"
                          data-toggle="tooltip"
                          title="delete"
                          class="submit-jquery-form btn-group">
                        <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                        <button class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    <?php if (in_array($postid, $fav_list)) { ?>
                        <form method="POST"
                              action="/posts/unfav"
                              data-toggle="tooltip"
                              title="remove from favourite"
                              data-success="refreshConditional"
                              data-confirm="confirmDeleteHost"
                              class="submit-jquery-form btn-group">
                            <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                            <input type="hidden" name="key" value="host-fav"/>
                            <button class="btn btn-default">
                                <i class="fa fa-star enabled"></i>
                            </button>
                        </form>
                    <?php } else { ?>
                        <form method="POST"
                              action="/posts/fav"
                              data-success="refreshConditional"
                              class="submit-jquery-form btn-group"
                              data-toggle="tooltip"
                              title="favourite">
                            <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                            <input type="hidden" name="key" value="host-fav"/>
                            <button class="btn btn-default">
                                <i class="fa fa-star"></i>
                            </button>
                        </form>
                    <?php } ?>
                    <a class="btn btn-default"
                       data-toggle="tooltip"
                       title="edit"
                       href="/host/edit?id=<?php echo $postid; ?>"
                       >
                        <i class="fa fa-pencil"></i>
                    </a>
                    <a class="btn btn-default"
                       data-toggle="tooltip"
                       title="add service"
                       href="/host/detail?id=<?php echo $postid; ?>#service-add"
                       >
                        <i class="fa fa-plus"></i>
                    </a>
<!--                    <div class="dropdown btn-group">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                            <i class="fa fa-wrench"></i>
                            <span class="caret"></span></button>
                        <ul class="dropdown-menu">
                            <li><a href="#">HTML</a></li>
                            <li><a href="#">CSS</a></li>
                            <li><a href="#">JavaScript</a></li>
                        </ul>
                    </div>-->
                </div>
            </td>
        </tr>
        <?php
    }
    ?>


</table>
