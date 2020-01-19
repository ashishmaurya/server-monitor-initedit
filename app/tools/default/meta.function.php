<?php

function get_user_meta($userid, $meta_key, $single = false, $default = NULL) {
    $limit = "";
    if ($single) {
        $limit = " limit 0,1";
    }
    $singleDatabase = new Database();
    $singleDatabase->query("select meta_value from users_meta where meta_key=:meta_key and userid=:userid  order by metaid desc $limit");
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":userid", $userid);
    $result = $singleDatabase->columnArray();

    if ($result == null || count($result) == 0) {
        return $default;
    } else {
        if ($single) {
            return $result[0];
        } else {
            return $result;
        }
    }
}

function user_meta_exists($userid, $meta_key, $meta_value) {
    $singleDatabase = new Database();
    if (empty($meta_value)) {
        $singleDatabase->query("select * from users_meta where meta_key=:meta_key and userid=:userid order by metaid desc");
    } else {
        $singleDatabase->query("select * from users_meta where meta_key=:meta_key and meta_value=:meta_value and userid=:userid order by metaid desc");
        $singleDatabase->bind(":meta_value", $meta_value);
    }
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":userid", $userid);
    return $singleDatabase->single();
}

function add_user_meta($userid, $meta_key, $meta_value) {
    $singleDatabase = new Database();
    $singleDatabase->query("insert into users_meta(userid,meta_key,meta_value) values(:userid,:meta_key,:meta_value)");
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":meta_value", $meta_value);
    $singleDatabase->bind(":userid", $userid);
    return $singleDatabase->execute();
}

function update_user_meta($userid, $meta_key, $meta_value, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = user_meta_exists($userid, $meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("update users_meta set meta_value=:meta_value where meta_key=:meta_key and userid=:userid");
        } else {
            $singleDatabase->query("update users_meta set meta_value=:meta_value where meta_key=:meta_key and meta_value=:last_meta_value and userid=:userid");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }

        $singleDatabase->bind(":meta_value", $meta_value);
        $singleDatabase->bind(":meta_key", $meta_key);
        $singleDatabase->bind(":userid", $userid);
        return $singleDatabase->execute();
    } else {
        add_user_meta($userid, $meta_key, $meta_value);
    }
    return true;
}

function delete_user_meta($userid, $meta_key, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = user_meta_exists($userid, $meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("delete from users_meta where meta_key=:meta_key and userid=:userid");
        } else {
            $singleDatabase->query("delete from users_meta where meta_key=:meta_key and meta_value=:last_meta_value and userid=:userid");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }
        $singleDatabase->bind(":meta_key", $meta_key);
        $singleDatabase->bind(":userid", $userid);
        return $singleDatabase->execute();
    }
    return true;
}

function is_json($string) {
 json_decode($string);
 return (json_last_error() == JSON_ERROR_NONE);
}

function get_options($meta_key, $single = true, $default = NULL) {
    $limit = "";
    if ($single) {
        $limit = " limit 0,1";
    }
    $singleDatabase = new Database();
    $singleDatabase->query("select meta_value from options where meta_key=:meta_key   order by metaid desc  $limit");
    $singleDatabase->bind(":meta_key", $meta_key);

    $result = $singleDatabase->columnArray();

    if ($result == null || count($result) == 0) {
        return $default;
    } else {
        if ($single) {
            if(is_json($result[0])){
                return json_decode($result[0],true);
            }
            return $result[0];
        } else {
            return $result;
        }
    }
}

function delete_options($meta_key, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = options_exists($meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("delete from options where meta_key=:meta_key");
        } else {
            $singleDatabase->query("delete from options where meta_key=:meta_key and meta_value=:last_meta_value");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }
        $singleDatabase->bind(":meta_key", $meta_key);

        return $singleDatabase->execute();
    }
    return true;
}

function options_exists($meta_key, $meta_value = NULL) {
    $singleDatabase = new Database();
    if (empty($meta_value)) {
        $singleDatabase->query("select * from options where meta_key=:meta_key order by metaid desc");
    } else {
        $singleDatabase->query("select * from options where meta_key=:meta_key and meta_value=:meta_value order by metaid desc");
        $singleDatabase->bind(":meta_value", $meta_value);
    }
    $singleDatabase->bind(":meta_key", $meta_key);

    return $singleDatabase->single();
}

function add_options($meta_key, $meta_value) {
    $singleDatabase = new Database();
    $singleDatabase->query("insert into options(meta_key,meta_value) values(:meta_key,:meta_value)");
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":meta_value", $meta_value);
    return $singleDatabase->execute();
}

function update_options($meta_key, $meta_value, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = options_exists($meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("update options set meta_value=:meta_value where meta_key=:meta_key");
        } else {
            $singleDatabase->query("update options set meta_value=:meta_value where meta_key=:meta_key and meta_value=:last_meta_value");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }

        $singleDatabase->bind(":meta_value", $meta_value);
        $singleDatabase->bind(":meta_key", $meta_key);

        return $singleDatabase->execute();
    } else {
        add_options($meta_key, $meta_value);
    }
    return true;
}

function get_post_meta($postid, $meta_key, $single = true, $default = NULL) {
    $limit = "";
    if ($single) {
        $limit = " limit 0,1";
    }
    $singleDatabase = new Database();
    $singleDatabase->query("select meta_value from posts_meta where meta_key=:meta_key and postid=:postid  order by metaid desc $limit");
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":postid", $postid);
    $result = $singleDatabase->columnArray();
    if ($result == null || count($result) == 0) {
        return $default;
    } else {
        if ($single) {
            return $result[0];
        } else {
            return $result;
        }
    }
}

function post_meta_exists($postid, $meta_key, $meta_value) {
    $singleDatabase = new Database();
    if (empty($meta_value)) {
        $singleDatabase->query("select * from posts_meta where meta_key=:meta_key and postid=:postid order by metaid desc");
    } else {
        $singleDatabase->query("select * from posts_meta where meta_key=:meta_key and meta_value=:meta_value and  postid=:postid order by metaid desc");
        $singleDatabase->bind(":meta_value", $meta_value);
    }
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":postid", $postid);
    return $singleDatabase->single();
}

function add_post_meta($postid, $meta_key, $meta_value) {
    $singleDatabase = new Database();
    $singleDatabase->query("insert into posts_meta(postid,meta_key,meta_value) values(:postid,:meta_key,:meta_value)");
    $singleDatabase->bind(":meta_key", $meta_key);
    $singleDatabase->bind(":meta_value", $meta_value);
    $singleDatabase->bind(":postid", $postid);
    return $singleDatabase->execute();
}

function update_post_meta($postid, $meta_key, $meta_value, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = post_meta_exists($postid, $meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("update posts_meta set meta_value=:meta_value where meta_key=:meta_key and postid=:postid");
        } else {
            $singleDatabase->query("update posts_meta set meta_value=:meta_value where meta_key=:meta_key and meta_value=:last_meta_value and postid=:postid");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }

        $singleDatabase->bind(":meta_value", $meta_value);
        $singleDatabase->bind(":meta_key", $meta_key);
        $singleDatabase->bind(":postid", $postid);
        return $singleDatabase->execute();
    } else {
        add_post_meta($postid, $meta_key, $meta_value);
    }
    return true;
}

function delete_post_meta($postid, $meta_key, $previous_value = null) {
    $singleDatabase = new Database();

    $meta_exists = user_meta_exists($userid, $meta_key, $previous_value);
    if ($meta_exists) {
        if (empty($previous_value)) {
            $singleDatabase->query("delete from posts_meta where meta_key=:meta_key and postid=:postid");
        } else {
            $singleDatabase->query("delete from posts_meta where meta_key=:meta_key and meta_value=:last_meta_value and postid=:postid");
            $singleDatabase->bind(":last_meta_value", $previous_value);
        }
        $singleDatabase->bind(":meta_key", $meta_key);
        $singleDatabase->bind(":postid", $postid);
        return $singleDatabase->execute();
    }
    return true;
}
