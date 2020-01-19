<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Search page
 * @TODO   Add More functionality 
 */

class search extends Controller {

    public function index() {
        login_require();
        $this->view("common/head", ["title" => "search", "description" => "Login Page"]);
        $this->view("common/header");
        $this->view("search/home");
        $this->view("common/footer");
    }
    
    /*
     * @AUTHOR Ashish
     * @ABOUT ASHQL functiona returns the list of posts(HTML Table)
     * @TODO   Add More advanced function to it and also add user search to history
     */

    public function ashql() {
        $result = ["code" => 1, "message" => "Unknown Error"];
        $search_term = value_post("s");
        if (empty($search_term)) {
            $result["html"] = "<h1>You know what to search</h1>";
        } else {
            $search_term = $search_term . " ";
            $status = get_string_between($search_term, "status:", " ");
            $type = get_string_between($search_term, "type:", " ");

            $q = str_replace("status:" . $status, "", $search_term);
            $q = str_replace("type:" . $type, "", $q);
            $q = trim($q);

            if (empty($type) || $type == "host") {
                $type = "website";
            }
            $status_query = "";
            if (!empty($status)) {
                $status_query = " and postid in (select postid from posts_meta where userid=:userid and meta_key='last-status' and meta_value=:status) ";
            }
            if ($type == "group") {
                $type = "host-group";
            }
            if ($type == "url") {
                $type = "url-monitoring";
            }

            $params = [
                "userid" => current_userid(),
                "post_type" => $type,
                "title" => "%" . $q . "%"
            ];
            if (!empty($status)) {
                $status = strtolower($status);
                if ($type == "website") {
                    if ($status == "okay") {
                        $status = "online";
                    } else if ($status == "critical") {
                        $status = "offline";
                    }
                } else if ($type == "service") {
                    if ($status == "okay") {
                        $status = "open";
                    } else if ($status == "critical") {
                        $status = "closed";
                    }
                }

                $status = ucwords($status);
                $params["status"] = $status;
            }

            $base_query = "select * from posts where enabled=1 and userid=:userid and post_type=:post_type $status_query and title like :title order by postid desc limit " . POST_PER_PAGE;
            $posts = db_result($base_query, $params);

            $result["html"] = $this->getview("search/search-list", ["post" => $posts, "params" => $params]);
        }

        echo json_encode($result);
    }

}
