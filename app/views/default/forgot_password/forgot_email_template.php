<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Your password</title>
</head>
<body>

  <?php $pwrurl = $data["pwrurl"]; ?>
  <div style="max-width: 600px;width: 60%;margin: 10px auto;padding: 10px;color: #000;">
      <h1 style="text-align: center;"><?php echo WEBSITE_NAME;?></h1>
      Dear user,
      <br/>
      It appears that you have requested a password reset at our web site
      <?php echo $_SERVER["SERVER_NAME"]?>
      <br/><br/>
      To reset your password, please click the link below. If you cannot click it,
      please paste it into your web browser's address bar.
      <br/><br/>
      <div style="text-align: center;">
          <a href="<?php echo $pwrurl; ?>">
              <button style="padding: 10px 30px;
                      background-color: #0066FF;
                      outline: none;
                      border: none;
                      box-shadow: 3px 3px 5px #989696;
                      border-radius: 5px;
                      color: #FFF;
                      cursor: pointer;">Reset Password</button>
          </a>
      </div>
      <div style="margin: 20px 0px;padding: 10px;background: #fff1a8;">NOTE : above link will expire in 30 minutes. </div>
      <br/><br/>Thanks,
      <br/>Team <?php echo WEBSITE_NAME;?>
  </div>
</body>
</html>
