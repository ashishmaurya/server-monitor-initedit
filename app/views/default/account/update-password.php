<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view("account-nav") ?>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-6">
                    <form method="POST"
                          action="/account/edit-password"
                          class="submit-jquery-form"
                          data-success="refreshConditional">
                        <div class="form-group errorContainer">
                            <div class="value"></div>
                        </div>
                        <div class="form-group">
                            <label for="usr">Old Password</label>
                            <input type="password"
                                   class="form-control"
                                   placeholder="type old password"
                                   data-required="true"
                                   data-empty="Old Password required"
                                   name="old-password"/>
                        </div>
                        <div class="form-group">
                            <label for="usr">New Password</label>
                            <input type="password"
                                   class="form-control new-password"
                                   placeholder="type new password"
                                   data-required="true"
                                   data-empty="New Password required"
                                   name="new-password"/>
                        </div>
                        <div class="form-group">
                            <label for="usr">Confirm New Password</label>
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
                            <button class="btn btn-primary lg" type="submit">
                                Update Password
                            </button>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
