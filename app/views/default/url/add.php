<div class="container-fluid">
  <div class="row pv-10">
    <div class="col-sm-3 ">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <div class="row">
        <div class="col-sm-6">
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
                <!-- <select class="form-control" disabled="true" name="service-type">
                <option value="tcp" data-target="#form-simple-monitoring" <?php echo ($url_type=="simple")?"selected":"";?>>Simple</option>
                <option value="udp" data-target="#form-keyword-monitoring" <?php echo ($url_type=="keyword")?"selected":"";?>>Keyword</option>
                <option value="url" data-target="#form-title-monitoring" <?php echo ($url_type=="title")?"selected":"";?>>Title</option>
              </select> -->
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
                <option value="60" selected="true">1 hour</option>
                <option value="360">6 hours</option>
                <option value="720">12 hours</option>
                <option value="1440">24 hours</option>
                <option value="manual" data-target="#form-manual-interval">Manual</option>
              </select>
            </div>
            <div class="form-group none" id="form-manual-interval">
              <label for="url">Manual Time</label>
              <input type="number" min="5" value="5" class="form-control" name="manual-interval" placeholder="type minutes">
              <p class="form-text text-muted">Must be greater then 5 minutes</p>
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
</div>
</div>
