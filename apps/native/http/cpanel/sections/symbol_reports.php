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

require_once(cl_full_path("core/apps/cpanel/symbol_reports/app_ctrl.php"));

$cl['total_reports']   = cl_admin_get_total_symbol_reports();
$cl['account_reports'] = cl_admin_get_symbol_reports();
$cl['http_res']        = cl_template("cpanel/assets/symbol_reports/content");