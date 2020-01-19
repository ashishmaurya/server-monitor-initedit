<div class="container-fluid">
  <div class="row bg">
    <div class="col-sm-9 " >
      <style>
      body{
        background-image:url(/public/images/imgs/billing-pro.png);
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
      }
      </style>
    </div>
    <div class="col-sm-3">
      <div class="billing-table">
        <div class="billing-title bg-primary">Pro</div>
        <div class="billing-price">
          <i class="fa fa-rupee"></i> 2500/month
        </div>
        <ul class="billing-feature list-unstyled">
          <li>
            Unlimited hosts
          </li>
          <li>
            Unlimited services
          </li>
          <li>
            Unlimited host groups
          </li>
          <li>
            Email Notification
          </li>
          <li>
            Activity Monitoring
          </li>
          <li>
            24/7 Service Support
          </li>
          <li>
            Update Interval(2 min)
          </li>
        </ul>
        <div class="billing-footer">
          <form action="/billing/get_pro" method="POST">
            <button class="btn btn-primary">
              Pay using Paypal
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
