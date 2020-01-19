<?php
$CURRENT_PAGE = get_current_page();
$TOTAL_POST = value_var($data,"total",0);
$PER_PAGE = value_var($data,"per_page",POST_PER_PAGE);
$base = get_request_uri();
$gets = $_GET;
$gets_str = "";
unset($gets["url"]);
unset($gets["page"]);
if(count($gets)>0){

  $gets_str = "?".implode("&",$gets);
}
$base_full = $base.(count($gets)==0)?"?":"?".$gets_str;
// print_r($_SERVER);
?>
<?php if($TOTAL_POST>$PER_PAGE){ ?>
  <div class="row">
    <div class="col-sm-12">

      <a href="<?php echo $base_full;?>page=<?php echo $CURRENT_PAGE-1;?>"
        class="btn btn-default"
        <?php echo ($CURRENT_PAGE==1)?"disabled":"";?>
        >
        Previous
      </a>
      <a href="<?php echo $base_full;?>page=<?php echo $CURRENT_PAGE+1;?>"
        class="btn btn-default right"
        <?php echo (($CURRENT_PAGE)*$PER_PAGE>$TOTAL_POST)?"disabled":"";?>
        >
        Next
      </a>
    </div>
  </div>
<?php }?>
