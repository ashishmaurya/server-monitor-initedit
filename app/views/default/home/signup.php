<?php
$referralCode = isset($_GET['referral']) ? $_GET['referral'] : "";
?>
<div class="loginBox">
    <form method="POST"
          action="/signup/add"
          class="submit-jquery-form"
          data-success="addeduser"
          novalidate
          >

        <h2 class="text-center loginBox-title">
            <?php echo WEBSITE_NAME; ?>
        </h2>
        <div class="loginBoxItems">

            <div class="form-group">
                <div class="errorContainer">
                    <div class="value"></div>
                </div>
            </div>
            <div class="form-group">
                <label for="usr">Your Name</label>
                <input type="text"
                       data-required="true"
                       data-empty="Name is required"
                       data-msg="Name is required."
                       name="name"
                       class="form-control"
                       autofocus="true"
                       placeholder="Enter Your Name"
                       >
            </div>
            <div class="form-group">
                <label for="usr">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter Your Email ID "
                    data-required="true"
                    data-empty="Email id required"
                    data-msg="Invalid Email"
                    > 
            </div>
            <div class="form-group">
                <label for="usr">Password</label>
                <input type="password"
                       data-required="true"
                       class="form-control new-password"
                       data-empty="Password is required"
                       data-msg="Password is week"
                       name="password"
                       placeholder="Enter Password 5-15 Characters" id="signupPassword" >
            </div>
            <div class="form-group">
                <label for="usr">Confirm Password</label>
                <input type="password"
                       class="form-control"
                       placeholder="type new password again"
                       data-required="true"
                       data-compare=".new-password"
                       data-empty="Confirm Password required"
                       data-msg="Password didn't match"
                       name="new-confirm-password"/>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
            </div>
            <div class="form-group">
                <div class="loginDivider">
                    <div class="divider"></div>
                    <span class="center dividerText">Already Have An Account?</span>
                </div>
            </div>
            <div class="form-group">
                <a href="/login" class="btn btn-default btn-block">
                    Login

                </a>
            </div>
        </div>
    </form>
</div>
<script>
    $(document).ready(function () {
        $("body").attr("id", "body");
        // $(".header-site").hide();
        particlesJS('body', {
            "particles": {
                "number": {
                    "value": 120,
                },
                "opacity": {
                    "value": 0.5,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 1,
                        "opacity_min": 0.1,
                        "sync": false
                    }
                },
                "size": {
                    "value": 3,
                    "random": true,
                    "anim": {
                        "enable": false,
                        "speed": 40,
                        "size_min": 0.1,
                        "sync": false
                    }
                },
            },
            "interactivity": {
                // "detect_on": "canvas",
                "events": {
                    "onhover": {
                        "enable": true,
                        "mode": "repulse"
                    },
                    "resize": true
                },
                "modes": {
                    "repulse": {
                        "distance": 50,
                        "duration": 0.4
                    },
                    "push": {
                        "particles_nb": 4
                    },
                }
            }
        });

    });
</script>

