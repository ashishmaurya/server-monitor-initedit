<?php
$posts = $data["posts"];
if(count($posts)==0){
  echo "<h3 class='text-center'>URL list is empty</h3>";
  return;
}
$parentpost = get_post($posts[0]["parentid"]);
?>
<table class="table table-bordered">
  <tr>
    <th>Sr No.</th>
    <th>Name</th>
    <th>Status</th>
    <th>Message</th>
    <th>Time</th>
    <th>Action</th>
  </tr>
  <?php
  $i = 1;
  foreach ($posts as $post) {
    // update_url_status($post);
    extract($post);
    $meta_str = get_post_meta($postid, "status");
    $lastupdated = get_post_meta($postid, "lastupdated");
    $url_type = get_post_meta($postid,"type");
    $meta = json_decode($meta_str, true);
    ?>
    <tr class="url-monitoring_<?php echo $postid; ?>">
      <td>
        <?php echo $i++; ?>
      </td>
      <td>
        <a href="/url-monitoring/detail?id=<?php echo $postid;?>">
          <?php
          echo $title;
          ?>
        </a>
        <label class="badge">
          <?php echo $url_type;?>
        </label>
      </td>
      <?php
      $status= value_var($meta, "status", "Unknown");
      ?>
      <td class="status <?php echo $status;?>">
        <?php
        echo $status;
        ?>
      </td>
      <td class="max-width-200">
        <?php
        echo value_var($meta,"message");
        ?>
      </td>
      <td>
        <?php echo time_detailed($lastupdated); ?>
      </td>
      <td>
        <div class="btn-group">
          <form method="POST"
          action="/posts/deleteurl"
          data-success="deletedurl"
          data-confirm="confirmDeleteService"
          class="submit-jquery-form btn-group">
          <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
          <button class="btn btn-default">
            <i class="fa fa-trash"></i>
          </button>
        </form>
        <a class="btn btn-default"
        href="/url-monitoring/edit?id=<?php echo $postid;?>"
        >
        <i class="fa fa-pencil"></i>
      </a>
    </div>
  </td>
</tr>
<?php
}
?>
</table>
