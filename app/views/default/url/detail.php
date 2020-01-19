<?php
$post = $data["post"];
// $post_statues = db_select("select meta_value from posts_meta where meta_key='status' and postid=:postid order by metaid desc limit 20",[
//   "postid"=>$post["postid"],
// ]);
extract($post);
$meta_str = get_post_meta($postid, "status");
$lastupdated = get_post_meta($postid, "lastupdated");
if(empty($meta_str)){
  $meta_str = "{}";
  }
  $meta = json_decode($meta_str, true);
  $status= value_var($meta, "status", "Unknown");
  $fav_list = get_user_meta(current_userid(),"url-fav",false,[]);
  ?>

  <div class="container-fluid tab-content pv-10">
    <div class="row pv-10">

      <div class="col-sm-6">
        <div class="clearfix pb-10">
          <div class="btn-group">
            <a href="/url-monitoring/all" class="btn btn-default">
              <i class="fa fa-arrow-left"></i>
              All
            </a>
            <a href="/url-monitoring/add" class="btn btn-default">
              <i class="fa fa-plus"></i>
              Add
            </a>
          </div>
          <div class="right btn-group">

            <form method="POST"
            action="/posts/deleteurl"
            data-success="deletedurl"
            data-confirm="confirmDeleteService"
            class="submit-jquery-form btn-group"
            >
            <button class="btn btn-default">
              <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
              <i class="fa fa-trash"></i> Delete
            </button>
          </form>
          <?php if(in_array($postid,$fav_list)){ ?>
            <form method="POST"
            action="/posts/unfav"
            data-success="refreshConditional"
            data-confirm="confirmDeleteHost"
            class="submit-jquery-form btn-group">
            <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
            <input type="hidden" name="key" value="url-fav"/>
            <button class="btn btn-default enabled">
              <i class="fa fa-star enabled"></i> Unfav
            </button>
          </form>
        <?php }else {?>
          <form method="POST"
          action="/posts/fav"
          data-success="refreshConditional"
          class="submit-jquery-form btn-group">
          <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
          <input type="hidden" name="key" value="url-fav"/>
          <button class="btn btn-default">
            <i class="fa fa-star"></i> Fav
          </button>
        </form>
      <?php }?>
    </form>
    <a class="btn btn-default"
    href="/url-monitoring/edit?id=<?php echo $postid;?>"
    >
    <i class="fa fa-pencil"></i> &nbsp;
    edit
  </a>
<!--  <button class="btn btn-default" onclick="$('.page-preview-container').toggle();">
    <i class="fa fa-eye"></i> Preview

  </button>-->

</div>
</div>
<table class="table table-bordered">
  <tr>
    <td>Hostname</td>
    <td>
      <?php echo $post["title"];?>
    </td>
  </tr>
  <tr>
    <td>Host IP address</td>
    <!-- <td><?php echo $meta["ip"];?></td> -->
    <td><?php echo value_var($meta,"ip","---");?></td>
  </tr>
  <tr>
    <td>URL</td>
    <td><?php echo $post["title"];?></td>
  </tr>
  <tr>
    <td>Message</td>
    <!-- <td><?php echo $meta["message"];?></td> -->
    <td><?php echo value_var($meta,"message","---");?></td>
  </tr>
  <tr>
    <td>Error</td>
    <td><?php echo value_var($meta,"error","---");?></td>
  </tr>
  <tr>
    <td>Time Interval</td>
    <td><?php echo $content;?> minutes</td>
  </tr>
  <tr>
    <td>Status</td>
    <td class="status <?php echo $status;?>">
      <?php
      echo $status;
      ?>
    </td>
  </tr>
  <tr>
    <td>Response Time</td>
    <td><?php echo value_var($meta,"response_time","---");?></td>
    <!-- <td >
    <?php
    echo $meta["response_time"];

    ?>
    ms
  </td> -->
</tr>

<tr>
  <td>Time Created</td>

  <td>
    <?php echo time_detailed($post["time_created"]);?>
  </td>
</tr>
<tr>
  <td>Last Checked</td>
  <td>
    <?php
    $time = value_var($meta,"time");
    if($time){
      echo time_detailed($time);
    }else{
      echo "---";
    }
    ?>
  </td>
</tr>
<tr>
  <td>Next Update</td>
  <td class="time-updated"
  data-current="<?php echo time();?>"
  data-time="<?php echo ($time+((int)$content)*60);?>">
  <span class="value">--</span>
</td>
</tr>
<?php
$curl_options = value_var($meta,"curl_info");
if($curl_options){
  ?>

  <tr>
    <td>Content Type</td>
    <td>
      <?php echo $curl_options["content_type"];?>
    </td>
  </tr>
  <tr>
    <td>HTTP Code</td>
    <td>
      <?php echo $curl_options["http_code"];?>
    </td>
  </tr>
  <tr>
    <td>Header Size</td>
    <td>
      <?php echo $curl_options["header_size"];?>
    </td>
  </tr>
  <tr>
    <td>Download Size</td>
    <td>
      <?php echo $curl_options["size_download"];?>
    </td>
  </tr>
  <tr>
    <td>SSL Support</td>
    <td>
      <?php echo $curl_options["ssl_verify_result"]==0?"No":"Yes";?>
    </td>
  </tr>
  <?php
}
?>

</table>
</div>
<div class="col-sm-6">
  <?php
//  $html = value_var($meta,"http_response","");
//  $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
//  $html.="<script>
//  function prevent(event){event.preventDefault();}
//  document.getElementsByTagName('body')[0].setAttribute('onclick','prevent(event)');
//  </script>";
//  $html = str_replace("'",'"',$html);
  ?>
<!--  <div class='page-preview-container none'>
    <h4>
      <span class="left">Preview</span>
      <div class="right btn-group btn-preview-group btn-group-select pb-10">
        <button class="btn btn-sm bg-primary"
        data-toggle="tooltip"
        title="desktop"
        data-class="desktop">
        <i class="fa fa-desktop"></i>
      </button>
      <button class="btn btn-default btn-sm"
      data-toggle="tooltip"
      title="tablet"
      data-class="tablet">
      <i class="fa fa-tablet"></i>
    </button>
    <button class="btn btn-default btn-sm"
    data-toggle="tooltip"
    title="mobile"
    data-class="mobile">
    <i class="fa fa-mobile"></i>
  </button>
</div>
</h4>
<iframe class="scroll-bar page-preview desktop" disabled="true" srcdoc='<?php echo $html;?>'>

</iframe>
</div>-->
<?php
if($meta_str=="{}"){
  ?>
  <div class="jumbotron">
    <h1>No data found</h1>
    <p>Looks like you have just added url.</p>
  </div>
  <?php
}else{
  if($curl_options){
    $meta["curl_info"]["local_ip"]="Private";
    $meta["curl_info"]["local_port"]="Private";
  }
  unset($meta["http_response"]);
  var_dump($meta);
}
?>
</div>
</div>
</div>
<script>
$(document).ready(function(){
  $(".btn-preview-group button").click(function(){
    var cls = $(this).attr("data-class");
    $('.page-preview').removeClass('tablet desktop mobile');
    $(".page-preview").addClass(cls);
  })
});
</script>
