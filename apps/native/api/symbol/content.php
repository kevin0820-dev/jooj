<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)							@
# @ Author_url 1: https://jooj.us                       @
# @ Author_url 2: http://jooj.us/twitter-clone                      @
# @ Author E-mail: sales@jooj.us                                    @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2023 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@

$profile_id    = fetch_or_get($_GET["symbol_id"], false);
$profile_uname = fetch_or_get($_GET["username"], false);

if (not_empty($profile_uname) && preg_match('/^[\w]+$/', $profile_uname)) {
    $profile_id = cl_get_symbol_id_by_name(cl_text_secure($profile_uname));
}

if (empty($profile_id) || is_posnum($profile_id) != true) {
	$data['code']    = 400;
    $data['message'] = "User ID is missing or invalid";
    $data['data']    = array();
}

else {
	require_once(cl_full_path("core/apps/symbol/app_ctrl.php"));

	$symbol_data = cl_raw_symbol_data($profile_id); 

	if (not_empty($symbol_data)) {
		$can_view_profile = cl_can_view_profile($symbol_data['id']);
		$data['code']     = 200;
		$data['message']  = "Profile fetched successfully";
		$data['data']     = array(
			'id'          => $symbol_data['id'],
        	'avatar'      => cl_get_media($symbol_data['avatar']),
            'cover'       => cl_get_media($symbol_data['cover']),
            'cover_orig'  => cl_get_media($symbol_data['cover_orig']),
            'first_name'  => $symbol_data['fname'],
			'user_id'  => $symbol_data['user_id'],
        	'user_name'   => $symbol_data['username'],
        	'is_verified' => (($symbol_data['verified'] == '1') ? true : false),
        	'website'     => $symbol_data['website'],
        	'about_you'          => $symbol_data['about'],
        	'gender'             => $symbol_data['gender'],
        	'country'            => $cl['countries'][$symbol_data['country_id']],
            'country_flag'       => cl_banner_url($cl['country_codes'][$symbol_data['country_id']]),
        	'post_count'         => $symbol_data['posts'],
        	'email'              => $symbol_data['email'],
        	'following_count'    => $symbol_data['following'],
        	'follower_count'     => $symbol_data['followers'],
        	'language'           => $symbol_data['language'],
        	'last_active'        => $symbol_data['last_active'],
        	'profile_privacy'    => $symbol_data['profile_privacy'],
        	'member_since'       => date("M Y", $symbol_data['joined']),
        	'is_blocked_visitor' => false,
        	'is_following'       => false,
        	'can_view_profile'   => $can_view_profile
		);

		if (not_empty($cl['is_logged'])) {
			$data['data']['user']['is_blocked_visitor'] = cl_is_blocked($symbol_data['id'], $me['id']);
			$data['data']['user']['is_blocked_profile'] = cl_is_blocked($me['id'], $symbol_data['id']);
			$data['data']['user']['is_following']       = cl_is_following($me['id'], $symbol_data['id']);
		}
	}

	else {
		$data['code']    = 404;
        $data['message'] = "Profile with this ID does not exist";
        $data['data']    = array();
	}
}