<?php
$query = "select count(*) as c,post_type from posts where userid=:userid group by post_type";
$counts = db_select($query, [
    "userid" => current_userid(),
        ]);
$parse_count = [];
foreach ($counts as $count) {
    $parse_count[$count["post_type"]] = $count["c"];
}
$counts = $parse_count;
?>
<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target=".host-nav">Hosts
        <div class="right">


            <lebel class="badge">
                <?php echo value_var($counts, "website", 0); ?>
            </lebel>

            &nbsp;
            &nbsp;
            <a href="/host/add" class="btn btn-primary btn-xs prevent-propagation">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>

    </div>
    <div class="host-nav  collapse in panel-collapse ">
        <div class="list-group">

            <a href="/host/all" class="list-group-item">All</a>
            <a href="/host/fav" class="list-group-item">Favourites</a>
            <a href="/host/recent" class="list-group-item">Recent</a>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">Quick Search Host
        <a href="/search" class="right">
            <i class="fa fa-search"></i>
        </a>
    </div>
    <div class="panel-body panel-collapse ">
        <form method="GET"
              action="/host/detail"
              >
            <div class="form-group">
                <select name="id"
                        class="js-data-select-ajax form-control"
                        data-url="/posts/select"
                        data-type="website"
                        data-placeholder="type hostname"
                        onchange="this.form.submit()">
                </select>
            </div>
        </form>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target=".url-nav">URL Monitoring
        <div class="right">


            <lebel class="badge">
                <?php echo value_var($counts, "url-monitoring", 0); ?>
            </lebel>
            &nbsp;
            &nbsp;
            <a href="/url-monitoring/add" class="btn btn-primary btn-xs prevent-propagation">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </div>
    <div class="url-nav  collapse in panel-collapse ">
        <div class="list-group">
            <a href="/url-monitoring/all" class="list-group-item">All</a>
            <a href="/url-monitoring/fav" class="list-group-item">Favourites</a>
            <a href="/url-monitoring/recent" class="list-group-item">Recent</a>
        </div>
    </div>
</div>


<div class="panel panel-default">
    <div class="panel-heading" data-toggle="collapse" data-target=".host-group-nav">Host Group
        <div class="right">

            <lebel class="badge">
                <?php echo value_var($counts, "host-group", 0); ?>
            </lebel>
            &nbsp;
            &nbsp;
            <a href="/host-group/add" class="btn btn-primary btn-xs prevent-propagation">
                <i class="fa fa-plus"></i> Add
            </a>
        </div>
    </div>
    <div class=" host-group-nav collapse in panel-collapse ">
        <div class="list-group">
            <a href="/host-group/all" class="list-group-item">All</a>
            <a href="/host-group/fav" class="list-group-item">Favourites</a>
            <a href="/host-group/recent" class="list-group-item">Recent</a>
        </div>
    </div>
</div>
