<?php 
# @*************************************************************************@
# @ Software author: JOOJ Team (JOOJ.us)                                    @
# @ Author_url 1: https://jooj.us                                           @
# @ Author_url 2: http://jooj.us/twitter-clone                              @
# @ Author E-mail: sales@jooj.us                                            @
# @*************************************************************************@
# @ JOOJ Talk - The Ultimate Modern Social Media Sharing Platform           @
# @ Copyright (c) 2020 - 2023 JOOJ Talk. All rights reserved.               @
# @*************************************************************************@


$cl["app_statics"] = array(
	"scripts" => array(
		cl_static_file_path("apps/cpanel/statics/js/libs/bootstrap-select-v1.13.9.min.js"),
		cl_static_file_path("statics/js/libs/jquery-plugins/jquery.form-v4.2.2.min.js")
	),
	"styles" => array(
		cl_static_file_path("apps/cpanel/statics/css/libs/bootstrap-select-v1.13.9.min.css")
	)
);

require_once(cl_full_path("core/apps/cpanel/dashboard/app_ctrl.php"));
require_once(cl_full_path("core/apps/cpanel/pages_view/app_ctrl.php"));
$cl['total_symbols'] = cl_admin_total_symbols();
$cl['site_symbols']  = cl_admin_get_symbols(array('limit' => 7)); 

$cl['http_res'] = cl_template("cpanel/assets/pages_view/content");