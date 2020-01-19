<?php

/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Used to send reset password
 * @TODO   Prevent Spamming by tracking user session count
 */

class forgot_password extends Controller {

    /*
     * @AUTHOR Ashish
     * @ABOUT Show Forgot Password Interface
     * @TODO   Prevent Spamming
     */
    public function index() {
        if (is_login()) {
            header("Location: /home");
        }
        if (!EMAIL_ENABLED) {
            return;
        }
        $this->view("common/head", ["title" => "Forgot Password", "description" => " Forgot Password Page"]);
        $this->view("common/header");
        $this->view("forgot_password/forgot_password");
        $this->view("common/footer");
    }

    /*
     * @AUTHOR Ashish
     * @ABOUT description
     * @TODO   Testing Sub
     */

    public function sendemail() {
        // $this->loadLib("phpmail/PHPMailerAutoload");
        // $this->view("forgot_password/sendemail");
    }
    
    /*
     * @AUTHOR Ashish
     * @ABOUT Used to send reset email
     * @TODO   Try to add proper valid certificate page 
     * and allow ssl verification
     */

    public function validate() {
        if (!EMAIL_ENABLED) {
            return;
        }
        $email = value_post("email");
        $result = ["code" => 100, "message" => "Unknown Error"];
        if (empty($email)) {
            $result = ["code" => 102, "message" => "Email ID Is Required."];
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $result = ["code" => 104, "message" => "Email ID is not valid."];
        } else {

            $user = db_single("select * from users where email=:email", [
                "email" => $email
            ]);
            if ($user) {
                $date = new DateTime();
                $result = $date->format('Y-m-d-H-i-s');
                $salt = "498#2D83B631%38" . $result . rand(0, 10000);
                $password = hash('sha512', $salt . $email);
                $server_name = $_SERVER['SERVER_NAME'];
                $weburl = "http://" . $_SERVER['SERVER_NAME'];
                $pwrurl = $weburl . "/reset-password?q=" . $password;
                $_pwrurl = $pwrurl;
                $mailbody = $this->getview("forgot_password/forgot_email_template", ["pwrurl" => $_pwrurl]);
                $this->loadLib("phpmail/PHPMailerAutoload");
                $mail = new PHPMailer;
                $mail->isSMTP();                            // Set mailer to use SMTP
                $mail->Host = RESETHOST;             // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                     // Enable SMTP authentication
                $mail->Username = EMAIL;          // SMTP username
                $mail->Password = PASSWORD; // SMTP password
                $mail->SMTPSecure = EMAILENCRYPT;                  // Enable TLS encryption, `ssl` also accepted
                $mail->Port = EMAILPORT;                          // TCP port to connect to

                $mail->setFrom('reset@' . $server_name, $server_name);
                $mail->addReplyTo('no-reply@' . $server_name, $server_name);
                $mail->addAddress($email);
                $mail->isHTML(true);
                $mail->Subject = 'Reset Password - ' . $server_name;
                $mail->Body = $mailbody;

                $mail->smtpConnect(
                        array(
                            "ssl" => array(
                                "verify_peer" => false,
                                "verify_peer_name" => false,
                                "allow_self_signed" => true
                            )
                        )
                );

                if (!$mail->send()) {
                    //To Debug Email Error
                    // $result["message"]="Mailer Error: " . $mail->ErrorInfo;
                    $result["message"] = "Unable to send mail";
                } else {
                    $result = [];
                    $result["code"] = 1;
                    $result["message"] = "E-Mail has been sent";
                    $time = time();

                    $user_reset = [];
                    $user_reset["key"] = $salt;
                    $user_reset["value"] = $password;
                    $user_reset["email"] = $email;
                    $user_reset["time"] = $time;
                    add_user_meta($user["userid"], "reset-pwd", json_encode($user_reset));
                }

            } else {
                $result = ["code" => 106, "message" => "Email is not registered"];
            }
        }
        echo json_encode($result);
    }

}
