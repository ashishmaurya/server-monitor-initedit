<?php
$status = value_get("status");
?>
<div class="container-fluid">
  <div class="row  pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-4 pb-10">
          <div class="host-name">
            All URL's &nbsp;&nbsp;
            
            <a href="/url-monitoring/add"
            class="btn btn-primary btn-sm">
            Add URL
          </a>
<!--            
            <button data-toggle="collapse"
            data-target=".add-url-row"
            class="btn btn-primary btn-sm">
            Add URL
          </button>-->
        </div>
      </div>
      <div class="col-sm-8 pb-10">
        <div class="btn-group right">
          <a href="/url-monitoring/all?status=Okay"
           class="btn  <?php echo $status=="Okay"?"bg-primary":"btn-default";?>">Okay</a>
          <a href="/url-monitoring/all?status=Warn"
           class="btn  <?php echo $status=="Warn"?"bg-primary":"btn-default";?>">Warn</a>
          <a href="/url-monitoring/all?status=Critical"
           class="btn  <?php echo $status=="Critical"?"bg-primary":"btn-default";?>">Critical</a>
        </div>
      </div>
    </div>
    <div class="row none add-url-row">
      <div class="col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">Add URL</div>
          <div class="panel-body">
            <form method="POST"
            action="/posts/addurl"
            class="submit-jquery-form"
            data-success="refreshConditional">
            <div class="form-group errorContainer">
              <div class="value"></div>
            </div>
            <div class="form-group">
              <label for="usr">URL</label>
              <input type="url" placeholder="type url"
              value="https://"
              data-required="true"
              data-empty="URL is required"
              data-msg="Invalid URL"
              class="form-control" name="name">
            </div>
            <div class="form-group">
              <label for="usr">Monitoring Type</label>
              <select class="form-control" name="url-type">
                  <option value="simple" data-target="#form-simple-monitoring">Simple</option>
                  <option value="keyword" data-target="#form-keyword-monitoring">Keyword</option>
                  <option value="title" data-target="#form-title-monitoring" >Title</option>
              </select>
            </div>
            <div class="form-group none" id="form-keyword-monitoring">
              <label for="url">Keyword</label>
              <input type="text" placeholder="type keyword"
              class="form-control" name="url-keyword">
            </div>
            <div class="form-group none" id="form-title-monitoring">
              <label for="url">Title Contains</label>
              <input type="text" placeholder="type title"
              class="form-control" name="url-title">
            </div>

            <div class="form-group">
              <label for="url">Interval Type</label>
              <select class="form-control" name="interval">
                <option value="5">5 min</option>
                <option value="10">10 min</option>
                <option value="15">15 min</option>
                <option value="30">30 min</option>
                <option value="60">1 hour</option>
                <option value="360">6 hours</option>
                <option value="720">12 hours</option>
                <option value="1440">24 hours</option>
              </select>
            </div>
            <div class="form-group">
              <button class="btn btn-primary lg" type="submit">
                Add URL
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <?php
      $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;

      if(empty($status)){
        $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid";
        $params = [
          "enabled"=>1,
          "userid"=>current_userid(),
          "post_type"=>'url-monitoring'
        ];
      }else{
        $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid and postid in (
          select postid from posts_meta where meta_value=:meta_value
          )";
        $params = [
          "enabled"=>1,
          "userid"=>current_userid(),
          "post_type"=>'url-monitoring',
          "meta_value"=>$status
        ];

      }

      $posts = db_result("$base_query order by postid $limit",$params);
      get_post_view($posts,"url-list");

      $total = db_single("select count(*) as c from ($base_query) as dummy",$params)["c"];
      get_pagination($total);

      // $posts = db_select("posts", [
      //   "post_type" => "url-monitoring",
      //   "userid" => current_userid()
      // ]);
      // get_post_view($posts,"url-list");
      ?>
    </div>
  </div>
</div>
</div>
</div>
