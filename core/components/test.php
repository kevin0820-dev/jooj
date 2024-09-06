<?php
function cl_delete_symbol_data($symbol_id = false) {
    global $db;
    if (not_num($symbol_id)) {
        return false;
    }

    else {
        $db        = $db->where('id', $symbol_id);
        $user_data = $db->getOne(T_USERS);

        if (cl_queryset($user_data)) {

            /*===== Delete user notifications =====*/
                $db = $db->where('notifier_id', $symbol_id);
                $qr = $db->delete(T_NOTIFS);

                $db = $db->where('recipient_id', $symbol_id);
                $qr = $db->delete(T_NOTIFS);
            /*====================================*/

            /*===== Delete user bookmarks =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_BOOKMARKS);
            /*====================================*/

            /*===== Delete user reports =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_PROF_REPORTS);
                
                $db = $db->where('profile_id', $symbol_id);
                $qr = $db->delete(T_PROF_REPORTS);
            /*====================================*/

            /*===== Delete user blocks =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_BLOCKS);
                
                $db = $db->where('profile_id', $symbol_id);
                $qr = $db->delete(T_BLOCKS);
            /*====================================*/

            /*===== Delete user aff payouts =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_AFF_PAYOUTS);
            /*====================================*/

            /*===== Delete user wallet history =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_WALLET_HISTORY);
            /*====================================*/

            /*===== Delete user post reposts =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->delete(T_PUB_REPORTS);
            /*====================================*/

            /*===== Delete user ads =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->get(T_ADS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        cl_delete_media($row['cover']);
                    }

                    $db = $db->where('symbol_id', $symbol_id);
                    $qr = $db->delete(T_ADS);
                }
            /*====================================*/

            /*===== Delete banktrans requests =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->get(T_BANKTRANS_REQS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        cl_delete_media($row['receipt_img']);
                    }

                    $db = $db->where('symbol_id', $symbol_id);
                    $qr = $db->delete(T_BANKTRANS_REQS);
                }
            /*====================================*/

            /*===== Delete user likes =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->get(T_LIKES);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        $post_data = cl_raw_post_data($row['pub_id']);

                        if (not_empty($post_data) && ($post_data['symbol_id'] != $symbol_id)) {
                            $num = ($post_data['likes_count'] -= 1);
                            $num = (is_posnum($num)) ? $num : 0;
                            cl_update_post_data($row['pub_id'], array(
                                'likes_count' => $num
                            ));
                        }
                    }

                    $db = $db->where('symbol_id', $symbol_id);
                    $qr = $db->delete(T_LIKES);
                }
            /*====================================*/

            /*===== Delete user reposts =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $db = $db->where('type', 'repost');
                $qr = $db->get(T_POSTS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        $post_data = cl_raw_post_data($row['publication_id']);

                        if (not_empty($post_data) && ($post_data['symbol_id'] != $symbol_id)) {
                            $num = ($post_data['reposts_count'] -= 1);
                            $num = (is_posnum($num)) ? $num : 0;
                            cl_update_post_data($row['publication_id'], array(
                                'reposts_count' => $num
                            ));
                        }
                    }

                    $db = $db->where('symbol_id', $symbol_id);
                    $db = $db->where('type', 'repost');
                    $qr = $db->delete(T_POSTS);
                }
            /*====================================*/
            
            /*===== Delete user publications =====*/
                $db = $db->where('symbol_id', $symbol_id);
                $qr = $db->get(T_PUBS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        if ($row['target'] == 'pub_reply') {
                            cl_update_thread_replys($row['thread_id'], 'minus');
                        }

                        $db = $db->where('publication_id', $row['id']);
                        $qr = $db->delete(T_POSTS);

                        cl_recursive_delete_post($row['id']);
                    }
                }
            /*====================================*/

            /*===== Delete user chats =====*/
                $db = $db->where('user_one', $symbol_id);
                $qr = $db->delete(T_CHATS);

                $db = $db->where('user_two', $symbol_id);
                $qr = $db->delete(T_CHATS);

                $db = $db->where('sent_by', $symbol_id);
                $db = $db->where('sent_to', $symbol_id, '=', 'OR');
                $qr = $db->get(T_MSGS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        if (not_empty($row['media_file'])) {
                            cl_delete_media($row['media_file']);
                        }
                    }

                    $db = $db->where('sent_by', $symbol_id);
                    $db = $db->where('sent_to', $symbol_id, '=', 'OR');
                    $qr = $db->delete(T_MSGS);
                }
            /*====================================*/

            /*===== Delete user connections =====*/
                $db = $db->where('follower_id', $symbol_id);
                $qr = $db->get(T_CONNECTIONS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        $user_data = cl_raw_user_data($row['following_id']);

                        if (not_empty($user_data)) {
                            $num = ($user_data['followers'] -= 1);
                            $num = (is_posnum($num)) ? $num : 0;
                            
                            cl_update_user_data($user_data['id'], array(
                                'followers' => $num
                            ));
                        }
                    }

                    $db = $db->where('follower_id', $symbol_id);
                    $qr = $db->delete(T_CONNECTIONS);
                }

                $db = $db->where('following_id', $symbol_id);
                $qr = $db->get(T_CONNECTIONS);

                if (cl_queryset($qr)) {
                    foreach ($qr as $row) {
                        $user_data = cl_raw_user_data($row['follower_id']);

                        if (not_empty($user_data)) {
                            $num = ($user_data['following'] -= 1);
                            $num = (is_posnum($num)) ? $num : 0;

                            cl_update_user_data($user_data['id'], array(
                                'following' => $num
                            ));
                        }
                    }

                    $db = $db->where('following_id', $symbol_id);
                    $qr = $db->delete(T_CONNECTIONS);
                }
            /*====================================*/

            if (not_empty($user_data["swift"])) {
                $swift_data = cl_init_swift($user_data["swift"]);

                if (not_empty($swift_data)) {
                    foreach ($swift_data as $row) {
                        if ($row["type"] == "image") {
                            cl_delete_media($row["media"]["src"]);
                        }
                        else if($row["type"] == "video") {
                            cl_delete_media($row["media"]["source"]);
                        }
                    }
                }
            }
            

            $db = $db->where('symbol_id', $symbol_id);
            $qr = $db->delete(T_SESSIONS);
            $db = $db->where('id', $symbol_id);
            $qr = $db->delete(T_USERS);

            return true;
        }

        else {
            return false;
        }
    }
}