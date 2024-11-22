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

	require_once(cl_full_path("core/apps/coins/app_ctrl.php"));

	$cl["page_title"] = cl_translate("Coins");
	$cl["page_desc"]  = $cl["config"]["description"];
	$cl["page_kw"]    = $cl["config"]["keywords"];
	$cl["pn"]         = "coins";
	$cl["sbr"]        = true;
	$cl["sbl"]        = true;
	$cl["search_query"] = fetch_or_get($_GET['q'], "");
	$cl["query_result"] = array();

	if (not_empty($cl["search_query"])) {
		$cl["search_query"] = cl_text_secure($cl["search_query"]);
		$cl["page_title"]   = $cl["search_query"];
		$cl["search_query"] = cl_croptxt($cl["search_query"], 32);
	}
	$cl["query_result"] = cl_search_page($cl["search_query"], false, 30);
	$cl["http_res"] = cl_template("coins/content");