<?php
$posts = $data["posts"];
$fav_list = get_user_meta(current_userid(), "host-group-fav", false, []);
if (count($posts) == 0) {
    echo "<h3 class='text-center'>Host Group list is empty</h3>";
    return;
}
?>
<table class="table table-bordered">
    <tr>
        <th>Sr No.</th>
        <th>Group Name</th>
        <th>Hosts</th>
        <th>Time</th>
        <th class="min-width-150">Action</th>
    </tr>
    <?php
    $i = 1;
    foreach ($posts as $post) {
        extract($post);
        ?>
        <tr class="domain_<?php echo $postid; ?>">
            <td>
                <?php echo $i++; ?>
            </td>

            <td>
                <a href="/host-group/detail?id=<?php echo $postid; ?>">
                    <?php echo $title; ?>
                    <br/>
                </a>
            </td>
            <td>
                <?php echo get_post_parent_count($postid); ?>
            </td>
            <td>
                <?php echo time_detailed($time_created); ?>
            </td>
            <td>
                <div class="btn-group">
                    <form method="POST"
                          action="/posts/deletehostgroup"
                          data-success="deletedwebsite"
                          data-confirm="confirmDeleteHost"
                          class="submit-jquery-form btn-group">
                        <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                        <button class="btn btn-default">
                            <i class="fa fa-trash"></i>
                        </button>
                    </form>
                    <?php if (in_array($postid, $fav_list)) { ?>
                        <form method="POST"
                              action="/posts/unfav"
                              data-success="refresh"
                              data-confirm="confirmDeleteHost"
                              class="submit-jquery-form btn-group">
                            <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                            <input type="hidden" name="key" value="host-group-fav"/>
                            <button class="btn btn-default">
                                <i class="fa fa-star enabled"></i>
                            </button>
                        </form>
                    <?php } else { ?>
                        <form method="POST"
                              action="/posts/fav"
                              data-success="refresh"
                              class="submit-jquery-form btn-group">
                            <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                            <input type="hidden" name="key" value="host-group-fav"/>
                            <button class="btn btn-default">
                                <i class="fa fa-star"></i>
                            </button>
                        </form>
                    <?php } ?>
                    <?php // if ($title != "default-group") { ?>
                        <a class="btn btn-default" href="/host-group/edit?id=<?php echo $postid; ?>">
                            <i class="fa fa-pencil"></i>
                        </a>
                    <?php // } ?>
                </div>
            </td>
        </tr>
        <?php
    }
    ?>


</table>
