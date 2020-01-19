<?php
$user = get_user(current_userid());
extract($user);
$userTheme = get_user_meta($userid, "theme", true, "");
?>
<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view("account-nav") ?>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6">
                    <form method="POST"
                          action="/account/updatebasic"
                          class="submit-jquery-form"
                          data-success="refreshConditional">
                        <div class="form-group errorContainer">
                            <div class="value"></div>
                        </div>
                        <div class="form-group">
                            <label for="usr">Name</label>
                            <input type="text"
                                   value="<?php echo $name; ?>"
                                   class="form-control"
                                   data-required="true"
                                   data-empty="Name is required"
                                   name="name"/>
                        </div>
                        <div class="form-group">
                            <label for="usr">Email</label>
                            <input type="email"
                                   value="<?php echo $email; ?>"
                                   class="form-control"
                                   data-required="true"
                                   data-empty="Email is required"
                                   data-msg="Email is invalid"
                                   name="email"/>
                        </div>
                        <div class="form-group">
                            <span class="bold">Theme</span>
                            <select name="theme" class="right">
                                <option value="lighttheme">Light Theme</option>
                                <option value="darktheme"
                                <?php echo ($userTheme == "darktheme") ? "selected" : ""; ?>
                                        >Dark Theme</option>
                                <option value="auto"
                                <?php echo ($userTheme == "auto") ? "selected" : ""; ?>
                                        >Auto</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary lg" type="submit">
                                Update Account
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
