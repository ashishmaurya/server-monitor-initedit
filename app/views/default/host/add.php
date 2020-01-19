<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view(); ?>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6">

                    <div class="panel panel-default">
                        <div class="panel-heading">Add Host</div>
                        <div class="panel-body">
                            <form method="POST"
                                  action="/posts/addhost"
                                  class="submit-jquery-form"
                                  data-success="addedwebsite">
                                <div class="form-group errorContainer">
                                    <div class="value"></div>
                                </div>
                                <div class="form-group">
                                    <label for="usr">Host Name/IP Address</label>
                                    <input type="text"
                                           autofocus="true"
                                           placeholder="type host name" .
                                           data-required="true"
                                           data-empty="Host is required"
                                           data-msg="Invalid Host"

                                           class="form-control" name="name">
                                </div>
                                <div class="form-group">
                                    <label for="usr">Host Group</label>
                                    <a href="/host-group/add" class="btn btn-default btn-xs right">
                                        <i class="fa fa-plus"></i> Add
                                    </a>
                                    <select name="host-group"
                                            class="js-data-select-ajax form-control"
                                            data-url="/posts/select"
                                            data-type="host-group">
                                        <option value="default-group" selected="selected">default-group</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="url">Device Type</label>
                                    <select class="form-control" name="device-type">
                                        <option>Linux</option>
                                        <option>Windows</option>
                                        <option>AIX</option>
                                        <option>Switch</option>
                                        <option>Router</option>
                                        <option>SAN</option>
                                        <option>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary lg" type="submit">
                                        Add Host
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
