<?php
$posts = $data["posts"];
if(count($posts)==0){
  echo "<h3 class='text-center'>Service list is empty</h3>";
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
    // update_service_status($post,$parentpost);
    extract($post);
    $meta_str = get_post_meta($postid, "status");
    $lastupdated = get_post_meta($postid, "lastupdated");
    $service_type = get_post_meta($postid,"service");
    $meta = json_decode($meta_str, true);
    ?>
    <tr class="service_<?php echo $postid; ?>">
      <td>
        <?php echo $i++; ?>
      </td>

      <td>
        <a href="/service?id=<?php echo $postid;?>">

          <?php
          if($service_type=="tcp" || $service_type=="udp"){
            echo $service_type.":";
          }
           echo $title;
           ?>
          <br/>
        </a>
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

        if($service_type=="tcp" || $service_type=="udp"){
          $status= value_var($meta, "status", "Unknown");
          $is_port_open= value_var($meta, "port_open", false);
          // echo "(".value_var($meta,"ip").")";
          echo value_var($meta,"message");

        }else{
          echo value_var($meta,"message");
          // $error = value_var($meta,"error");
          // echo empty($error)?"":"(".$error.")";
          // echo value_var($meta,"http_response");
          // $curl_info = (value_var($meta,"curl_info"));
          // echo
          // print_r(value_var($meta,"curl_info"));
        }
        ?>
      </td>
      <td>
        <?php echo time_elapsed_string($lastupdated)." - ".time_detailed($lastupdated); ?>
      </td>
      <td>
        <div class="btn-group">
        <form method="POST"
        action="/posts/deleteservice"
        data-success="deletedservice"
        data-confirm="confirmDeleteService"
        class="submit-jquery-form btn-group">
        <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
        <button class="btn btn-default">
          <i class="fa fa-trash"></i>
        </button>
      </form>
      <a class="btn btn-default"
          href="/service/edit?id=<?php echo $postid;?>"
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
