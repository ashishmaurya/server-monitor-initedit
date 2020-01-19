<?php

function get_os_name() {
    if (function_exists("php_uname")) {

        return php_uname("s");
    } else {
        return "Windows";
    }
}

function is_windows() {
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        return true;
    } else {
        return false;
    }
}

function is_linux() {
    return get_os_name() == "Linux" ? true : false;
}

function is_unix() {
    return get_os_name() == "FreeBSD" ? true : false;
}

function url_test($url) {
    $timeout = 6;
    $ch = curl_init();
    $response_time = round(microtime(true) * 1000);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $http_respond = curl_exec($ch);
    $response_time = round(microtime(true) * 1000) - $response_time;
    // $http_respond = trim(strip_tags($http_respond));
    $http_respond = $http_respond;
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $response = [];
    $response["http_code"] = $http_code;
    $response["curl_info"] = curl_getinfo($ch);
    $response["http_response"] = $http_respond;
    $response["time"] = time();
    $response["response_time"] = $response_time;
    $response["error"] = curl_error($ch);


    $primary_ip = $response["curl_info"]["primary_ip"];
    $download_size = $response["curl_info"]["size_download"];
    if (( $http_code == "200" ) || ( $http_code == "302" )) {
        $response["status"] = "Okay";
        $response["message"] = $http_code . " : $primary_ip , Up : $download_size bytes";
    } else {
        $response["status"] = "Critical";
        $response["message"] = $http_code . " : $primary_ip , Down : $download_size bytes";
    }
    $response["ip"] = $primary_ip;
    curl_close($ch);
    return $response;
}

function validate_hostname($url) {
    $url = trim($url);
    if (filter_var($url, FILTER_VALIDATE_IP) != $url) {
        if (filter_var(gethostbyname($url), FILTER_VALIDATE_IP)) {
            return true;
        }
        return false;
    } else {
        return true;
    }
}

function get_host_ip($url = '') {
    $url = trim($url);
    $url = str_replace("udp://", "", $url);
    if (filter_var($url, FILTER_VALIDATE_IP) != $url) {
        $ip = gethostbyname($url);
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        return null;
    } else {
        return $url;
    }
}

function get_downtime_msg() {
    $response = [];
    $response["code"] = 2;
    $response["status"] = "Downtime";
    $response["pings"] = [];
    $response["sent"] = 0;
    $response["received"] = 0;
    $response["lost"] = 0;
    $response["minimum"] = 0;
    $response["maximum"] = 0;
    $response["average"] = 0;
    $response["time"] = time();
    return $response;
}

function get_downtime_service_msg() {
    $response = [];
    $response["code"] = 3;
    $response["status"] = "downtime";
    $response["port_open"] = false;
    $response["message"] = "Service was in downtime";

    $response["time"] = time();
    $response["response_time"] = 0;
    return $response;
}

function is_host_in_downtime($post) {
    extract($post);
    $is_host_group_in_downtime = is_host_group_in_downtime(get_post($post["parentid"]));
    if (!$is_host_group_in_downtime) {
        $downtime_type = get_post_meta($postid, "downtime-type", true, "");
        $downtime_type_start = get_post_meta($postid, "start", true, "");
        $downtime_type_end = get_post_meta($postid, "end", true, "");
        $curret_time = time();
        $start_date = 0;
        $end_date = 0;
        if ($downtime_type == "manual") {
            $start_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_start)->getTimestamp();
            $end_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_end)->getTimestamp();
        } else if ($downtime_type == "everyday") {
            // 12:46 AM
            $start_date = DateTime::createFromFormat('H:i A', $downtime_type_start)->getTimestamp();
            $end_date = DateTime::createFromFormat('H:i A', $downtime_type_end)->getTimestamp();
        }
        if ($start_date <= $curret_time && $curret_time <= $end_date) {
            return true;
        }
        return false;
    } else {
        return $is_host_group_in_downtime;
    }
}

function is_service_in_downtime($post, $parentpost) {

    $is_host_in_downtime = is_host_in_downtime($parentpost);
    if (!$is_host_in_downtime) {
        extract($post);
        $downtime_type = get_post_meta($postid, "downtime-type", true, "");
        $downtime_type_start = get_post_meta($postid, "start", true, "");
        $downtime_type_end = get_post_meta($postid, "end", true, "");
        $curret_time = time();
        $start_date = 0;
        $end_date = 0;
        if ($downtime_type == "manual") {
            $start_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_start)->getTimestamp();
            $end_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_end)->getTimestamp();
        } else if ($downtime_type == "everyday") {
            // 12:46 AM
            $start_date = DateTime::createFromFormat('H:i A', $downtime_type_start)->getTimestamp();
            $end_date = DateTime::createFromFormat('H:i A', $downtime_type_end)->getTimestamp();
        }
        if ($start_date <= $curret_time && $curret_time <= $end_date) {
            return true;
        }
        return false;
    } else {
        return $is_host_in_downtime;
    }
}

function is_host_group_in_downtime($post) {
    extract($post);
    $downtime_type = get_post_meta($postid, "downtime-type", true, "");
    $downtime_type_start = get_post_meta($postid, "start", true, "");
    $downtime_type_end = get_post_meta($postid, "end", true, "");
    $curret_time = time();
    $start_date = 0;
    $end_date = 0;
    if ($downtime_type == "manual") {
        $start_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_start)->getTimestamp();
        $end_date = DateTime::createFromFormat('Y-m-d H:i A', $downtime_type_end)->getTimestamp();
    } else if ($downtime_type == "everyday") {
        // 12:46 AM
        $start_date = DateTime::createFromFormat('H:i A', $downtime_type_start)->getTimestamp();
        $end_date = DateTime::createFromFormat('H:i A', $downtime_type_end)->getTimestamp();
    }
    if ($start_date <= $curret_time && $curret_time <= $end_date) {
        return true;
    }
    return false;
}

function single_ping($host, $timeout = 3) {
    $response = [];
    /* ICMP ping packet with a pre-calculated checksum */
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $package = "\x08\x00\x7d\x4b\x00\x00\x00\x00PingHost";
    $socket = @socket_create(AF_INET, SOCK_RAW, 1);

    if ($socket) {
        socket_set_option($socket, SOL_SOCKET, SO_RCVTIMEO, array('sec' => $timeout, 'usec' => 0));
        $is_connected = socket_connect($socket, $host, null);
        $ts = microtime(true);
        if ($is_connected) {
            socket_send($socket, $package, strLen($package), 0);
            if (@socket_read($socket, 255)) {
                $response["status"] = true;
                $response["time"] = microtime(true) - $ts;
                $response["message"] = "Online";
            } else {
                $response["error_code"] = socket_last_error($socket);
                $response["error_message"] = socket_strerror($response["error_code"]);
                $response["time"] = microtime(true) - $ts;
                $response["status"] = false;
                $response["message"] = "Unable to read from socket";
            }
            $socket_peer_response = socket_getpeername($socket, $address, $port);
            if ($socket_peer_response) {
                $response["ip"] = $address;
                $response["port"] = $port;
            }
            socket_close($socket);
        } else {
            $response["error_code"] = socket_last_error($socket);
            $response["error_message"] = socket_strerror($response["error_code"]);
            $response["time"] = microtime(true) - $ts;
            $response["status"] = false;
            $response["message"] = "Unable to connect to $host";
        }
    } else {
        $response["error_code"] = socket_last_error($socket);
        $response["error_message"] = socket_strerror($response["error_code"]);
        $response["time"] = microtime(true) - $ts;
        $response["status"] = false;
        $response["message"] = "Unable to create socket connection to $host";
    }
    return $response;
}

function get_ping($hostname, $ping_count = 3) {
    // $ping_count = 3;
    $pings = [];
    $success_count = 0;
    $loss_count = 0;
    $minimum_time = 0;
    $maximum_time = 0;
    $average_time = 0;

    $total_time = 0;
    $host_ip = null;
    for ($i = 0; $i < $ping_count; $i++) {
        $ping = single_ping($hostname);
        $pings[] = $ping;
        if ($ping["status"]) {
            $success_count++;
        } else {
            $loss_count++;
        }
        $host_ip = $ping["ip"];
        if ($i == 0) {
            $minimum_time = $ping["time"];
        } else if ($minimum_time > $ping["time"]) {
            $minimum_time = $ping["time"];
        }
        if ($maximum_time < $ping["time"]) {
            $maximum_time = $ping["time"];
        }
        $total_time+=$ping["time"];
    }
    $response = [];
    $response["pings"] = $pings;
    $response["sent"] = count($pings);
    $response["received"] = $success_count;
    $response["lost"] = $loss_count;

    if ($loss_count == 0) {
        $response["status"] = "Online";
    } else {
        $response["status"] = "Offline";
    }

    if ($total_time > 0) {
        $response["minimum"] = $minimum_time;
        $response["maximum"] = $maximum_time;
        $response["average"] = ($total_time / $ping_count);
    }
    $response["time"] = time();
    $response["code"] = 1;

    return $response;
}

function update_host_status($post) {
    $lastupdated = get_post_meta($post["postid"], "lastupdated", true, 0);
    $next_update = $lastupdated + 5 * 60;
    if ($next_update < time()) {
        $postid = $post["postid"];
        $domain = $post["title"];
        if (is_host_in_downtime($post)) {
            $response = get_downtime_msg();
        } else {
            $ping = new Ping();
            $response = $ping->get($domain);

            $notification_enabled = get_post_meta($post["postid"], "notification-enable", true, "true");
            if ($notification_enabled == "true") {
                $last_status_str = get_post_meta($post["postid"], "status", true);
                $last_status_json = json_decode($last_status_str, true);
                if ($last_status_json["status"] != $response["status"]) {
                    $title = "<label class='status ".$response["status"]." circle'></label> Host Monitoring : $domain, <span class='status'>" . $response["status"] . "</span>";
                    $message = "<div class='text-center'><a href='/host/detail?id=" . $post["postid"] . "' class='btn btn-default'>View Detail</a></div>";
                    add_user_notification($post["userid"], $title, $message);
                    add_last_status_changed($post,$last_status_json,$response);
                }
            }
        }
        $response_str = json_encode($response);




        add_post_meta($postid, "status", $response_str);
        update_post_meta($postid, "last-status", $response["status"]);
        update_post_meta($postid, "lastupdated", time());
    }
}

function is_port_open_udp($host, $port = 80, $timeout = 6) {
    return is_port_open("udp://" . $host, $port, $timeout);
}

function is_port_open($host, $port = 80, $timeout = 3) {
    $response_time = round(microtime(true) * 1000);
    $fsock = @fsockopen($host, $port, $errno, $errstr, $timeout);
    $response_time = round(microtime(true) * 1000) - $response_time;
    $response = [
        "errorno" => $errno,
        "errormsg" => $errstr,
        "port_open" => false
    ];
    $ip_address = get_host_ip($host);
    if (!$fsock) {
        $response["port_open"] = false;
        $response["message"] = "$ip_address:$port, Closed";
    } else {
        fclose($fsock);
        $response["port_open"] = true;
        $response["message"] = "$ip_address:$port, Open";
    }
    $response["ip"] = $ip_address;
    $response["time"] = time();
    $response["response_time"] = $response_time;
    return $response;
}

function update_service_status($service, $parentpost) {
    if (is_service_in_downtime($service, $parentpost)) {
        $port_response = get_downtime_service_msg();
        $port_response_str = json_encode($port_response);
        add_post_meta($service["postid"], "status", $port_response_str);
        update_post_meta($service["postid"], "last-status", $port_response["status"]);
        update_post_meta($service["postid"], "lastupdated", time());
        return;
    }
    $lastupdated = get_post_meta($service["postid"], "lastupdated", true, 0);
    $service_type = get_post_meta($service["postid"], "service", true, "tcp");

    $next_update = $lastupdated + $service["content"] * 60;
    if ($next_update < time()) {
        $host = $parentpost["title"];
        if ($service_type == "tcp" || $service_type == "udp") {
            if ($service_type == "udp") {
                $host = "udp://" . $host;
            }
            $port_response = is_port_open($host, $service["title"]);
            $port_response["status"] = $port_response["port_open"] ? "Open" : "Closed";
            $port_response_str = json_encode($port_response);
            $notification_enabled = get_post_meta($service["postid"], "notification-enable", true, "true");
            if ($notification_enabled == "true") {
                $last_status_str = get_post_meta($service["postid"], "status", true);
                $last_status_json = json_decode($last_status_str, true);
                if ($last_status_json["port_open"] != $port_response["port_open"]) {
                    $title = "<label class='status ".$port_response["status"]." circle'></label> Port Monitoring : <span class='status'>" . $port_response["status"] . "</span> for ";
                    $title.=" <a class='host-link' href='/host/detail?id=" . $parentpost["postid"] . "'>" . $parentpost["title"] . "</a>";
                    $message = $port_response["message"];
                    add_user_notification($service["userid"], $title, $message);
                    add_last_status_changed($service,$last_status_json,$port_response);
                }
            }

            add_post_meta($service["postid"], "status", $port_response_str);
            update_post_meta($service["postid"], "last-status", $port_response["status"]);
        }
        update_post_meta($service["postid"], "lastupdated", time());
    }
}

function add_last_status_changed($post, $last_status_json, $status_json) {
    $status_changed = [];
    $status_changed["time"] = time();
    $status_changed["message"] = value_var($last_status_json, "status", "Unknown") . " &rarr; " . $status_json["status"];
    $status_changed_str = json_encode($status_changed);
    update_post_meta($post["postid"], "last-changed", $status_changed_str);
}

function get_title_string($html) {
    $res = preg_match("/<title>(.*)<\/title>/siU", $html, $title_matches);
    if (!$res)
        return null;

    // Clean up title: remove EOL's and excessive whitespace.
    $title = preg_replace('/\s+/', ' ', $title_matches[1]);
    $title = trim($title);
    return $title;
}

function update_url_status($post) {
    $lastupdated = get_post_meta($post["postid"], "lastupdated", true, 0);
    $url_type = get_post_meta($post["postid"], "type", true, "simple");
    $url_type_value = get_post_meta($post["postid"], "url-type-value", true, "");
    $next_update = $lastupdated + $post["content"] * 60;
    if ($next_update < time()) {
        $host = $post["title"];

        $url_response = url_test($host);

        $html = $url_response["http_response"];
        $html = strtolower($html);
        $url_type_value = strtolower($url_type_value);
        if ($url_type == "simple") {
            
        } else if ($url_type == "keyword") {
            $http_respond = trim(strip_tags($html));

            if (strpos($http_respond, $url_type_value) !== false) {
                $pos = strpos($html, $url_type_value);
                $url_response["status"] = "Okay";
                $url_response["message"] = "<code>$url_type_value</code> found at $pos Position<br/>" . $url_response["message"];
            } else {
                if ($url_response["status"] == "Okay") {
                    $url_response["status"] = "Critical";
                }
            }
        } else if ($url_type == "title") {
            $title = get_title_string($html);
            if ($title == null) {
                $url_response["status"] = "Warn";
                $url_response["message"] = "Title tag not found<br/>" . $url_response["message"];
            } else {
                if (strpos($title, $url_type_value) !== false) {
                    $pos = strpos($title, $url_type_value);
                    $url_response["status"] = "Okay";
                    $url_response["message"] = "<code>$url_type_value</code> found at $pos Position<br/>" . $url_response["message"];
                } else {
                    if ($url_response["status"] == "Okay") {
                        $url_response["status"] = "Critical";
                    }
                }
            }
        }
        unset($url_response["http_response"]);

        $url_response_str = json_encode($url_response);
        $notification_enabled = get_post_meta($post["postid"], "notification-enable", true, "true");
        if ($notification_enabled == "true") {

            $last_status_str = get_post_meta($post["postid"], "status", true);

            $last_status_json = json_decode($last_status_str, true);
            if ($last_status_json["status"] != $url_response["status"]) {
                $title = "<label class='status ".$url_response["status"]." circle'></label> <a class='url-link' href='/url-monitoring/detail?id=" . $post["postid"] . "'>Url</a> is <span class='status'>" . $url_response["status"] . "</span>";
                $title = "URL Monitoring : " . $url_response["status"];
                $message = "<div class='host-link'><a href='/url-monitoring/detail?id=" . $post["postid"] . "'>" . $host . "</a></div>";
                $message .= $url_response["message"];
                add_user_notification($post["userid"], $title, $message);
                add_last_status_changed($post,$last_status_json,$url_response);
            }
        }
        add_post_meta($post["postid"], "status", $url_response_str);
        update_post_meta($post["postid"], "last-status", $url_response["status"]);
        update_post_meta($post["postid"], "lastupdated", time());
    }
}

function update_all_host_status() {
    $posts = db_select("posts", [
        "enabled" => 1,
        "post_type" => "website"
    ]);
    foreach ($posts as $post) {
        update_host_status($post);
    }
    return true;
}

function update_all_host_service() {
    $posts = db_select("posts", [
        "enabled" => 1,
        "post_type" => "website"
    ]);
    foreach ($posts as $post) {
        if (is_host_in_downtime($post)) {
            continue;
        }
        update_host_all_service($post);
    }
    return true;
}

function update_host_all_service($parentpost) {
    if (is_host_in_downtime($parentpost)) {
        return;
    }
    $posts = db_select("posts", [
        "enabled" => 1,
        "post_type" => "service",
        "parentid" => $parentpost["postid"]
    ]);
    foreach ($posts as $post) {
        update_service_status($post, $parentpost);
    }
    return true;
}

function execInBackground($cmd) {
    if (is_windows()) {
        pclose(popen("start /B " . $cmd, "r"));
    } else {
        exec($cmd . " > /dev/null &");
    }
}

function add_user_notification($userid, $title, $msg = null) {
    $count = get_user_meta($userid, "notification-count", true, 0);
    update_user_meta($userid, "notification-count", $count + 1);
    $notification = [
        "title" => $title,
        "message" => $msg,
        "time" => time()
    ];
    add_user_meta($userid, "notification", json_encode($notification));
}

function get_user_notification_count($userid) {
    return get_user_meta($userid, "notification-count", true, 0);
}

function add_user_activity($userid, $title, $msg = null, $code = 0) {
    if ($userid == null) {
        $userid = current_userid();
    }
    $sessionid = get_session("sessionid");
    if ($sessionid == null) {
        $sessionid = time();
        set_session("sessionid", $sessionid);
    }
    $count = get_user_meta($userid, "activity-count", true, 0);
    update_user_meta($userid, "activity-count", $count + 1);
    $activity = [
        "title" => $title,
        "message" => $msg,
        "code" => $code,
        "device" => get_user_device_info(),
        "time" => time(),
        "sid" => $sessionid
    ];
    add_user_meta($userid, "activity", json_encode($activity));
}

function get_user_activity_count($userid) {
    return get_user_meta($userid, "activity-count", true, 0);
}

function get_user_last_notification($page = 1) {
    $limit = ($page - 1) * POST_PER_PAGE . "," . POST_PER_PAGE;
    $res = db_column("select meta_value from users_meta where userid=:userid and meta_key='notification' order by metaid desc limit $limit", [
        "userid" => current_userid()
    ]);

    return $res;
}

function get_user_last_activity($page = 1) {
    $limit = ($page - 1) * POST_PER_PAGE . "," . POST_PER_PAGE;
    $res = db_column("select meta_value from users_meta where userid=:userid and meta_key='activity' order by metaid desc limit $limit", [
        "userid" => current_userid()
    ]);

    return $res;
}

function get_user_device_info() {
    $res = [];
    $res["REMOTE_ADDR"] = $_SERVER["REMOTE_ADDR"];
    $res["REMOTE_PORT"] = $_SERVER["REMOTE_PORT"];
    $res["HTTP_USER_AGENT"] = $_SERVER["HTTP_USER_AGENT"];

    return $res;
}
