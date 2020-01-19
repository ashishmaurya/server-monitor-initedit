<div class="container-fluid">
    <div class="row pv-10">
        <div class="col-sm-3">
            <?php get_nav_view(); ?>
        </div>
        <div class="col-sm-9">
            <div class="row">
                <div class="col-sm-12">
                    <form method="POST"
                          action="/posts/addhostgroup"
                          class="submit-jquery-form"
                          data-success="refreshConditional">
                        <div class="form-group errorContainer">
                            <div class="value"></div>
                        </div>
                        <div class="form-group">
                            <label for="usr">Host Group Name</label>
                            <input type="text" placeholder="type group name" value="" required="true"
                                   data-required="true"
                                   data-empty="Host Group is required"
                                   data-msg="Invalid Host Group"
                                   class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label for="usr">Description</label>
                            <textarea
                                placeholder="write about this group"
                                class="form-control resize-none" 
                                rows="5"
                                name="description"></textarea>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-primary lg" type="submit">
                                Add Host Group
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
