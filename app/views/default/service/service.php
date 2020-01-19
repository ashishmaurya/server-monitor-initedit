<?php
$service = $data["service"];
$parentpost = get_post($service["parentid"]);
// $service_statues = db_select("select meta_value from posts_meta where meta_key='status' and postid=:postid order by metaid desc limit 20",[
//   "postid"=>$service["postid"],
// ]);
extract($service);
$meta_str = get_post_meta($postid, "status");
$lastupdated = get_post_meta($postid, "lastupdated");
$service_type = get_post_meta($postid, "service");
if (empty($meta_str)) {
    $meta_str = "{}";
}
$meta = json_decode($meta_str, true);
$status = value_var($meta, "status", "Unknown");
?>

<div class="container-fluid tab-content pv-10">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view("service-nav"); ?>
        </div>
        <div class="col-sm-6">

            <div class="pb-10 left btn-group">
                <a class="btn btn-default"
                   href="/host/detail?id=<?php echo $parentid; ?>#service-list"
                   >
                    <i class="fa fa-arrow-left"></i> &nbsp;
                    all
                </a>
                <a class="btn btn-default"
                   href="/host/detail?id=<?php echo $parentid; ?>#service-add"
                   >
                    <i class="fa fa-plus"></i> &nbsp;
                    add
                </a>
            </div>
            <div class="pb-10 right btn-group">
                <form method="POST"
                      action="/posts/deleteservice"
                      data-success="refreshConditional"
                      data-confirm="confirmDeleteService"
                      class="submit-jquery-form btn-group">
                    <input type="hidden" name="id" value="<?php echo $postid; ?>"/>
                    <button class="btn btn-default">
                        <i class="fa fa-trash"></i> delete
                    </button>
                </form>
                <a class="btn btn-default"
                   href="/service/edit?id=<?php echo $postid; ?>"
                   >
                    <i class="fa fa-pencil"></i> &nbsp;
                    edit
                </a>
            </div>
            <table class="table table-bordered">
                <tr>
                    <td>Hostname</td>
                    <td>
                        <a href="/host/detail?id=<?php echo $parentpost["postid"]; ?>">
                            <?php echo $parentpost["title"]; ?>
                        </a>
                    </td>
                </tr>
                <tr>
                    <td>Host IP address</td>
                    <td><?php echo value_var($meta, "ip", "---"); ?></td>
                </tr>
                <tr>
                    <td>Service</td>
                    <td><?php echo $service["title"]; ?></td>
                </tr>
                <tr>
                    <td>Message</td>
                    <td><?php echo value_var($meta, "message", "---"); ?></td>
                </tr>
                <tr>
                    <td>Service Type</td>
                    <td><?php echo $service_type; ?></td>
                </tr>
                <tr>
                    <td>Time Interval</td>
                    <td><?php echo $content; ?> minutes</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td class="status <?php echo $status; ?>">
                        <?php
                        echo $status;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Status Changed </td>
                    <td class="">
                        <?php
                        $last_status = get_post_meta($postid, "last-changed", true, "{}");

//                        print_r($last_status);
                        $last_status_json = json_decode($last_status, true);
                        echo value_var($last_status_json, "message", "---");
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Response Time</td>
                    <td >
                        <?php
                        // echo $meta["response_time"];
                        echo value_var($meta, "response_time", "---");
                        ?>
                        ms
                    </td>
                </tr>

                <tr>
                    <td>Time Created</td>
                    <td>
                        <!-- <?php echo time_detailed($service["time_created"]); ?> -->
                        <?php echo time_detailed(value_var($service, "time_created")); ?>
                    </td>
                </tr>

                <tr>
                    <td>Last Checked</td>
                    <td>
                        <?php
                        $time = value_var($meta, "time");
                        if ($time) {
                            echo time_elapsed_string($time) . " - " . time_detailed($time);
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
                        data-time="<?php echo (value_var($meta, "time", $service["time_created"]) + ((int) $content) * 60); ?>">
                        <span class="value">--</span>
                    </td>
                </tr>
            </table>
        </div>
        <!--        <div class="col-sm-6 json-pretty">
        <?php
        if ($meta_str == "{}") {
            ?>
                            <div class="jumbotron">
                                <h1>No data found</h1>
                                <p>Looks like you have just added service.</p>
                            </div>
            <?php
        }
        ?>
                    <script>
                        var obj = JSON.parse('<?php echo json_encode($meta); ?>');
                        var str = JSON.stringify(obj, null, 2);
                        function syntaxHighlight(json) {
                            if (typeof json != 'string') {
                                json = JSON.stringify(json, undefined, 2);
                            }
                            json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                            return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
                                var cls = 'number';
                                if (/^"/.test(match)) {
                                    if (/:$/.test(match)) {
                                        cls = 'key';
                                    } else {
                                        cls = 'string';
                                    }
                                } else if (/true|false/.test(match)) {
                                    cls = 'boolean';
                                } else if (/null/.test(match)) {
                                    cls = 'null';
                                }
                                return '<span class="' + cls + '">' + match + '</span>';
                            });
                        }
                            var json_str = (syntaxHighlight(obj));
                            json_str = json_str.split("\n").join("<br/>");
                            json_str = json_str.split("\t").join("&nbsp;&nbsp;&nbsp;&nbsp;");
                            json_str = json_str.split("  ").join("&nbsp;&nbsp;");
                            document.write(json_str);
                    </script>
                </div>-->
    </div>
</div>
