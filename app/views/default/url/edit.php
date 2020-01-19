<?php
$post =$data["post"];
extract($post);
$url_type = get_post_meta($postid,"type",true,"simple");
$url_type_value = get_post_meta($postid,"url-type-value",true);

?>
<div class="container-fluid">
  <div class="row pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-6">
          <div class="panel panel-default">
            <div class="panel-heading">Edit URL</div>
            <div class="panel-body">
              <form method="POST"
              action="/posts/updateurl"
              class="submit-jquery-form"
              data-success="refreshConditional">
              <input type="hidden" value="<?php echo $postid;?>" name="id"/>
              <div class="form-group errorContainer">
                <div class="value"></div>
              </div>
              <div class="form-group">
                <label for="usr">URL</label>

                <input type="url" placeholder="type url"
                value="<?php echo $title;?>"
                disabled="true"
                data-required="true"
                data-empty="URL is required"
                data-msg="Invalid URL"
                class="form-control" name="name">
              </div>
              <div class="form-group">
                <label for="usr">Monitoring Type</label>
                <select class="form-control" disabled="true" name="url-type">
                  <option value="tcp" data-target="#form-simple-monitoring" <?php echo ($url_type=="simple")?"selected":"";?>>Simple</option>
                  <option value="udp" data-target="#form-keyword-monitoring" <?php echo ($url_type=="keyword")?"selected":"";?>>Keyword</option>
                  <option value="url" data-target="#form-title-monitoring" <?php echo ($url_type=="title")?"selected":"";?>>Title</option>
                </select>

            </div>
            <div class="form-group <?php echo ($url_type=="keyword")?"":"none";?>" id="form-keyword-monitoring">
              <label for="url">Keyword</label>
              <input type="text" placeholder="type keyword"
              value="<?php echo $url_type_value;?>"
              class="form-control" name="url-keyword">
            </div>
            <div class="form-group <?php echo ($url_type=="title")?"":"none";?>" id="form-title-monitoring">
              <label for="url">Title Contains</label>
              <input type="text" placeholder="type title"
              value="<?php echo $url_type_value;?>"
              class="form-control" name="url-title">
            </div>
            <div class="form-group">
              <label for="url">Interval Type</label>
              <select class="form-control" name="interval">
                <option value="5" <?php echo $content=="5"?"selected":"";?>>5 min</option>
                <option value="10" <?php echo $content=="10"?"selected":"";?>>10 min</option>
                <option value="15" <?php echo $content=="15"?"selected":"";?>>15 min</option>
                <option value="30" <?php echo $content=="30"?"selected":"";?>>30 min</option>
                <option value="60" <?php echo $content=="60"?"selected":"";?>>1 hour</option>
                <option value="360" <?php echo $content=="360"?"selected":"";?>>6 hours</option>
                <option value="720" <?php echo $content=="720"?"selected":"";?>>12 hours</option>
                <option value="1440" <?php echo $content=="1440"?"selected":"";?>>24 hours</option>
                <option value="manual"
                 <?php echo !in_array($content,[5,10,15,30,60,360,720,1440])?"selected":"";?>
                  data-target="#form-manual-interval">Manual</option>
              </select>
            </div>
            <div class="form-group <?php echo in_array($content,[5,10,15,30,60,360,720,1440])?"none":"";?>" id="form-manual-interval">
              <label for="url">Manual Time</label>
              <input type="number" min="5" value="<?php echo $content;?>" class="form-control" name="manual-interval" placeholder="type minutes">
              <p class="form-text text-muted">Must be greater then 5 minutes</p>
            </div>
            <div class="form-group">
              <button class="btn btn-primary lg" type="submit">
                Update URL
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
