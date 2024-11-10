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

echo "<script>alert('coins')</script>";

if (empty($cl["is_logged"])) {
	cl_redirect("guest");
}

else {
	require_once(cl_full_path("core/apps/coins/app_ctrl.php"));

	$cl["page_title"] = cl_translate("Coins");
	$cl["page_desc"]  = $cl["config"]["description"];
	$cl["page_kw"]    = $cl["config"]["keywords"];
	$cl["pn"]         = "coins";
	$cl["sbr"]        = true;
	$cl["sbl"]        = true;
	$cl["chats"]      = cl_get_chats(array("user_id" => $me['id']));
	$cl["http_res"]   = cl_template("coins/content");
}