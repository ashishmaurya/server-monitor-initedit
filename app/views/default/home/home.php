<?php
$query = "select meta_value COLLATE utf8_bin as meta_value, count(*) as c from posts_meta where meta_key='last-status' and postid in (select postid from posts where userid=:userid) group by  meta_value  COLLATE utf8_bin";
$counts = db_result($query, [
    "userid" => current_userid()
        ]);

$c_array = [];
foreach ($counts as $c) {
    $c_array[$c["meta_value"]] = $c["c"];
}
$count_size = db_single("select count(*) as c from posts where userid=:userid and post_type not in ('host-group')", [
            "userid" => current_userid()
        ])["c"];
?>
<?php
if ($count_size == 0) {
    ?>
    <div class="container-fluid">
        <div class="row pv-10">
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h2>Welcome New 'Admin'</h2>
                        <p class="text-muted">Some topics to get you started monitoring your network.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-3">
                        <div class="ash-card">
                            <img class="ash-card-top" src="/public/images/imgs/card-1.png"/>
                            <div class="card-block text-center">
                                <h4 class="card-title">Monitor Host</h4>
                                <p class="card-subtitle text-muted">
                                    Add hosts for monitoring to get insight on availability and performance
                                    or add services for fine grained monitoring
                                </p>
                                <br/>
                                <br/>
                                <br/>
                                <a href="/host/add" class="btn btn-primary btn-block">Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="ash-card">
                            <img class="ash-card-top" src="/public/images/imgs/card-2.png"/>
                            <div class="card-block text-center">
                                <h4 class="card-title">Monitor URL</h4>
                                <p class="card-subtitle text-muted">
                                    Add Web url for monitoring to get insight on availability and performance
                                </p>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <a href="/url-monitoring/add" class="btn btn-primary btn-block">Add</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="ash-card">
                            <img class="ash-card-top" src="/public/images/imgs/card-3.png"/>
                            <div class="card-block text-center">
                                <h4 class="card-title">My Account</h4>
                                <p class="card-subtitle text-muted">
                                    Modify your account / change your password and more
                                </p>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <a href="/account" class="btn btn-primary btn-block">Account</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="ash-card">
                            <img class="ash-card-top" src="/public/images/imgs/card-4.png"/>
                            <div class="card-block text-center">
                                <h4 class="card-title">Host Group</h4>
                                <p class="card-subtitle text-muted">
                                    Add host to group for easy monitoring and maintainance
                                </p>
                                <br/>
                                <br/>
                                <br/>
                                <br/>
                                <a href="/account" class="btn btn-primary btn-block">Add</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-3">
                        <a href="/host/all" class="btn btn-default btn-block">View Hosts</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="/url-monitoring/all" class="btn btn-default btn-block">View URL's</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="/account/edit" class="btn btn-default btn-block">Edit Account</a>
                    </div>
                    <div class="col-sm-3">
                        <a href="/host-group/all" class="btn btn-default btn-block">View Hosts Groups</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    return;
}
?>
<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view(); ?>
        </div>
        <div class="col-sm-9">
            <div class="row home-graphs">
                <div class="col-sm-4 graph-title text-center">Host Status</div>
                <div class="col-sm-4 graph-title text-center">Service Status</div>
                <div class="col-sm-4 graph-title text-center">URL Status</div>
                <div class="col-sm-4 home-graph" id="host-total"></div>
                <div class="col-sm-4 home-graph"  id="service-total"></div>
                <div class="col-sm-4 home-graph" id="url-total"></div>
                <div class="col-sm-4"  >
                    <a href="/host/all" id="host-total-count"></a>
                </div>
                <div class="col-sm-4"  >
                      <a href="/host/all" id="service-total-count"></a>
                </div>
                <div class="col-sm-4">
                      <a href="/url-monitoring/all" id="url-total-count"></a>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <?php
                    $limit = " limit " . (get_current_page() - 1) * POST_PER_PAGE . "," . POST_PER_PAGE;
                    $base_query = "select * from posts where enabled=:enabled and post_type=:post_type and userid=:userid";
                    $params = [
                        "enabled" => 1,
                        "userid" => current_userid(),
                        "post_type" => 'website'
                    ];
                    $posts = db_result("$base_query order by postid $limit", $params);
                    get_post_view($posts);
                    ?>
                </div>
            </div>
        </div>
    </div>

</div>
<script>
    $(document).ready(function () {
        google.charts.load('current', {'packages': ['corechart']});
        google.charts.setOnLoadCallback(drawChart);
    });
    function drawChart() {
        // setTimeout(visualGraph,200);
        visualGraphHost();
        visualGraphService();
        visualGraphUrl();
    }
    var colors = ["rgb(51, 220, 30)", "rgb(248, 75, 24)", "rgb(249, 154, 80)"];

    var GlobalOptions = {
        chartArea: {
            left: 0,
            top: 10,
            bottom: 30,
            width: '100%',
            height: '85%'
        },
        sliceVisibilityThreshold: 0,
        colors: colors,
        pieSliceText: 'percentage',
        titleTextStyle: {
//        color: <string>,    // any HTML string color ('red', '#cc00cc')
//        fontName: <string>, // i.e. 'Times New Roman'
            fontSize: 16, // 12, 18 whatever you want (don't specify px)
//        bold: <boolean>,    // true or false
//        italic: <boolean>   // true of false
        },
        legend: {
            position: "bottom"
        }
    }
    function visualGraphHost() {
        var raw_data = [
            ['Status', 'Numbers'],
            ['Online', <?php echo value_var($c_array, "Online", 0); ?>],
            ['Offline', <?php echo value_var($c_array, "Offline", 0); ?>],
            ['Downtime', <?php echo value_var($c_array, "Downtime", 0); ?>],
        ];
        var data = google.visualization.arrayToDataTable(raw_data);
        var options = GlobalOptions;
        options.title = "Host Status";
        var chart = new google.visualization.PieChart(document.getElementById('host-total'));
        chart.draw(data, options);

        $("#host-total-count").html((raw_data[1][1] + raw_data[2][1]+ raw_data[3][1]));
    }
    function visualGraphService() {
        var raw_data = [
            ['Status', 'Numbers'],
            ['Open', <?php echo value_var($c_array, "Open", 0); ?>],
            ['Closed', <?php echo value_var($c_array, "Closed", 0); ?>],
            ['Downtime', <?php echo value_var($c_array, "downtime", 0); ?>],
        ];
        var data = google.visualization.arrayToDataTable(raw_data);
        var options = GlobalOptions;
        options.title = "Service Status";
        var chart = new google.visualization.PieChart(document.getElementById('service-total'));
        chart.draw(data, options);
        $("#service-total-count").html((raw_data[1][1] + raw_data[2][1] + raw_data[3][1]));
    }

    function visualGraphUrl() {
        var raw_data = [
            ['Status', 'Numbers'],
            ['Okay', <?php echo value_var($c_array, "Okay", 0); ?>],
            ['Critical', <?php echo value_var($c_array, "Critical", 0); ?>],
            ['Warn', <?php echo value_var($c_array, "Warn", 0); ?>],
        ];
        var data = google.visualization.arrayToDataTable(raw_data);
        var options = GlobalOptions;
        options.title = "URL Status";
        var chart = new google.visualization.PieChart(document.getElementById('url-total'));
        chart.draw(data, options);
        $("#url-total-count").html((raw_data[1][1] + raw_data[2][1] + raw_data[3][1]));
    }
</script>
