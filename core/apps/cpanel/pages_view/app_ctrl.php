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

function cl_admin_get_symbols($args = array()) {
    global $cl,$me,$db;

    $args           = (is_array($args)) ? $args : array();
    $options        = array(
        "offset"    => false,
        "limit"     => 7,
        "offset_to" => false,
        "order"     => 'DESC',
        "filter"    => array(),
    );

    $args           = array_merge($options, $args);
    $offset         = $args['offset'];
    $limit          = $args['limit'];
    $order          = $args['order'];
    $filter         = $args['filter'];
    $offset_to      = $args['offset_to'];
    $data           = array();
    $t_symbols        = T_SYMBOLS;
    $sql            = cl_sqltepmlate('apps/cpanel/pages_view/sql/fetch_site_users',array(
        'offset'    => $offset,
        't_symbols'   => $t_symbols,
        'limit'     => $limit,
        'offset_to' => $offset_to,
        'order'     => $order,
        'filter'    => $filter,
    ));

    $data  = array();
    $users = $db->rawQuery($sql);

    if (cl_queryset($users)) {
        foreach ($users as $row) {
            // $row['username'] = 'symbol/' . $row['username'];
            $row['url']         = cl_link('symbol/' . $row['username']);
            $row['avatar']      = cl_get_media($row['avatar']);
            $row['last_active'] = date('d M, Y h:m',$row['last_active']);
            $banner_code        = fetch_or_get($cl['country_codes'][$row['country_id']], 'us');
            $row['banner']      = cl_banner($banner_code);
            $data[]             = $row;
        }
    }

    return $data;
}