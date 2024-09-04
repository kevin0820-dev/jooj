<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)                           @
# @ Author_url 1: https://jooj.us                       @
# @ Author_url 2: http://jooj.us/twitter-clone                      @
# @ Author E-mail: sales@jooj.us                                    @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2023 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@

if (empty($cl["is_logged"])) {
	cl_redirect("guest");
}
else {

	require_once(cl_full_path("core/apps/wallet/app_ctrl.php"));

	$cl["page_title"]     = cl_translate("Wallet");
	$cl["page_desc"]      = $cl["config"]["description"];
	$cl["page_kw"]        = $cl["config"]["keywords"];
	$cl["pn"]             = "wallet";
	$cl["sbr"]            = true;
	$cl["sbl"]            = true;
	$cl["wallet_history"] = cl_get_account_wallet_history();
	$cl["http_res"]       = cl_template("wallet/content");
}