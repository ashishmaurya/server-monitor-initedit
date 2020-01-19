<?php

function get_current_page($key = "page") {
    $num = value_get("page", 0);
    if (is_numeric($num)) {
        if ($num > 0) {
            return $num;
        }
    }
    return 1;
}

function get_info() {
    $general = get_options("general", TRUE, "");
    return $general;
}

function is_account_active($user) {
    if ($user["status"] == "Enabled") {
        return true;
    } else {
        return false;
    }
}
