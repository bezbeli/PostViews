<?php
namespace Bezbeli\Stats;

class PostViews
{

    // function to display number of posts.
    public function get($postID)
    {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if ($count=='') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return "0 View";
        }
        return $count.' Views';
    }

    // function to count views.
    public function set($postID)
    {
        $user_ip = $_SERVER['REMOTE_ADDR']; //retrieve the current IP address of the visitor
        $key = $user_ip . 'x' . $postID; //combine post ID & IP to form unique key
        $value = array($user_ip, $postID); // store post ID & IP as separate values (see note)
        $visited = get_transient($key); //get transient and store in variable

        if ( false === ( $visited ) ) {
            set_transient( $key, $value, 60*2 );
            $count_key = 'post_views_count';
            $count = get_post_meta($postID, $count_key, true);
            if ($count=='') {
                $count = 0;
                delete_post_meta($postID, $count_key);
                add_post_meta($postID, $count_key, '0');
            } else {
                $count++;
                update_post_meta($postID, $count_key, $count);
            }
        }
    }
}
