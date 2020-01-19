<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Class used to interact with database and users input
 * @TODO   Remove unused function
 * @FIX    Too much to fix (See Indivisual Function for more detail)
 */
class posts extends Controller {

    public function index() {
        $result = ["code" => 100, "message" => "Unknown Error"];
        echo json_encode($result);
    }

    public function select() {
        $q = trim(value_post("q", ""));
        $page = value_post("page");
        $post_type = value_post("search");

        $posts = db_select("select postid as id,title as text from posts where enabled=1 and post_type=:post_type and userid=:userid and title like :title order by postid desc limit 6", [
            "post_type" => $post_type,
            "userid" => current_userid(),
            "title" => "%" . $q . "%"
        ]);
        echo json_encode(["code" => 1, "items" => $posts]);
    }

    public function updateurl() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $interval = value_post("interval");
        $id = value_post("id");
        $valid_interval = true;
        if ($interval == "manual") {
            $t = value_post("manual-interval", 0);
            if (!is_positive($t)) {
                $valid_interval = false;
            } else if ($t < 5) {
                $valid_interval = false;
            } else {
                $interval = ((int) $t);
            }
        }
        $post = get_post($id);
        if (!$post) {
            $result["message"] = "Invalid Service type";
        } else if (empty($interval)) {
            $result["message"] = "Interval is required";
        } else if (!$valid_interval) {
            $result["message"] = "Interval should be greater then 5";
        } else if (!is_positive($interval)) {
            $result["message"] = "Interval is invalid";
        } else {
            if ($post["userid"] == current_userid()) {
                $updated = db_update("posts", [
                    "content" => $interval
                        ], [
                    "postid" => $id
                ]);
                $result["code"] = 1;
                $result["message"] = "Updated Successfully";
            } else {
                $result["message"] = "Access Denied";
            }
        }
        echo json_encode($result);
    }

    public function addurl() {
        $result = ["code" => 100, "message" => "Unknown Error"];
        $url = value_post("name");
        $interval = value_post("interval");
        $url_type = value_post("url-type", "simple");
        $url_type_value = value_post("url-" . $url_type, "");
        $valid_interval = true;
        if ($interval == "manual") {
            $t = value_post("manual-interval", 0);
            if (!is_positive($t)) {
                $valid_interval = false;
            } else if ($t < 5) {
                $valid_interval = false;
            } else {
                $interval = ((int) $t);
            }
        }
        if (empty($url)) {
            $result["message"] = "URL is required";
        } else if (!is_url($url)) {
            $result["message"] = "URL is invalid";
        } else if (empty($interval)) {
            $result["message"] = "Interval is required";
        } else if (!$valid_interval) {
            $result["message"] = "Interval should be greater then 5";
        } else if (!is_positive($interval)) {
            $result["message"] = "Interval is invalid";
        } else if ($url_type == "keyword" && empty($url_type_value)) {
            $result["message"] = "Keyword is required";
        } else if ($url_type == "title" && empty($url_type_value)) {
            $result["message"] = "title is required";
        } else {

            $lastid = db_insert("posts", [
                "title" => $url,
                "content" => $interval,
                "time_created" => time(),
                "post_type" => "url-monitoring",
                "userid" => current_userid(),
                "parentid" => null
            ]);
            if (($lastid)) {
                $post = get_post($lastid);
                update_post_meta($lastid, "type", $url_type);
                update_post_meta($lastid, "url-type-value", $url_type_value);
                update_url_status($post);
                $title = "URL Monitoring : New URL Was Added";
                $message = "New URL was added";
                $message.= "<div class='text-center'><a href='/url-monitoring/detail?id=$lastid'>$url</a></div>";
                add_user_activity(null, $title, $message, 6);
                $result["code"] = 1;
                $result["message"] = "Added Sucessfully";
                $result["url"] = "/url-monitoring/detail?id=" . $lastid;
            } else {
                $result["message"] = "Unable to add url";
            }
        }
        echo json_encode($result);
    }

    public function addservice() {
        $result = ["code" => 100, "message" => "Unknown Error"];
        $service_type = value_post("service-type");
        $content_selection = "service_" . $service_type;
        $portnumber = value_post($content_selection);
        $interval = value_post("interval");
        $parentid = value_post("parentid");
        $parentpost = get_post($parentid);

        $is_valid = false;
        $error_msg = "Unknown Error";
        if ($service_type == "tcp" || $service_type == "udp") {
            if (is_positive($portnumber)) {
                $is_valid = true;
            } else {
                $error_msg = "Port number invalid";
            }
        } else if ($service_type == "url") {
            if (is_url($portnumber)) {
                $is_valid = true;
            } else {
                $error_msg = "Url is invalid";
            }
        }


        if (empty($portnumber)) {
            $result["message"] = "Service value is required";
        } else if (!$is_valid) {
            $result["message"] = $error_msg;
        } else if (empty($interval)) {
            $result["message"] = "Interval is required";
        } else if (!$parentpost) {
            $result["message"] = "Host is invalid";
        } else {

            $lastid = db_insert("posts", [
                "title" => $portnumber,
                "content" => $interval,
                "time_created" => time(),
                "post_type" => "service",
                "userid" => current_userid(),
                "parentid" => $parentid
            ]);
            if (($lastid)) {

                $post = get_post($lastid);
                add_post_meta($lastid, "service", $service_type);
                update_service_status($post, $parentpost);

                $title = "Service : New Service Added";
                $message = "New service was added";
                $message.= "<div class='text-center'><a href='/service/detail?id=$lastid'>$portnumber</a></div>";
                add_user_activity(null, $title, $message, 5);


                $result["code"] = 1;
                $result["url"] = "/service/?id=" . $lastid;
                $result["message"] = "Added Sucessfully";
            } else {
                $result["message"] = "Unable to add service";
            }
        }
        echo json_encode($result);
    }

    public function addhostgroup() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $name = value_post("name");
        $description = value_post("description", "");


        if (empty($name)) {
            $result["message"] = "Host group name required";
        } else {
            $post_exists = db_count("posts", [
                "title" => $name,
                "userid" => current_userid(),
                "post_type" => "host-group"
            ]);
            if ($post_exists == 0) {

                $lastid = db_insert("posts", [
                    "title" => $name,
                    "content" => $description,
                    "time_created" => time(),
                    "post_type" => "host-group",
                    "userid" => current_userid(),
                ]);
                if (is_positive($lastid)) {
                    $title = "Host Group : New Host Group Created";
                    $message = "New Host Group was created";
                    $message.= "<div class='text-center'><a href='/host-group/detail?id=$lastid'>$name</a></div>";
                    add_user_activity(current_userid(), $title, $message, 3);
                    $result["code"] = 1;
                    $result["message"] = "Added Sucessfully";
                    $result["url"] = "/host-group/edit?id=".$lastid;
                } else {
                    $result["message"] = "Unable to add host group";
                }
            } else {
                $result["message"] = "Host group is already added";
            }
        }
        echo json_encode($result);
    }

    public function addhost() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $name = value_post("name");
        $deviceType = value_post("device-type");
        $hostGroup = value_post("host-group");
        if (!is_positive($hostGroup)) {
            $postHostGroup = db_single("posts", [
                "userid" => current_userid(),
                "post_type" => "host-group",
                "title" => $hostGroup
            ]);
        } else {
            $postHostGroup = get_post($hostGroup);
        }

        if (empty($name)) {
            $result["message"] = "Host name required";
        } else if (!validate_hostname($name)) {
            $result["message"] = "Host name is invalid";
        } else if (empty($deviceType)) {
            $result["message"] = "Device type is required";
        } else if (!$postHostGroup) {
            $result["message"] = "Host Group is required";
        } else {
            $post_exists = db_count("posts", [
                "title" => $name,
                "userid" => current_userid(),
            ]);
            if ($post_exists == 0) {

                $lastid = db_insert("posts", [
                    "title" => $name,
                    "content" => $deviceType,
                    "time_created" => time(),
                    "post_type" => "website",
                    "userid" => current_userid(),
                    "parentid" => $postHostGroup["postid"]
                ]);
                if (is_positive($lastid)) {
                    $post = get_post($lastid);
                    update_host_status($post);
                    $title = "Host : New Host Added";
                    $message = "New Host was added";
                    $message.= "<div class='text-center'><a href='/host/detail?id=$lastid'>$name</a></div>";
                    add_user_activity(current_userid(), $title, $message, 4);

                    $result["code"] = 1;
                    $result["id"] = $lastid;
                    $result["message"] = "Added Sucessfully";
                } else {
                    $result["message"] = "Unable to add website";
                }
            } else {
                $result["message"] = "host is already added";
            }
        }
        echo json_encode($result);
    }

    public function fav() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");
        $key = value_post("key");

        if (empty($postid)) {
            $result["message"] = "Invalid Host";
        } else if (empty($key)) {
            $result["message"] = "Invalid Key";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    if (!user_meta_exists(current_userid(), $key, $postid)) {
                        $lastid = add_user_meta(current_userid(), $key, $postid);
                        if ($lastid) {
                            $result["code"] = 1;
                            $result["postid"] = $postid;
                            $result["message"] = "Added to fav";
                        } else {
                            $result["message"] = "Unable to add to fav";
                        }
                    } else {
                        $result["code"] = 1;
                        $result["postid"] = $postid;
                        $result["message"] = "Added to fav";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function unfav() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");
        $key = value_post("key");

        if (empty($postid)) {
            $result["message"] = "Invalid Host";
        } else if (empty($key)) {
            $result["message"] = "Invalid Key";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {

                    $lastid = delete_user_meta(current_userid(), $key, $postid);
                    if ($lastid) {
                        $result["code"] = 1;
                        $result["postid"] = $postid;
                        $result["message"] = "Removed from fav";
                    } else {
                        $result["message"] = "Unable to remove from fav";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function deletehostgroup() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");

        if (empty($postid)) {
            $result["message"] = "Invalid website";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    if ($post["title"] != "default-group") {
                        $defaultgroup = db_select("select postid from posts where userid=:userid and title='default-group'", [
                            "userid" => current_userid()
                        ]);
                        $defaultgroupid = $defaultgroup["postid"];
                        $lastid = remove_host_group($postid, $defaultgroupid);
                        if (($lastid)) {

                            $result["code"] = 1;
                            $result["postid"] = $postid;
                            $result["message"] = "Removed Sucessfully";
                        } else {
                            $result["message"] = "Unable to remove website";
                        }
                    } else {
                        $result["message"] = "Default host group cannot be deleted";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function deletehost() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");

        if (empty($postid)) {
            $result["message"] = "Invalid website";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $lastid = remove_host($postid);
                    if (($lastid)) {

                        $result["code"] = 1;
                        $result["postid"] = $postid;
                        $result["message"] = "Removed Sucessfully";
                    } else {
                        $result["message"] = "Unable to remove website";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function deleteservice() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");

        if (empty($postid)) {
            $result["message"] = "Invalid Service";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $lastid = remove_service($postid);
                    if (($lastid)) {
                        $result["code"] = 1;
                        $result["postid"] = $postid;
                        $result["url"] = "/host/detail?id=" . $post["parentid"] . "#service-list";
                        $result["message"] = "Removed Sucessfully";
                    } else {
                        $result["message"] = "Unable to remove service";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function deleteurl() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");

        if (empty($postid)) {
            $result["message"] = "Invalid url";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $lastid = remove_service($postid);
                    if (($lastid)) {
                        $result["code"] = 1;
                        $result["postid"] = $postid;
                        $result["message"] = "Removed Sucessfully";
                    } else {
                        $result["message"] = "Unable to remove url";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function updatehost() {
        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");
        $groupid = value_post("host-group");
        $down_type = value_post("downtime-type");
        if (empty($postid)) {
            $result["message"] = "Invalid host";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $lastid = db_update("posts", [
                        "parentid" => $groupid,
                            ], [
                        "postid" => $postid
                    ]);
                    if (isset($_POST["notification"])) {
                        update_post_meta($postid, "notification-enable", "true");
                    } else {
                        update_post_meta($postid, "notification-enable", "false");
                    }
                    if ($lastid) {
                        if ($down_type == "manual") {
                            $start = value_post("manual-start");
                            $end = value_post("manual-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "manual");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else if ($down_type == "everyday") {
                            $start = value_post("everyday-start");
                            $end = value_post("everyday-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "everyday");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else {
                            update_post_meta($postid, "downtime-type", "none");
                            update_post_meta($postid, "start", "");
                            update_post_meta($postid, "end", "");
                        }
                        $result["code"] = 1;

                        $result["postid"] = $postid;
                        $result["message"] = "Updated Sucessfully";
                    } else {
                        $result["message"] = "Unable to remove service";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            }
        }
        echo json_encode($result);
    }

    public function updateservice() {

        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");
        $interval = value_post("interval");

        $down_type = value_post("downtime-type");
        if (empty($postid)) {
            $result["message"] = "Invalid Service";
        } else if (!is_positive($interval)) {
            $result["message"] = "Invalid Interval";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $updated = db_update("posts", [
                        "content" => $interval
                            ], [
                        "postid" => $postid
                    ]);
                    if ($updated) {
                        if (isset($_POST["notification"])) {
                            update_post_meta($postid, "notification-enable", "true");
                        } else {
                            update_post_meta($postid, "notification-enable", "false");
                        }

                        if ($down_type == "manual") {
                            $start = value_post("manual-start");
                            $end = value_post("manual-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "manual");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else if ($down_type == "everyday") {
                            $start = value_post("everyday-start");
                            $end = value_post("everyday-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "everyday");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else {
                            update_post_meta($postid, "downtime-type", "none");
                            update_post_meta($postid, "start", "");
                            update_post_meta($postid, "end", "");
                        }
                        $result["code"] = 1;
                        $result["message"] = "Updated Successfully";
                    } else {
                        $result["message"] = "Unable to update service";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            } else {
                $result["message"] = "Service not found";
            }
        }
        echo json_encode($result);
    }

    public function updatehostgroup() {

        $result = ["code" => 100, "message" => "Unknown Error"];

        $postid = value_post("id");
        $name = value_post("name");
        $description = value_post("description");

        $down_type = value_post("downtime-type");
        if (empty($postid)) {
            $result["message"] = "Invalid Host Group";
        } else if (empty($name)) {
            $result["message"] = "Host Group name required";
        } else {
            $post = get_post($postid);
            if ($post) {
                if ($post["userid"] == current_userid()) {
                    $description = htmlspecialchars($description);
                    $updated = db_update("posts", [
                        "content" => $description,
                        "title" => $name
                            ], [
                        "postid" => $postid
                    ]);
                    if ($updated) {
                        if (isset($_POST["notification"])) {
                            update_post_meta($postid, "notification-enable", "true");
                        } else {
                            update_post_meta($postid, "notification-enable", "false");
                        }

                        if ($down_type == "manual") {
                            $start = value_post("manual-start");
                            $end = value_post("manual-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "manual");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else if ($down_type == "everyday") {
                            $start = value_post("everyday-start");
                            $end = value_post("everyday-end");
                            if (!empty($start) && !empty($end)) {
                                update_post_meta($postid, "downtime-type", "everyday");
                                update_post_meta($postid, "start", $start);
                                update_post_meta($postid, "end", $end);
                            }
                        } else {
                            update_post_meta($postid, "downtime-type", "none");
                            update_post_meta($postid, "start", "");
                            update_post_meta($postid, "end", "");
                        }
                        $result["code"] = 1;
                        $result["message"] = "Updated Successfully";
                    } else {
                        $result["message"] = "Unable to update host group";
                    }
                } else {
                    $result["message"] = "Access Denied";
                }
            } else {
                $result["message"] = "Host Group not found";
            }
        }
        echo json_encode($result);
    }

    public function update_status($postid, $url) {
        $response = url_test($url);
        $response_str = json_encode($response);
        add_post_meta($postid, "status", $response_str);
    }

}
