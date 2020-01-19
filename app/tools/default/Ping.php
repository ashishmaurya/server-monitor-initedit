<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ping
 *
 * @author home
 */
class Ping {

    public $count = 2;

    public function get($host) {
        if (is_windows()) {
            $response = $this->getWindows($host);
        } else {
            $response = $this->getLinux($host);
        }
        return $response;
    }

    public function get_string_between($string, $start, $end) {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0)
            return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public function getWindows($host) {
        exec($cmd, $output, $return_code);
        $cmd = "ping -n 2 ";
    }

    public function getLinuxPing($result) {

        $pings = array_filter($result, function($r) {
            if (strpos($r, "icmp_seq") > 0) {
                return true;
            } else {
                return false;
            }
        });
        if (count($pings) > 0) {
            $pings = array_map(function($ping) {
                $res = [];
                $res["status"] = true;
                $res["message"] = "Online";
                $res["time"] = (float) $this->get_string_between($ping, "time=", " ms");

                $ip = $this->get_string_between($ping, "from ", ": ");
                $ip = trim($ip);
                $ip = explode(" ", $ip);
                $ip = $ip[0];
                $res["ip"] = $ip;
                $res["error_code"] = 200;
                $res["error_message"] = "";
                return $res;
            }, $pings);
        } else {
            $pings = [];
            for ($i = 0; $i < $this->count; $i++) {
                $res = [];
                $res["status"] = false;
                $res["message"] = "Offline";
                $res["ip"] = "Offline";
                $res["time"] = 2000;
                $res["error_code"] = 404;
                $res["error_message"] = "Unable to connect to host";

                $pings[] = $res;
            }
        }

        return $pings;
    }

    public function getLinux($host) {
        $cmd = "ping -c $this->count " . $host;
        exec($cmd, $output, $return_code);
        //var_dump($output);
        $pings = $this->getLinuxPing($output);

        $output_str = implode("\n", $output);

        $packet_sent = $this->get_string_between($output_str, "---\n", " packets transmitted");
        $packet_sent = trim($packet_sent);

        $received = $this->get_string_between($output_str, ", ", " received,");
        $received = trim($received);
        if (empty($packet_sent)) {
            $packet_sent = $this->count;
        } else {
            $packet_sent = (int) $packet_sent;
        }
        if (empty($received)) {
            $received = 0;
        } else {
            $received = (int) $received;
        }
        $minimum_time = 0;
        $maximum_time = 0;
        $average_time = 0;
        $total_time = 0;
        $host_ip = "";
        $i = 0;
        foreach ($pings as $ping) {
            if ($i == 0) {
                $minimum_time = $ping["time"];
            } else if ($minimum_time > $ping["time"]) {
                $minimum_time = $ping["time"];
            }
            if ($maximum_time < $ping["time"]) {
                $maximum_time = $ping["time"];
            }
            $total_time+=$ping["time"];
            $host_ip = $ping["ip"];
        }

        $lost_count = $packet_sent - $received;
        $response["pings"] = $pings;
        $response["sent"] = $packet_sent;
        $response["received"] = $received;
        $response["lost"] = $lost_count;
        $response["minimum"] = $minimum_time;
        $response["maximum"] = $maximum_time;
        $response["average"] = ($total_time / $this->count);
        $response["ip"] = $host_ip;

        if ($lost_count != $this->count) {
            $response["status"] = "Online";
        } else if ($lost_count == $this->count) {
            $response["status"] = "Offline";
        } else {
            $response["status"] = "Critical";
        }

        $response["time"] = time();
        $response["code"] = 1;
        return $response;
    }

}
