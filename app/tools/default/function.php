<?php

function is_login() {
    return session_exists("userid");
}

function is_not_login() {
    return !is_login();
}

function is_admin() {
    return session_exists("is_admin");
}

function login_require($redirect = "/login") {
    if (!is_login()) {
        header("Location: /login?redirect=" . $redirect);
    }
}

function is_positive($num) {
    if (is_numeric($num)) {
        if ($num >= 0) {
            return true;
        }
    }
    return false;
}

function is_not_admin() {
    return !is_admin();
}

function get_home() {
    return "http://" . $_SERVER['SERVER_NAME'];
}

function get_home_uri() {
    return get_home() . "/";
}

function get_absolute_url($uri) {
    if (strpos($uri, "http") == 0) {
        return $uri;
    } else {
        return get_home_uri() . $uri;
    }
}

function is_page($page) {
    $url = value_get("url", "");
    $url_explode = explode("/", $url);
    $current_page = $url_explode[0];
    $current_page = empty($current_page) ? "home" : $current_page;
    return $current_page == $page;
}

function get_page() {
    $url = value_get("url", "");
    $url_explode = explode("/", $url);
    $current_page = $url_explode[0];
    $current_page = empty($current_page) ? "home" : $current_page;
    return $current_page;
}

function user_login_required() {
    if (is_not_login()) {
        header("Location: /login?redirect=" . get_current_page_url());
    }
}

function admin_login_required() {
    if (is_not_admin()) {
        header("Location: /admin/login?redirect=" . get_current_page_url());
    }
}

function get_current_page_uri() {
    return $_SERVER['REQUEST_URI'];
}

function get_request_uri() {
    return $_SERVER['REDIRECT_URL'];
}

function get_current_page_url() {
    return get_home() . get_current_page_uri();
}

function value_get($key, $default = NULL) {
    return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function value_post($key, $default = NULL) {
    return isset($_POST[$key]) ? $_POST[$key] : $default;
}

function value_var($var, $key, $default = NULL) {
    return !empty($var[$key]) ? $var[$key] : $default;
}

function get_session($key, $default = NULL) {
    if (SessionManagement::sessionExists($key)) {
        return SessionManagement::getSession($key);
    } else {
        return $default;
    }
}

function set_session($key, $val) {
    SessionManagement::setSession($key, $val);
}

function session_exists($key) {
    return SessionManagement::sessionExists($key);
}

function current_userid() {
    return get_session("userid", -1);
}

function get_order_status_array() {
    return array("Pending",
        "Order Confirmed",
        "Out For Delivery",
        "Requested Cancellation",
        "Cancelled",
        "Delivered",
        "Rejected");
}

function time_detailed($t) {
    //    $time = strtotime($t);
    $datetime = date('Y/m/d H:i:s', $t);
    $ago = new DateTime($datetime);
    return $ago->format("d M, Y g:i:s A");
    return gmdate("d M, Y g:i:s A", $t);
}

function time_short($t) {
    //    $time = strtotime($t);
    $datetime = date('Y/m/d H:i:s', $t);
    $ago = new DateTime($datetime);
    return $ago->format("g:i A");

    return gmdate("g:i:s A", $t);
}

function time_elapsed_string($datetime, $full = false) {
    $datetime = date('Y/m/d H:i:s', $datetime);
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full)
        $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}

function is_app() {
    return SessionManagement::sessionExists("app");
}

function is_android() {
    if (SessionManagement::sessionExists("app_type")) {
        return SessionManagement::getSession("app_type") == "android";
    }
    return false;
}

function is_ios() {
    if (SessionManagement::sessionExists("app_type")) {
        return SessionManagement::getSession("app_type") == "ios";
    }
    return false;
}

function is_windowsphone() {
    if (SessionManagement::sessionExists("app_type")) {
        return SessionManagement::getSession("app_type") == "windowsphone";
    }
    return false;
}

function is_browser() {
    if (SessionManagement::sessionExists("app_type")) {
        return SessionManagement::getSession("app_type") == "browser";
    }
    return false;
}

function has_permission($permission, $account_type = null) {
    if ($account_type == NULL) {
        if (session_exists("account_type")) {
            $account_type = get_session("account_type");
        } else {
            $account_type = "Customer";
        }
    }
    $permissions = [
        "Customer" => [
        ],
        "Distributer" => [
            "user-view",
            "orders"
        ],
        "Sudo Admin" => [
            "user-view"
        ],
        "Admin" => [
            "user-edit-*",
            "user-view",
            "general-view",
            "general-edit",
            "orders",
            "products-view",
            "products-edit",
            "products-edit-favourite",
            "images-view",
            "images-edit",
            "slides-edit",
        ],
        "Super Admin" => [
            "*"
        ]
    ];
    return permission_search($permission, $permissions[$account_type]);
}

function permission_search($key, $array) {
    foreach ($array as $value) {
        $value = str_replace("*", ".*", $value);
        if (preg_match("/^{$value}/", $key)) {
            return true;
        }
    }
    return false;
}

function is_url($url) {
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    } else {
        return false;
    }
}

function get_post($postid = "", $key = null) {
    if (is_positive($postid)) {
        if ($key == null) {
            $single = db_single("select * from posts where postid=:postid", ["postid" => $postid]);
        } else {
            $single = db_single("select * from posts where $key=:$key", ["$key" => $postid]);
        }
        return $single;
    } else {
        return false;
    }
}

function remove_post($postid = "") {
    $is_deleted = db_delete("posts_meta", [
        "postid" => $postid
    ]);
    if ($is_deleted) {
        $is_deleted = db_delete("posts", [
            "postid" => $postid
        ]);
        if ($is_deleted) {
            return true;
        }
    }
    return false;
}

function get_post_parent_count($postid) {
    return db_count("posts", [
        "parentid" => $postid
    ]);
}

function get_post_view($posts, $template = "host-list") {
    $controller = new Controller();
    $controller->view("template/" . $template, ["posts" => $posts]);
}

function get_nav_view($template = "left-nav") {
    $controller = new Controller();
    $controller->view("common/" . $template);
}

function remove_service($postid) {

    $lastid = db_delete("posts_meta", [
        "postid" => $postid
    ]);
    $lastid = db_delete("posts", [
        "postid" => $postid
    ]);
    return $lastid;
}

function remove_host($postid) {

    $lastid = db_delete("posts", [
        "parentid" => $postid
    ]);

    $lastid = db_delete("posts_meta", [
        "postid" => $postid
    ]);
    $lastid = db_delete("posts", [
        "postid" => $postid
    ]);
    return $lastid;
}

function remove_host_group($postid, $defaultid) {

    $lastid = db_update("posts", [
        "parentid" => $defaultid,
            ], [
        "parentid" => $postid
    ]);

    $lastid = db_delete("posts_meta", [
        "postid" => $postid
    ]);
    $lastid = db_delete("posts", [
        "postid" => $postid
    ]);
    return $lastid;
}

function get_pagination($total, $template = "pagination") {
    $controller = new Controller();
    $controller->view("template/" . $template, ["total" => $total]);
}

function get_user($userid) {
    $res = db_single("select * from users where userid=:userid", [
        "userid" => $userid
    ]);
    return $res;
}

function get_string_between($string, $start, $end) {
    $string = ' ' . $string;
    $ini = strpos($string, $start);
    if ($ini == 0)
        return '';
    $ini += strlen($start);
    $len = strpos($string, $end, $ini) - $ini;
    return substr($string, $ini, $len);
}
