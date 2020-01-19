<?php
$post = $data["post"];
$host_status = db_select("select meta_value from posts_meta where meta_key='status' and postid=:postid order by metaid desc limit 40", [
    "postid" => $post["postid"],
        ]);
$meta_status = "{}";
if ($host_status) {
    if (count($host_status) > 0) {
        $meta_status = $host_status[0]["meta_value"];
    }
}

$meta_status_json = json_decode($meta_status, true);
?>
<?php
$online_count = 0;
$total_count = 0;
$packet_sent = 0;
$packet_received = 0;
$packet_lost = 0;
$latency_array = [];
$latency_array[] = [
    "Time",
    "Minimum",
    "Maximum",
    "Average"
];
$host_status = array_reverse($host_status);
foreach ($host_status as $host_stat) {
    $host_stat = value_var($host_stat, "meta_value");
    $host_stat = json_decode($host_stat, true);
    // var_dump($host_stat);
    $pings = value_var($host_stat, "pings", null);
    $total_minimum = 0;
    $total_maximum = 0;
    $total_average = 0;

    if ($pings) {
        foreach ($pings as $ping) {
            
        }

        if ($host_stat["status"] == "Online") {
            $online_count++;
        }
        $total_count++;
    }
    $total_minimum +=$host_stat["minimum"];
    $total_maximum +=$host_stat["maximum"];
    $total_average +=$host_stat["average"];
    $packet_sent += $host_stat["sent"];
    $packet_received += $host_stat["received"];
    $packet_lost += $host_stat["lost"];

    $latency_array[] = [
        time_short($host_stat["time"]),
        $host_stat["minimum"],
        $host_stat["maximum"],
        $host_stat["average"],
    ];
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-3">
            <?php get_nav_view("host-nav"); ?>
        </div>
        <div class="col-sm-9">
            <div class="">
                <div class="row">
                    <div class="col-sm-12 pv-10">
                        <a href="/host/all">
                            <button class="btn btn-default">
                                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                                &nbsp;
                                back to all host
                            </button>
                        </a>
                    </div>
                </div>
                <div class="row pv-10">
                    <div class="col-sm-12">
                        <div class="host-name">

                            <?php echo $post["title"]; ?>
                            &nbsp;&nbsp;
                            <button onclick="javascript:$('a[href=\'#service-add\']').click();" class="btn btn-primary btn-sm">Add Service</button>
                        </div>

                    </div>
                </div>
            </div>
            <div class=" pv-10">
                <div class="row">
                    <div class="col-sm-12">
                        <ul class="nav nav-tabs">
                            <li class="active"><a data-toggle="tab" onclick="drawChart()" href="#overview">Overview</a></li>
                            <!--<li ><a data-toggle="tab" href="#graphical-view">Visual</a></li>-->
                            <li><a data-toggle="tab" href="#service-list">Service</a></li>
                            <li><a data-toggle="tab" href="#service-add">Add Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="container-fluid tab-content pv-10">

                <div class="row pv-10 tab-pane fade in active" id="overview">
                    <div class="col-sm-6">

                        <table class="table table-bordered">
                            <tr>
                                <td>Hostname</td>
                                <td><?php echo $post["title"]; ?></td>
                            </tr>
                            <tr>
                                <td>Host Group</td>
                                <td>
                                    <?php echo get_post($post["parentid"])["title"]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Total Service</td>
                                <td>
                                    <a
                                        onclick="javascript:$('a[href=\'#service-list\']').click();"
                                        >
                                            <?php echo get_post_parent_count($post["postid"]); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <td>Status</td>

                                <td class="status <?php echo value_var($meta_status_json, "status", "Unknown"); ?>">
                                    <?php echo value_var($meta_status_json, "status", "Unknown"); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Status Updated</td>

                                <td class="">
                                    <?php
                                    $last_status = get_post_meta($post["postid"], "last-changed", true, "{}");
                                    $last_status_json = json_decode($last_status, true);
                                    echo value_var($last_status_json, "message", "---");
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Time Created</td>
                                <td>
                                    <?php echo time_detailed($post["time_created"]); ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Last Updated</td>
                                <td>
                                    <?php
                                    $time = value_var($meta_status_json, "time");
                                    if ($time) {
                                        echo time_elapsed_string($time)." - ".time_detailed($time);
                                        
                                    } else {
                                        echo "---";
                                    }
                                    ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Next Update</td>
                                <td class="time-updated"
                                    data-current="<?php echo time(); ?>"
                                    data-time="<?php echo (value_var($meta_status_json, "time", $post["time_created"]) + 5 * 60); ?>">
                                    <span class="value">--</span>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="col-sm-6">
                        <div class="<?php echo ($host_status) ? "" : "none"; ?>">
                            <div class="col-sm-12 graph">
                                <div id="piechart"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12 <?php echo ($host_status) ? "none" : ""; ?>">
                                <h2 class="text-center">
                                    No data available.
                                </h2>
                            </div>
                            <div class="col-sm-12 <?php echo ($host_status) ? "" : "none"; ?>">
                                <div class="graph">
                                    <div id="linechart" style="min-height:350px;min-width:100%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row pv-10 tab-pane" id="graphical-view">
                    <script type="text/javascript">
                        $(document).ready(function () {
                            google.charts.load('current', {'packages': ['corechart']});
                            google.charts.setOnLoadCallback(drawChart);
                        });
                        function drawChart() {
//                            visualGraph();
                            setTimeout(visualGraph, 200);
                        }
                        function visualGraph() {

                            var data = google.visualization.arrayToDataTable([
                                ['Packets', 'Sent'],
                                ['Received', <?php echo $packet_received; ?>],
                                ['Lost', <?php echo $packet_lost; ?>],
                            ]);

                            var options = {
                                title: 'Packets',
                                chartArea: {
                                    left: 0,
                                    top: 40,
                                    bottom: 0,
                                    width: '100%',
                                    height: '85%'
                                },
                                sliceVisibilityThreshold: 0,
                            };

                            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                            chart.draw(data, options);

                            var data = google.visualization.arrayToDataTable(<?php echo json_encode($latency_array); ?>);

                            var options = {
                                title: 'Host Performance',
                                // curveType: 'function',
                                chartArea: {
                                    left: 50,
                                    top: 40,
                                    bottom: 150,
                                    width: '90%',
                                    height: '85%'
                                },
                                vAxis: {title: "# of miliseconds"},
                                hAxis: {title: "Time"},
                                legend: {position: 'bottom'}
                            };
                            var chart = new google.visualization.LineChart(document.getElementById('linechart'));
                            chart.draw(data, options);
                        }
                    </script>

                </div>
                <div class="row tab-pane fade" id="service-list">
                    <div class="col-sm-12">
                        <?php
                        $posts = db_select("posts", [
                            "post_type" => "service",
                            "parentid" => $post["postid"]
                        ]);
                        get_post_view($posts, "service-list");
                        ?>
                    </div>
                </div>
                <div class="row tab-pane fade" id="service-add">
                    <form method="POST"
                          action="/posts/addservice"
                          class="submit-jquery-form col-sm-4"
                          data-success="refreshConditional"
                          novalidate
                          >
                        <input type="hidden" value="<?php echo $post["postid"]; ?>" name="parentid"/>
                        <div class="form-group errorContainer">
                            <div class="value"></div>
                        </div>
                        <div class="form-group">
                            <label for="usr">Service Type</label>
                            <select class="form-control" name="service-type">
                                <option value="tcp" data-target="#form-tcp-monitoring">Port Monitoring(TCP)</option>
                                <option value="udp" data-target="#form-udp-monitoring">Port Monitoring(UDP)</option>
                                <!-- <option value="url" data-target="#form-url-monitoring">Url Monitoring</option> -->
                            </select>
                        </div>

<!--                        <div class="form-group none" id="form-url-monitoring">
                            <label for="usr">Type url</label>
                            <input type="url"
                                   value="https://"
                                   class="form-control"
                                   name="service_url"
                                   data-empty="URL is required"
                                   data-msg="Invalid URL"
                                   >
                        </div>-->

                        <div class="form-group " id="form-tcp-monitoring">
                            <label for="usr">TCP (Port Number)</label>
                            <input type="number"
                                   value=""
                                   class="form-control"
                                   name="service_tcp"
                                   data-empty="TCP Port is required"
                                   data-msg="Invalid TCP Port"
                                   >
                        </div>

                        <div class="form-group none" id="form-udp-monitoring">
                            <label for="usr">UDP (Port Number)</label>
                            <input type="number"
                                   value=""
                                   class="form-control"
                                   name="service_udp"
                                   data-empty="UDP Port is required"
                                   data-msg="UDP Port is invalid"
                                   >
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
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary lg" type="submit">
                                Add Service
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
