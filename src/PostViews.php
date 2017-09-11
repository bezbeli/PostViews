<?php

namespace Bezbeli\Stats;

class PostViews
{

    public function getViews($postID, $icon)
    {
        $count_key = 'post_views_count';
        $count = get_post_meta($postID, $count_key, true);
        if ($count=='') {
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
            return $icon . ' 0';
        }
        return $icon . ' ' . $count;
    }

    public function setViews($postID, $interval = 600)
    {
        $user_ip = $_SERVER['REMOTE_ADDR'];
        $key = $user_ip . 'x' . $postID;
        $value = array($user_ip, $postID);
        $visited = get_transient($key);

        if (false === ( $visited )) {
            set_transient($key, $value, $interval);
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

