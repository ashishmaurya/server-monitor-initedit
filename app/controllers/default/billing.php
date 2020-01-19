<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Billing page is used to bill the customer
 * @TODO   Remove this page and add Donate Page(Paypal)
 */
class billing extends Controller
{
    public function index()
    {
        login_require();
        $this->view("common/head", ["title" => "billing","description"=>" Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("billing/home");
        $this->view("common/footer");
    }
    public function pro()
    {
        login_require();
        $this->view("common/head", ["title" => "billing","description"=>" Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("billing/pro");
        $this->view("common/footer");
    }
    public function primium()
    {
        login_require();
        $this->view("common/head", ["title" => "billing","description"=>" Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("billing/primium");
        $this->view("common/footer");
    }

    public function confirm()
    {
        $this->view("common/head", ["title" => "Success Payment","description"=>" Home Page"]);
        $this->view("common/header", ["category" => 'home']);
        $this->view("billing/confirm");
        $this->view("common/footer");
        $res = [
          "post"=>$_POST,
          "get"=>$_GET,
          "request"=>$_REQUEST,
        ];
        add_options("payment-response",json_encode($res));

        $userid = current_userid();
        add_user_meta($userid,"payment-response",json_encode($res));
    }

    public function get_pro()
    {
      $user = get_user(current_userid());
      if ($user) {
          extract($user);
          $query = array();
          $query['notify_url'] = get_home_uri()."billing/confirm";
          $query['return'] = get_home_uri()."billing/confirm";
          $query['cancel_return'] = get_home_uri()."billing/pro";
          $query['cmd'] = '_xclick-subscriptions';
          $query['item_name'] = 'Pro Server Monitoring Plan';
          $query['item_number'] = 'Pro';
          $query['business'] = 'ashish_us@maurya.com';
          $query['currency_code'] = 'USD';
          $query['a3'] = '150';
          $query['p3'] = '1';
          $query['t3'] = 'M';
          $query['custome'] = current_userid();
          $query['invoice'] = "INVOICE-".current_userid();
          // Prepare query string
          $query_string = http_build_query($query);
          header('Location: https://sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
      }
    }

    public function get_primium()
    {
      $user = get_user(current_userid());
      if ($user) {
          extract($user);
          $query = array();
          $query['notify_url'] = get_home_uri()."billing/confirm";
          $query['return'] = get_home_uri()."billing/confirm";
          $query['cancel_return'] = get_home_uri()."billing/pro";
          $query['cmd'] = '_xclick-subscriptions';
          $query['item_name'] = 'Primium Server Monitoring Plan';
          $query['item_number'] = 'Primium';
          $query['business'] = 'ashish_us@maurya.com';
          $query['currency_code'] = 'USD';
          $query['a3'] = '320';
          $query['p3'] = '1';
          $query['t3'] = 'M';
          $query['custome'] = current_userid();
          // Prepare query string
          $query_string = http_build_query($query);
          header('Location: https://sandbox.paypal.com/cgi-bin/webscr?' . $query_string);
      }
    }
}
