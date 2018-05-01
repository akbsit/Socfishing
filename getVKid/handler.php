<?php
require_once "inc.php";

if (is_ajax() || is_post()) {
    $apiId = clear_int($_POST["apiId"]);
    $pageId = clear_int($_POST["pageId"]);
    $key = clear_str($_POST["key"]);
    $domain = check_domain($key, $config["domains"]);

    if (isset($apiId, $pageId, $domain)) {
        $apiLink = "https://api.vk.com/method/likes.getList?type=sitepage&owner_id=" . $apiId . "&page_url=" . "http://" . $config["script_dir"] . "?mypp=" . $pageId;
        $content_vk = file_get_contents($apiLink);

        if ($content_vk) {
            $vk = json_decode($content_vk);

            if ($vk && isset($vk->response)) {
                $user_id = $vk->response->users[0];
                echo($user_id);
            }
        }
    }
}