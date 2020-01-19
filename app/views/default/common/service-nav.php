<?php
$c_post = get_post(value_get("id"));
$parent = get_post($c_post["parentid"]);
$posts = db_result("select * from posts where enabled=1 and parentid=:parentid order by postid limit 10",[
  "parentid"=>$parent["postid"]
]);
?>
<div class="">
  <div class="panel panel-default ">
    <div class="panel-heading">
      <a href="/host/detail?id=<?php echo $parent["postid"];?>">
      <?php echo $parent["title"];?>
      </a>
      <a href="/host/detail?id=<?php echo $parent["postid"];?>#service-add" class="btn btn-primary btn-xs right">
        <i class="fa fa-plus"></i> Add
      </a>
    </div>
    <div class="host-group-basic-nav collapse in panel-collapse ">
      <div class="list-group">
        <?php foreach($posts as $post){
          $meta_str = get_post_meta($post["postid"], "status",true);
          $meta = json_decode($meta_str, true);
          ?>
          <a href="/service?id=<?php echo $post["postid"];?>" class="list-group-item">
            <?php echo $post["title"];?>
            <lable class="badge status <?php echo value_var($meta, "status", "Unknown"); ?>">&nbsp;</lable>
          </a>

          <?php
        } ?>
      </div>
    </div>
  </div>
</div>
