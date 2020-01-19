<div class="container-fluid">
  <div class="row pv-10">
    <div class="col-sm-3">
      <?php get_nav_view();?>
    </div>
    <div class="col-sm-9">
      <form method="POST"
      action="/search/ashql"
      class="submit-jquery-form"
      data-success="searchResult"
      data-process="false">
      <div class="errorContainer">
        <div class="value"></div>
      </div>
      <div class="form-group">
        <input type="text"
        autofocus="true"
        placeholder="search query" .
        class="form-control" name="s">
      <div class="help-text text-muted">
        type:host/url/service/group
        &nbsp;&nbsp;&nbsp;&nbsp;
        status:okay/critical/warn
      </div>
      </div>
      <div class="form-group none">
        <div class="btn-group btn-group-select select-type">
          <button type="button"  class="btn btn-default">Host</button>
          <button type="button"  class="btn btn-default">Host Group</button>
          <button type="button"  class="btn btn-default">Service</button>
          <button type="button"  class="btn btn-default">URL</button>
          <button  type="button" class="btn btn-default">Clear</button>
        </div>
        <div class="btn-group btn-group-select right select-status">
          <button  type="button" class="btn btn-default">Okay</button>
          <button  type="button" class="btn btn-default">Warn</button>
          <button  type="button" class="btn btn-default">Critical</button>
          <button  type="button" class="btn btn-default">Unknown</button>
        </div>
      </div>
    </form>
    <div class="search-result">

    </div>
    </div>
  </div>
</div>
