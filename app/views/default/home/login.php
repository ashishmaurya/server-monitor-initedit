<div class="loginBox" id="particles-js">
    <form method="POST"
          action="/login/validate"
          class="submit-jquery-form"
          data-success="refreshConditional"
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
                <label for="usr">Email</label>
                <input
                    type="email"
                    name="email"
                    class="form-control"
                    placeholder="Enter Your Email ID "
                    data-required="true"
                    autofocus="true"
                    data-empty="Email id required"
                    data-msg="Invalid Email"
                    > 
            </div>
            <div class="form-group">
                <label for="usr">Password

                </label>
                <?php if (EMAIL_ENABLED) { ?>
                    <a href="/forgot-password" class="right">Forgot Password ?</a>
                <?php } ?>
                <input
                    type="password"
                    placeholder="Enter Your Password"
                    name="password"
                    class="form-control"

                    data-empty="Password is required"
                    data-msg="Password is week"
                    data-required="true">
            </div>
            <div class="form-group">
                <button
                    type="submit"
                    class="btn btn-primary btn-block">Login</button>
            </div>
            <div class="form-group">
                <div class="loginDivider">
                    <div class="divider"></div>
                    <span class="center dividerText">New To Our Site?</span>
                </div>
            </div>
            <div class="form-group">
                <a href="/signup" class=" btn btn-default btn-block">
                    Sign Up
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
