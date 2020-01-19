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
        <link rel="stylesheet" href="/public/css/style.min.css?<?php echo $version; ?>" type="text/css"/>
        <link rel="stylesheet" href="/public/css/admin.style.min.css?<?php echo $version; ?>" type="text/css"/>
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
        <script src="/public/js/shop.js?<?php echo $version; ?>"></script>
        <script src="/public/js/home.js?<?php echo $version; ?>"></script>
        <script src="/public/js/particles.min.js?<?php echo $version; ?>"></script>
    </head>
    <body class="page-<?php echo get_page(); ?>">
        <div class="left-menu">
            <ul class="list-unstyled">
                <li>
                    <a href="/admin/home">
                        <i class="fa fa-dashboard"></i><br/>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="/admin/posts">
                        <i class="fa fa-mobile"></i><br/>
                        Posts
                    </a>
                </li>
                <li>
                    <a href="/admin/users">
                        <i class="fa fa-user"></i><br/>
                        Users
                    </a>
                </li>
                <li>
                    <a href="/admin/blogger">
                        <i class="fa fa-blogger"></i><br/>
                        Blogger
                    </a>
                </li>
            </ul>
        </div>