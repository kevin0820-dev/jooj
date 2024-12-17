<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)							@
# @ Author_url 1: https://jooj.us                        @
# @ Author_url 2: http://jooj.us/twitter-clone                       @
# @ Author E-mail: sales@jooj.us                                    @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2022 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@

function cl_search_page($keyword = "", $offset = false, $limit = 30) {
    global $db, $cl, $me;

    $data          = array();
    $user_id       = ((not_empty($cl['is_logged'])) ? $me['id'] : false);
    $sql           = cl_sqltepmlate('apps/coins/sql/fetch_page',array(
        't_users'  => T_SYMBOLS,
        't_blocks' => T_BLOCKS,
        'user_id'  => $user_id,
        'limit'    => $limit,
        'offset'   => $offset,
        'keyword'  => $keyword
    ));

    $query_result = $db->rawQuery($sql);

    if (cl_queryset($query_result)) {
        foreach ($query_result as $row) {
            $row['about']            = cl_rn_strip($row['about']);
            $row['about']            = stripslashes($row['about']);
            $row['name']             = cl_strf("%s",$row['fname']);      
            $row['avatar']           = cl_get_media($row['avatar']);
            $row['url']              = cl_link('symbol/' . $row['username']); 
            $row['last_active']      = date("d M, y h:m A",$row['last_active']); 
            $row['is_user']          = false;
            $row['is_following']     = false;
            $row['follow_requested'] = false;
            $row['common_follows']   = array();
            $row['country_a2c']      = fetch_or_get($cl['country_codes'][$row['country_id']], 'us');
            $row['country_name']     = cl_translate($cl['countries'][$row['country_id']], 'Unknown');
            $row['type']             = 'page';
            if (not_empty($user_id)) {
            	$row['is_user']      = ($user_id == $row['id']);
            	$row['is_following'] = cl_is_watching($user_id, $row['id']);

                if (empty($row['is_following'])) {
                    $row['follow_requested'] = cl_watch_requested($user_id, $row['id']);
                }

                $row['common_follows'] = cl_get_common_follows($row['id']);
            }

            $row['about'] = cl_linkify_urls($row['about']);
            $data[]       = $row;
        }
    }

    return $data;
}
