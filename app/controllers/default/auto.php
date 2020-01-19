<?php
/*
 * @AUTHOR Ashish
 * @CONTACT ashishmaurya@outlook.com
 * @ABOUT Auto Class is used to update Posts Status(Host,Services, URL Monitoring)
 * @TODO  Add Proper API Key rather then random  
 */
class auto extends Controller {


  public function all()
  {
    $api_key = value_get("api");
    if(empty($api_key)){
      echo "Rejected : Invalid Api Key";
      return;
    }
    $limit = " limit ".(get_current_page()-1)*POST_PER_PAGE.",".POST_PER_PAGE;
    $limit = POST_PER_PAGE;
    $last_post_updated = get_options("last-post-updated",true,0);

    $post_types = [
      "website",
      "url-monitoring",
      "service"
    ];
    $post_types_str = "'".implode("', '",$post_types)."'";
    $base_query = "select * from posts where postid>:lastid and post_type in ($post_types_str) limit $limit";
    $params = [
      "lastid"=>$last_post_updated,
    ];
    // update_options("last-post-updated",$last_post_updated+POST_PER_PAGE);

    $posts = db_result($base_query,$params);
    foreach($posts as $post){
      $post_type = $post["post_type"];
      if($post_type=="website"){
        update_host_status($post);
      }else if($post_type=="url-monitoring"){
        update_url_status($post);
      }else if($post_type=="service"){
        $parent = get_post($post["parentid"]);
        update_service_status($post,$parent);
      }

    }

    if($posts){
      if(count($posts)>0){
        $last_post = $posts[count($posts)-1];
        update_options("last-post-updated",$last_post["postid"]);
      }
      if(count($posts)<POST_PER_PAGE){
        update_options("last-post-updated",0);
      }
    }
    echo "Done Processing";
  }
  public function host()
  {
    // update_all_host_status();
  }

  public function service() {
    // update_all_host_service();
  }
}
