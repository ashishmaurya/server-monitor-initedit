<!DOCTYPE html>
<html class="en-in">
<!--
Developed By : Ashish A. Maurya
Email        : ashishmaurya@outlook.com
Note         : contact me if you would like to develop website or android application for your business
-->
<?php
$errors = [];
$passed = [];

if(is_writable("../app/tools/default/")){
  $passed[] = "Writable : app/tools/default Directory is writable ";
}else{
  $errors[] = "Writable : app/tools/default Directory is not writable";
}
if(is_writable("../app/")){
  $passed[] = "Writable : App Directory is writable ";
}else{
  $errors[] = "Writable : App Directory is not writable";
}
if(class_exists("mysqli")){
  $passed[] = "Mysqli Installed ";
}else{
  $errors[] = "Mysqli is not installed";
}
if(extension_loaded('mysqli')){
  $passed[] = "Mysql Database Installed ";
}else{
  $errors[] = "Mysql Database is not installed";
}



?>
<head>
  <?php
  $version = "v=0.1.1";
  if (true) {
    $version = "t=" . time();
  }
  ?>
  <title>Install</title>
  <meta name="language" content="english">

  <meta http-equiv="Cache-control" content="public">

  <meta name="viewport" content="width=device-width, initial-scale=1">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.9.0/moment-with-locales.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/3.1.3/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/css/bootstrap-datepicker.min.css" />
  <link rel="stylesheet" href="/public/css/dark.style.min.css?<?php echo $version; ?>" type="text/css"/>
  <link rel="stylesheet" href="/public/css/style.min.css?<?php echo $version; ?>" type="text/css"/>
  <script src="/public/js/shop.js?<?php echo $version; ?>"></script>
  <script src="/public/js/home.js?<?php echo $version; ?>"></script>
  <script src="/public/js/particles.min.js?<?php echo $version; ?>"></script>
  <style>
    .install-box{
      padding:20px;
      box-shadow:0px 0px 2px 3px #999;
      max-width:350px;
      margin:80px auto;
    }
    .none{
      display:none;
    }
  </style>
  <script>
  function showBox(selector){
    $(".install-box>div").hide();
    $(".install-box>"+selector).show();
  }
  function checkedDB(data){
    if(data.code==1){
      addMessageBox(0,data.message);
      showBox(".website-info");
    }
  }
  function confirmInstall(){
    var data = {};
    data.db = getFormdata($(".db-info form"));
    data.website = getFormdata($(".website-info form"));
    $(".btn-primary").prop("disabled",true);
    $.post("/install/confirm.php",data,function(response){
      console.log(response);
      response = JSON.parse(response);
      console.log(response);
      if(response.code==1){
        showBox(".final-info");
        if(!response.hasHtaccess){
          $(".htaccess").show();
        }
        if(!response.hasVariable){
          $(".variable").show();
        }
      }else{
        $(".force-delete").val("1");
        $(".confirmErrorContainer").show().find(".value").html(response.message);
      }
    }).always(function(){
      $(".btn-primary").prop("disabled",false);
    });
    return false;
  }
  </script>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-12">
        <div class="install-box">
          <h2>Install Monitoring Tool</h2>
          <div>
              <?php 
              var_dump(file_exists("../htaccess_sample.txt"));
              var_dump(copy("../htaccess_sample.txt","../.htaccess"));
              ?>
          </div>
          <div class="errorContainer">
            <div class="value"></div>
          </div>
          <div class="final-info none">
            <h4 class="text-center">Congratulations...</h4>
            <p>Server Monitor was installed successfully</p>
            <div class="success">
              <a href="/" class="btn btn-primary btn-block">
                Go To Home Page
              </a>
            </div>
            <div class="well bg-danger none htaccess">
              Unable to copy .htaccess<br/>
              Rename .htaccess_sample to .htaccess
            </div>
            <div class="well bg-danger none variable">
              Unable to create "Variable.php"<br/>
              <a href="/install">Install Again</a>
            </div>
          </div>
          <div class="home-info" >
            <h4>Welcome to Monitoring Tool<br/>
              <small>By <a href="https://initedit.com">initedit</a></small>
            </h4>
            <p>Keep these thing in mind</p>
            <div>
              <ul>
                <li><strong>It's free and always will be.</strong></li>
                <li>Keep database name ready</li>
                <li>Please delete "install" directory after installing</li>
                <li>Enable curl with https support</li>
              </ul>
            </div>
            <div class="clearfix">
              <button class="btn btn-primary right" onclick="showBox('.pre-condition-check')">Next</button>
            </div>
          </div>
          <div class='pre-condition-check none'>
            <h4>Pre Conditions</h4>
            <div>
              <ul class="list-group">

                <?php foreach($errors as $error){ ?>
                  <li class="list-group-item-danger list-group-item"><?php echo $error;?></li>
                <?php }?>
                <?php foreach($passed as $pass){ ?>
                  <li class="list-group-item-success list-group-item"><?php echo $pass;?></li>
                <?php }?>
              </ul>
            </div>
            <div class="clearfix">
              <button class="btn btn-primary right"
                <?php echo count($errors)>0?"disabled":""?>
               onclick="showBox('.db-info')">Next</button>
            </div>
          </div>
          <div class='db-info none'>
            <form method="POST"
            action="/install/checkdb.php"
            class="submit-jquery-form"
            data-success="checkedDB">
            <div class="form-group errorContainer">
              <div class="value"></div>
            </div>

            <div class="form-group">
              <label for="usr">Hostname</label>
              <input type="text"
              placeholder="type database name" .
              data-required="true"
              value="localhost"
              class="form-control" name="db_host">
            </div>
            <div class="form-group">
              <label for="usr">DB Name</label>
              <input type="text"
              placeholder="type database name"
              data-required="true"
              value=""
              class="form-control" name="db_name">
            </div>
            <div class="form-group">
              <label for="usr">DB Username</label>
              <input type="text"
              placeholder="type username"
              data-required="true"
              class="form-control"
              value=""
              name="db_user">
            </div>
            <div class="form-group">
              <label for="usr">DB Password</label>
              <input type="password"
              placeholder="type password"
              class="form-control"
              name="db_pass">
            </div>
            <div class="form-group">
              <button class="btn btn-primary lg" type="submit">
                Next
              </button>
            </div>
          </form>
        </div>
        <div class='website-info none'>
          <form method="POST" onsubmit="return confirmInstall()">
            <div class="form-group errorContainer confirmErrorContainer">
              <div class="form-group">
                <button class="btn btn-primary btn-block" type="submit">
                  force install
                </button>
              </div>
              <div class="value"></div>
            </div>
              <input type="hidden" value="0" name="force" class="force-delete"/>
            <div class="form-group">
              <label for="usr">Website Name</label>
              <input type="text"
              value="Server Monitor"
              placeholder="type website name" .
              data-required="true"
              class="form-control" name="websitename">
            </div>
            <div class="form-group">
              <label for="usr">Post Per Page</label>
              <input type="number"
              value="15"
              placeholder="default:15" .
              data-required="true"
              class="form-control" name="postperpage">
            </div>
            <div class="form-group">
              <button class="btn btn-primary lg" type="submit">
                Install
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
</body>
</html
