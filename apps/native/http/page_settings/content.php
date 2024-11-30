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

$cl["page_title"] = cl_translate("Account settings");
$cl["page_desc"] = $cl["config"]["description"];
$cl["page_kw"] = $cl["config"]["keywords"];
$cl["pn"] = "page_settings";
$cl["sbr"] = true;
$cl["sbl"] = true;
$cl["blocked_users"] = cl_get_blocked_users();
$cl["settings_app"] = fetch_or_get($_GET["sapp"], false);
$cl["settings_app"] = (not_empty($cl["settings_app"])) ? cl_text_secure($cl["settings_app"]) : 0;
$cl["settings_apps"] = array("name", "email", "user_id", "siteurl", "bio", "gender", "password", "language", "country", "city", "verification", "privacy", "notifications", "blocked", "delete", "information", "email_notifs");


$symbol_id = fetch_or_get($_GET["symbol_id"], false);
$symbol_username = fetch_or_get($_GET["symbol_username"], false);




if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["symbol_id"])) {
        $symbol_id = $_POST["symbol_id"];
        if (isset($_FILES['avatar']) && not_empty($_FILES['avatar']['tmp_name'])) {
        } 
        else {
            if (isset($_POST["name"]) && isset($_POST["raw_sname"]) && isset($_POST["email"]) && isset($_POST["about"]) && isset($_POST["country"]) && isset($_POST["website"]) && isset($_POST["city"])) {
                $data = array(
                    "fname" => $_POST["name"],
                    "username" => $_POST["raw_sname"],
                    "email" => $_POST["email"],
                    "about" => $_POST["about"],
                    "country_id" => $_POST["country"],
                    "website" => $_POST["website"],
                    "city" => $_POST["city"],
                    "symbol_fetch" => $_POST['symbol_fetch'],
                    "fetch_from" => $_POST['fetch_from'],
                    "symbol_fetch_gecko" => $_POST['symbol_fetch_gecko'],
                    "symbol_price" => $_POST['symbol_price'],
                );
                if (cl_update_symbol_data($symbol_id, $data)) {
                    echo "";
                } else {
                    echo "<p class='text-red-600'>Произошла ошибка при обновлении данных символа.</p>";
                }
            }
            else {
            }
        }
    }
}
if(isset($_POST["id"])){
    $data = array(
        "title" => $_POST["title"],
        "answer" => $_POST["answer"],
    );
    cl_update_question_data($_POST["id"], $data);
}
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['avatar']) && not_empty($_FILES['avatar']['tmp_name'])) {
    if (isset($_POST["symbol_id"])) {
        $symbol_id = $_POST["symbol_id"];

        $file_info = array(
            'file' => $_FILES['avatar']['tmp_name'],
            'size' => $_FILES['avatar']['size'],
            'name' => $_FILES['avatar']['name'],
            'type' => $_FILES['avatar']['type'],
            'file_type' => 'thumbnail',
            'folder' => 'avatars',
            'slug' => 'avatar',
            'crop' => array('width' => 512, 'height' => 512),
            'allowed' => 'jpg,png,jpeg,gif'
        );

        $file_upload = cl_upload($file_info);

        if (not_empty($file_upload['cropped'])) {
            // Обновляем путь к аватару пользователя в базе данных
            if (cl_update_symbol_data($symbol_id, array('avatar' => $file_upload['cropped']))) {
                // Проверяем наличие данных о символе
                if (isset($cl['symbol']['raw_avatar'])) {
                    // Удаляем предыдущий аватар, если он существует
                    cl_delete_media($cl['symbol']['raw_avatar']);
                }
                // Выводим сообщение об успешной загрузке аватара

            } else {
                echo "<p class='text-red-600'>Произошла ошибка при обновлении аватара.</p>";
            }
        } else {

        }
    } else {


    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['cover']) && not_empty($_FILES['cover']['tmp_name'])) {
    if (isset($_POST["symbol_id"])) {
        $symbol_id = $_POST["symbol_id"];

        $file_info = array(
            'file' => $_FILES['cover']['tmp_name'],
            'size' => $_FILES['cover']['size'],
            'name' => $_FILES['cover']['name'],
            'type' => $_FILES['cover']['type'],
            'file_type' => 'image',
            'folder' => 'covers',
            'slug' => 'cover',
            'allowed' => 'jpg,png,jpeg,gif',
            'aws_uploadfile' => "N"
        );

        $file_upload = cl_upload($file_info);

        if (not_empty($file_upload['filename'])) {
            try {
                require_once (cl_full_path("core/libs/PHPgumlet/ImageResize.php"));
                require_once (cl_full_path("core/libs/PHPgumlet/ImageResizeException.php"));

                $prof_cover = new \Gumlet\ImageResize(cl_full_path($file_upload['filename']));
                $sw = $prof_cover->getSourceWidth();
                $sh = $prof_cover->getSourceHeight();
                $data['sw'] = $sw;
                $data['sh'] = $sh;

                $path_info = explode(".", $file_upload['filename']);
                $filepath = fetch_or_get($path_info[0], "");
                $file_ext = fetch_or_get($path_info[1], "");
                $cropped_cover = cl_strf("%s_600x200.%s", $filepath, $file_ext);
                $data['status'] = 200;

                $prof_cover->crop(600, 200, true);
                $prof_cover->save(cl_full_path($cropped_cover));

              
                cl_delete_media($me['raw_cover']);
                cl_delete_media($me['cover_orig']);

              
                cl_update_symbol_data($symbol_id, array(
                    'cover' => $cropped_cover,
                    'cover_orig' => $file_upload['filename']
                )
                );

                if ($sw != 600) {
                    $prof_cover = new \Gumlet\ImageResize(cl_full_path($file_upload['filename']));
                    $prof_cover->resize(600, (($sh * 600) / $sw), true);
                    $prof_cover->save(cl_full_path($file_upload['filename']));
                }

                if ($cl['config']['as3_storage'] == 'on') {
                    cl_upload2s3($cropped_cover);
                    cl_upload2s3($file_upload['filename']);
                }

         

            } catch (Exception $e) {
                $data['err_code'] = "invalid_req_data";
                $data['err_message'] = $e->getMessage();
                $data['status'] = 400;
            }
        } else {
            $data['err_code'] = "invalid_req_data";
            $data['status'] = 400;
        }
    }
}

if ($symbol_id || $symbol_username) {
    if ($symbol_id) {
        $symbol_data = cl_symbol_data($symbol_id);
    } else {
        $symbol_data = cl_get_symbol_by_name($symbol_username);
    }

    if ($symbol_data) {
        $cl['symbol'] = $symbol_data;
    } else {
       
        cl_redirect("404");
    }
} else {
    // Если ни идентификатор, ни username не переданы, перенаправляем на страницу 404
    cl_redirect("404");
}

if (not_empty($cl["settings_app"]) && in_array($cl["settings_app"], $cl["settings_apps"])) {
    if ($cl["settings_app"] == "email_notifs" && $cl["config"]["email_notifications"] == "off") {
        cl_redirect("404");
    } else if ($cl["settings_app"] == "premium" && $cl["config"]["prem_account_system_status"] == "off") {
        cl_redirect("404");
    } else if ($cl["settings_app"] == "blue" && $cl["config"]["blue_account_system_status"] == "off") {
        cl_redirect("404");
    } else {
        $cl["http_res"] = cl_template(cl_strf("page_settings/includes/%s", $cl["settings_app"]));
    }
} else {
    $cl["http_res"] = cl_template("page_settings/content");
}
?>