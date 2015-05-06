<?php
//Configuration for our PHP server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();
//Make Constants using define.
define('clientID', '477393f935964621b84185fb1c272017');
define('clientSecret', 'b3b9843feb9d412aab05bc61367737ef');
define('redirectURI', 'http://localhost:8888/appacademyapi/index.php');
define('ImageDirectory', 'pics/');

function connectToInstagram($url){
    $ch = curl_init();
    
    curl_setopt_array($ch, array(
       CURLOPT_URL => $url,
       CURLOPT_RETURNTRANSFER => true,
       CURLOPT_SSL_VERIFYPEER => false,
       CURLOPT_SSL_VERIFYHOST => 2,
    ));
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}
//function to get userID becuase username does not allow us to get pictures!
function getUserID($userName){
    $url = "http://api.instagram.com/vl/users/search?q=".$userName."&client_id=".clientID;
    $instagramInfo = connectToInstagram($url);
    $results = json_decode($instagramInfo, true);
    echo $results['data']['0']['id'];
}
//function to print out images onto screen

function printImages($userID){
    $url = "https://api.instagram.com/vl/users/".$userID."/media/recent?client_id=".clientID."&count=5";
    $instagramInfo = connectToInstagram($url);
    $results = json_decode($instagramInfo, true);
    //Parse through the information one by one.
    foreach($results['data'] as $items){
    $image_url = $items['images']['low_resolution']['url'];
        
    echo "<img src=".$image_url."/><br/>";
    }
    
}
if(isset($_GET['code'])){
    $code = ($_GET['code']);
    $url = 'https://api.instagram.com/oauth/access_token';
    $access_token_settings = array('client_id' => clientID,
                                    'client_secret' => clientSecret,
                                    'grant_type' => 'authorization_code',
                                    'redirect_uri' == redirectURI,
                                    'code' => $code
                                     );
    
    //cURL is what we use in PHP, it's a library calls to other API's.
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
   $result = curl_exec($curl);
curl_close();
$results = json_decode($result, true);
$userName = $results['user']['username'];
$userID = getUserID($userName);
printImages($userID);
}
else {
    
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="description" content="">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Untitled</title>
<link rel="stylesheet" href="css/style.css">
<link rel="author" href="humans.txt">
</head>
<body>
<!-- Creating a login for people to go and give approval for our web app to access their Instagram Account after getting approval we are now -->
<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code">LOGIN</a>
<script src="js/main.js"></script>
</body>
</html>
<?php 
}
?>