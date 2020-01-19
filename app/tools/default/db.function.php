<?php
function has_whitespace($str) {
    return !(preg_match('/\s/', $str) == 0);
}
function db_raw_select($select,$where,$group_by,$having,$order,$limit){
  $select = value_var($conf,"select");
  $query = "";
  if(has_whitespace($select)){
    $query = $query;
  }
  $where = "";


}

function db_insert($table, $bind = []) {
    $db = new Database();


    $colums = [];

    foreach ($bind as $key => $value) {
        $colums[] = $key;
    }
    $table_col = implode(",", $colums);
    $table_val = ":" . implode(",:", $colums);

    $query = "insert into $table($table_col) values($table_val)";

    if (has_whitespace($table)) {
        $query = $table;
    }


    $db->query($query);

    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    $db->execute();
    return $db->lastInsertId();
}

function db_count($table, $bind = []) {
    $db = new Database();


    $colums = [];
    foreach ($bind as $key => $value) {
        $colums[] = $key . "=:" . $key;
    }
    $table_col = implode(" and ", $colums);
    $where = "";
    if (count($bind) > 0) {
        $where = " where $table_col ";
    }
    $query = "select COALESCE(count(*),0) from $table $where";

    if (has_whitespace($table)) {
        $query = $table;
    }

    $db->query($query);

    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->firstColumn();
}
function db_select($table, $bind = []) {
    $db = new Database();


    $colums = [];
    foreach ($bind as $key => $value) {
        $colums[] = $key . "=:" . $key;
    }
    $table_col = implode(" and ", $colums);
    $where = "";
    if (count($bind) > 0) {
        $where = " where $table_col ";
    }
    $query = "select * from $table $where";

    if (has_whitespace($table)) {
        $query = $table;
    }

    $db->query($query);

    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->resultset();
}
function db_delete($table, $bind = []) {
    $db = new Database();


    $colums = [];
    foreach ($bind as $key => $value) {
        $colums[] = $key . "=:" . $key;
    }
    $table_col = implode(" and ", $colums);
    $where = "";
    if (count($bind) > 0) {
        $where = " where $table_col ";
    }
    $query = "delete from $table $where";

    if (has_whitespace($table)) {
        $query = $table;
    }

    $db->query($query);

    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->execute();
}

function db_update($table, $sets = [], $where = []) {
    $db = new Database();


    $colums = [];
    foreach ($sets as $key => $value) {
        $colums[] = $key . "=:" . $key;
    }
    $table_col = implode(" , ", $colums);
    $set_val = "";
    if (count($sets) > 0) {
        $set_val = " $table_col ";
    }

    $colums = [];
    foreach ($where as $key => $value) {
        $colums[] = $key . "=:" . $key;
    }
    $table_col = implode(" and ", $colums);
    $where_val = "";
    if (count($sets) > 0) {
        $where_val = " where $table_col ";
    }


    $query = "update $table set $set_val $where_val";

    if (has_whitespace($table)) {
        $query = $table;
    }

    $db->query($query);

    foreach ($sets as $key => $value) {
        $db->bind(":$key", $value);
    }
    foreach ($where as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->execute();
}

function db_single($table, $bind = []) {
    $db = new Database();


        $colums = [];
        foreach ($bind as $key => $value) {
            $colums[] = $key . "=:" . $key;
        }
        $table_col = implode(" and ", $colums);

    $where = "";
    if (count($bind) > 0) {
        $where = " where $table_col ";
    }


    $query = "select * from posts $where";
    if (has_whitespace($table)) {
        $query = $table;
    }

    $db->query($query);
    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->single();
}

function db_result($query, $bind = []) {
    $db = new Database();
    $db->query($query);
    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->resultset();
}

function db_column($query, $bind = []) {
    $db = new Database();
    $db->query($query);
    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->columnArray();
}

function db_execute($query, $bind = []) {
    $db = new Database();
    $db->query($query);
    foreach ($bind as $key => $value) {
        $db->bind(":$key", $value);
    }
    return $db->execute();
}
