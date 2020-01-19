<div class="loginBox">
  <form method="POST"
  action="/forgot-password/validate"
  class="submit-jquery-form"
  data-success="forgotPassowrd"
  novalidate
  >

  <div class="w-350 loginBoxItems" id="resetPasswordStepOne">
    <h2>Forgot Password</h2>
    <div >
      <div class="pv-10">
        <div class="errorContainer">
          <div class="value"></div>
        </div>
      </div>
      <div class="form-group">
        <label for="usr">Email ID</label>
        <input type="email"
        autofocus="true"
        placeholder="type email id" .
        data-required="true"
        data-empty="Email id required"
        data-msg="Invalid Email"
        class="form-control forgot-email"
        name="email">
        <div class="text-muted help-text">
          Email reset link be sent
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-primary btn-block">Continue</button>
      </div>
    </div>
  </div>
  <div id="resetPasswordStepTwo" class="w-350 none">
    <h2>Reset Link Sent</h2>
    <p>Password reset link or instruction has been sent.</p>
    <p class='bg-info text-sm p-10'>Link will be valid for 30 minutes.</p>
    <div class="form-group">
      <a href="#" target="_blank" class="btn btn-primary btn-block goto-mail">
        Go to mail.com
      </a>
    </div>
    <a href="/home" class="btn btn-default btn-block">
      Go to home page
    </a>
  </div>
</div>
</div>
