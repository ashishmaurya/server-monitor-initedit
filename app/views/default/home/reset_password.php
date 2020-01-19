<div class="loginBox">
    <form method="POST"
          action="/reset-password/reset"
          class="submit-jquery-form"
          data-success="resetPassword"
          novalidate
          >
        <br/>
        <h2 class="text-center">Reset Password</h2>
        <div class="loginBoxItems" id="resetStepOne">
            <div class="errorContainer">
                <div class="value"></div>
            </div>
            <input type="hidden" name="resetMD" value="<?php echo value_get("q"); ?>" id="resetMessageDigest">
            <div class="form-group">
                <label for="usr">Email ID</label>
                <input type="email"
                       autofocus="true"
                       placeholder="type email id" .
                       data-required="true"
                       data-empty="Email id required"
                       data-msg="Invalid Email"
                       class="form-control"
                       name="email">
            </div>
            <div class="form-group">
                <label for="usr">New Password</label>
                <input type="password"
                       name="password"
                       data-required="true"
                       data-empty="Password required"
                       data-msg="Password is week"
                       placeholder="type new password"
                       class="form-control"
                       id="new-password">
            </div>
            <div class="form-group">
                <label for="usr">Confirm New Password</label>
                <input type="password"
                       name="confirmPassword"
                       data-required="true"
                       data-empty="Confirm Password required"
                       data-msg="Password did not match"
                       placeholder="type password again"
                       class="form-control"
                       data-compare="#new-password"/>
            </div>
            <div class="form-group">
                <input type="submit" value="Reset" class="btn btn-primary btn-block"> 
            </div>
        </div>
        <div class="loginBoxItems none" id="resetStepTwo">
            <div class="form-group">
                Password changed successfully.
            </div>
            <div class="form-group">
                <a href="/login" class="btn btn-primary">Login Now</a> 
            </div>
        </div>
    </form>
</div>
