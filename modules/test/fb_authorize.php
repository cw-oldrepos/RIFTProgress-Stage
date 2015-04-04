<?php
//-- Facebook API --//
    include_once    "properties.php";

    require $ROOT.'/facebook/src/facebook.php';

//-- App Information --//
$app_id     = '816307315051965';
$app_secret = '97e6d3e3e7f6decc497428ec53b08cdd';

// Create Facebook Instance
$facebook = new Facebook(array(
    'appId' => $app_id,
    'secret' => $app_secret
));

//-- To Facebook (Notice we ask for offline access) --//
//if (empty($_REQUEST))
//{
    $loginUrl = $facebook->getLoginUrl(array(
        'canvas' => 1,
        'fbconnect' => 0,
        'scope' => 'offline_access,publish_stream'
    ));
    //header('Location:'.$loginUrl );
//}
//-- From Facebook --//
//else
//{
    $user = $facebook->getUser();
    if($user)
    {
        $access_token = $facebook->getAccessToken();
        echo "Your access token is: <br><br>$access_token";
    }
//}
?>