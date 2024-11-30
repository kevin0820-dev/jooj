<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)							@
# @ Author_url 1: https://jooj.us                       @
# @ Author_url 2: http://jooj.us/twitter-clone                      @
# @ Author E-mail: sales@jooj.us                                   @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2021 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@

function cl_get_notifications($args = array()) {
	global $db, $cl, $me;

	$args        = (is_array($args)) ? $args : array();
	$options     = array(
        "offset" => false,
        "limit"  => 10,
        "type"   => "notifs"
    );

    $args   = array_merge($options, $args);
    $offset = $args['offset'];
    $limit  = $args['limit'];
    $type   = $args['type'];
	$sql    = cl_sqltepmlate('apps/notifications/sql/fetch_notifications', array(
		't_notifs' => T_NOTIFS,
		't_blocks' => T_BLOCKS,
		't_users'  => T_USERS,
		'offset'   => $offset,
		'user_id'  => $me['id'],
		'type'     => $type,
		'limit'    => $limit
	));

	$notifs = $db->rawQuery($sql);
	$data   = array();
	$update = array();

	if (cl_queryset($notifs)) {
		foreach ($notifs as $row) {
			$row['url']      = cl_link($row['username']);
			$row['avatar']   = cl_get_media($row["avatar"]);
			$row['time']     = cl_time2str($row["time"]);
			$row['name']     = cl_rn_strip($row['name']);
            $row['name']     = stripslashes($row['name']);
            $row['user_url'] = cl_link($row['username']);

			if (in_array($row['subject'], array('reply', 'repost', 'like', 'mention'))) {
				$row['url']     = cl_link(cl_strf("thread/%d", $row['entry_id']));
				$row['post_id'] = $row['entry_id'];
			}

			else if ($row['subject'] == "ad_approval") {
				$row['url'] = cl_link(cl_strf("ads/%d", $row['entry_id']));
			}

			else if (in_array($row['subject'], array('subscribe_accept', 'subscribe', 'subscribe_request', 'visit'))) {
				$row['user_id'] = $row['entry_id'];
			}

			if ($row['status'] == '0') {
				$update[] = $row['id'];
			}

			$data[] = $row;
		}

		if (not_empty($update)) {
			$db = $db->where('id', $update, 'IN');
			$qr = $db->update(T_NOTIFS, array('status' => '1'));
		}
	}

	return $data;
}

function cl_get_total_notifications($type = false) {
	global $db, $cl, $me;

	$sql_query     = cl_sqltepmlate('apps/notifications/sql/fetch_total', array(
		't_notifs' => T_NOTIFS,
		't_blocks' => T_BLOCKS,
		't_users'  => T_USERS,
		'user_id'  => $me['id'],
		'type'     => $type
	));

	$total  = 0;
	$notifs = $db->rawQueryOne($sql_query);

	if (cl_queryset($notifs) && not_empty($notifs["total"])) {
		$total = $notifs["total"];
	}

	return $total;
}

function cl_notification_post($id = 0) {
	global $db,$cl,$me;

	$data           = array();
	$sql            = cl_sqltepmlate("apps/notifications/sql/fetch_post",array(
		"t_pubs"    => T_PUBS,
        "t_posts"   => T_POSTS,
        "t_blocks"  => T_BLOCKS,
        "t_conns"   => T_CONNECTIONS,
        't_reports' => T_PUB_REPORTS,
        "id"   => $id,
 	));

	$query_res = $db->rawQuery($sql);
    $counter   = 0;

	if (cl_queryset($query_res)) {
		foreach ($query_res as $row) {
			$post_data = cl_raw_post_data($row['publication_id']);
			if (not_empty($post_data) && in_array($post_data['status'], array('active'))) {
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
				$data             = cl_post_data($post_data);
			}
		}
	}

	return $data;
}