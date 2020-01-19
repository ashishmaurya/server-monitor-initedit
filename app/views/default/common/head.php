<!DOCTYPE html>
<html class="en-in">
<!--
Developed By : Ashish A. Maurya
Email        : ashishmaurya@outlook.com
Note         : contact me if you would like to develop website or android application for your business
-->
<head>
  <?php
  $version = "v=0.1.1";
  if (DEBUG) {
    $version = "t=" . time();
  }
  ?>
  <!--    Meta Tags-->
  <meta charset="UTF-8">
  <title><?php echo $data['title']; ?></title>
  <meta name="keywords" content="<?php
  echo value_var($data, "description");
  ?> ">
  <meta name="description" content="<?php echo value_var($data, "description"); ?>">

  <meta name="language" content="english">

  <meta http-equiv="Cache-control" content="public">

  <meta name="viewport" content="width=device-width, initial-scale=1">


  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css" />
  <link rel="stylesheet" href="/public/css/dark.style.min.css?<?php echo $version; ?>" type="text/css"/>
  <link rel="stylesheet" href="/public/css/style.css?<?php echo $version; ?>" type="text/css"/>
  <!-- <link rel="stylesheet" href="/public/css/style.css?<?php echo $version; ?>" type="text/css"/> -->
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

  <script src="/public/js/shop.js?<?php echo $version; ?>"></script>
  <script src="/public/js/home.js?<?php echo $version; ?>"></script>
  <script src="/public/js/analytics.google.com.js"></script>
  <script src="/public/js/particles.min.js?<?php echo $version; ?>"></script>
</head>
<?php
$theme = get_user_meta(current_userid(),"theme",true,"lighttheme");
if($theme=="auto"){
  $sunrise = "10:00 pm";
  $sunset = "8:00 am";
  $datetime = date('Y/m/d H:i:s',time());
  $date1 = new DateTime($datetime);
  $date2 = DateTime::createFromFormat('H:i a', $sunrise);
  $date3 = DateTime::createFromFormat('H:i a', $sunset);
  if ($date1 > $date2 && $date1 < $date3)
  {
    $theme = "darktheme";
  }else{
    $theme = "lighttheme";
  }
}
?>
<body class="<?php echo $theme;?> page-<?php echo get_page(); ?>">
