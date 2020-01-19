<?php
if(SessionManagement::sessionExists("app")){
    return;
}
if(isset($_GET["p"])){
    include "footer-new.php";
        return;
}
?><footer>
    <div class="footerSpace"></div>
    <div class="footerSpaceSecondary"></div>

    <div class="footerItemContainer">
        <div class="shopItemContainer">
            <div class="headerItem">Shop</div>
            <ul class="vl shopItems">
                <li><a href="/about-us">About Us</a> </li>
                <li><a href="/help">Help</a></li>
                <li><a href="/privacy">Privacy</a></li>
                <li><a href="/terms-conditions">Terms & Condition</a></li>
            </ul>
        </div>
        <div class="addressItemContainer">
            <div class="headerItem">Address</div>
            <div class="footerAddress"><?php echo SHOP_ADDRESS;?></div>
        </div>
        <div class="contactItemContainer">
            <div class="headerItem">Contact</div>
            <div><i class="fa fa-phone paddingSmallRight"></i><?php echo PHONE_NUMBER;?></div>
        </div>
    </div>

    <h4 class="text-center footer-quote">Eat healthy, Be healthy.</h4>


</footer>