<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)                           @
# @ Author_url 1: https://jooj.us                       @
# @ Author_url 2: http://jooj.us/twitter-clone                      @
# @ Author E-mail: sales@jooj.us                                   @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2021 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@

function cl_admin_get_posts($args = array()) {
    global $cl, $me, $db;

    $args           = (is_array($args)) ? $args : array();
    $options        = array(
        "offset"    => false,
        "limit"     => 10,
        "offset_to" => false,
        "order"     => 'DESC',
        "keyword"   => false,
    );

    $args           =  array_merge($options, $args);
    $offset         =  $args['offset'];
    $limit          =  $args['limit'];
    $order          =  $args['order'];
    $keyword        =  $args['keyword'];
    $offset_to      =  $args['offset_to'];
    $data           =  array();
    $t_pubs         =  T_PUBS;
    $sql            =  cl_sqltepmlate('apps/cpanel/posts/sql/fetch_posts',array(
        'offset'    => $offset,
        't_pubs'    => $t_pubs,
        'limit'     => $limit,
        'offset_to' => $offset_to,
        'order'     => $order,
        'keyword'   => $keyword,
    ));

    $data  = array();
    $posts = $db->rawQuery($sql);

    if (cl_queryset($posts)) {
        foreach ($posts as $row) {
            $post_data = cl_raw_post_data($row['id']);
            $post_data['url']           = cl_link(cl_strf('thread/%d', $row['id']));
            $post_data['replys_count']  = cl_number($row['replys_count']);
            $post_data['reposts_count'] = cl_number($row['reposts_count']);
            $post_data['likes_count']   = cl_number($row['likes_count']);
            // $post_data['time']          = date('d M, Y h:m',$row['time']);
            $post_data['is_repost']   = (($row['type'] == 'repost') ? true : false);
            $post_data['is_quote']   = (($row['type'] == 'quote') ? true : false);		/* edited by kevin to quote comment */
            $post_data['is_reposter'] = false;
            $post_data['attrs']       = array();
            $post_data['comment_on']  = null;						/* edited by kevin to fetch comment on (added) */

            if ($post_data['is_repost']) {
                $reposter_data         = cl_user_data($row['user_id']);
                $post_data['reposter'] = array(
                    'name' => $reposter_data['name'],
                    'username' => $reposter_data['username'],
                    'url' => $reposter_data['url'],
                );
            }

            if($post_data['is_quote']){
                $post_data['comment_on']  = cl_get_guest_feed_one($row['comment_on']);
                if($post_data['comment_on']) $post_data['comment_on'] = $post_data['comment_on'][0];		/* edited by kevin to fetch comment on (added) */
            }

            if (isset($me['id']) && $row['user_id'] == $me['id']) {
                $post_data['is_reposter'] = true;
            }

            $post_data['attrs'] = ((not_empty($post_data['attrs'])) ? implode(' ', $post_data['attrs']) : '');
            $data[]             = cl_post_data($post_data);
        }
    }
    
    return $data;
}