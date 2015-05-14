<style>
    @font-face {
  font-family: 'Eight Track';
  src: url('http://www.fontsaddict.com/fontface/eight-track.TTF');
}
.clockStyle {
	background-color:#000;
	border:#999 2px inset;
	padding:6px;
	color:#0FF;
	font-family: 'Eight Track';
        src: url('http://www.fontsaddict.com/fontface/eight-track.TTF');
    font-size:16px;
    font-weight:bold;
	letter-spacing: 2px;
	display:inline;
}
body{
    font-family: 'Eight Track';
    src: url('http://www.fontsaddict.com/fontface/eight-track.TTF');
    background-image: url("http://stitchmasterfresno.com/wp-content/uploads/2013/10/instagram.png");
    background-color: #cccccc;
   
}
a { text-decoration: none; }
a:visited { text-decoration: none; }
a:hover { text-decoration: none;
color: orange;
}
a:focus { text-decoration: none; 
color: lime;
}
a:hover, a:active { text-decoration: none; }
a { color:blue }
</style>
<?php

//Configuration for our PHP server
set_time_limit(0);
ini_set('default_socket_timeout', 300);
session_start();
//Make Constants using define.
define('clientID', '4e6800479159456795b55ad092b60633');
define('clientSecret', '02df84a6dd5145b2805717ec1ec732b8');
define('redirectURI', 'http://localhost/appacademyapi/index.php');
define('ImageDirectory', 'pics/');
//function that is going to connect to Instagram.
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
    $url = "https://api.instagram.com/vl/users/search?q=".$userName."&client_id=".clientID; //to get ID
    $instagramInfo = connectToInstagram($url);//connecting to Instagram
    $results = json_decode($instagramInfo, true);//creating a local variable to decode json information
    return $results['data']['0']['id'];//echoing out userID
}
//function to print out images onto screen

function printImages($userID){
    $url = "https://api.instagram.com/vl/users/".$userID."/media/recent?client_id=".clientID."&count=5";
    $instagramInfo = connectToInstagram($url);
    $results = json_decode($instagramInfo, true);
    //Parse through the information one by one.
    foreach ($results['data'] as $items){
    $image_url = $items['images']['low_resolution']['url'];
        
    echo '<img src=" '.$image_url .' "/><br/>'; //going to go through all of my results and give myself back the URL of those pictures
    //calling a function to save image to server
    savePictures($image_url);
    }
}
    function savePictures($image_url){
        echo $image_url . "<br>";
        $filename = basename($image_url); //the filename is what we are storing. basename is the PHP built in method that we are using to store $image_url
        echo $filename . "<br>";
        
        $destination = ImageDirectory . $filename; 
        file_put_contents($destination, file_get_contents($image_url)); //goes and grabs an image file and stores it into out server
    }
    
    

if(isset($_GET['code'])){
    $code = ($_GET['code']);
    $url = 'https://api.instagram.com/oauth/access_token';
    $access_token_settings = array('client_id' => clientID,
                                    'client_secret' => clientSecret,
                                    'grant_type' => 'authorization_code',
                                    'redirect_uri' => redirectURI,
                                    'code' => $code
                                     );
    
    //cURL is what we use in PHP, it's a library calls to other API's.
    $curl = curl_init($url);//setting a cURL session and we put in $url because that's where we are getting the data from.
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $access_token_settings); //setting the POSTFIELDS to the array setup that we created.
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);//setting it equal to 1 because we are getting strings back.
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//but in live work-production we want to set this to true.
   $result = curl_exec($curl);
curl_close($curl);
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
    <?php require_once("loading.php");?>
<meta charset="utf-8">
<meta name="description" content="">
<script type="text/javascript" src="sound-mouseover.js"></script>

<meta name="viewport" content="">
<title>App Academy API</title>
<link rel="stylesheet" href="css/style.css">
<link rel="author" href="humans.txt">
</head>
<body>
    <div id="clockDisplay" class="clockStyle"></div>
<script>
function renderTime() {
	var currentTime = new Date();
	var diem = "AM";
	var h = currentTime.getHours();
	var m = currentTime.getMinutes();
    var s = currentTime.getSeconds();
	setTimeout('renderTime()',1000);
    if (h == 0) {
		h = 12;
	} else if (h > 12) { 
		h = h - 12;
		diem="PM";
	}
	if (h < 10) {
		h = "0" + h;
	}
	if (m < 10) {
		m = "0" + m;
	}
	if (s < 10) {
		s = "0" + s;
	}
    var myClock = document.getElementById('clockDisplay');
	myClock.textContent = h + ":" + m + ":" + s + " " + diem;
	myClock.innerText = h + ":" + m + ":" + s + " " + diem;
}
renderTime();
</script>

<center>
    <audio id="FinalAnswer" preload="auto">
	<source src="http://www.televisiontunes.com/themesongs/Who%20Wants%20To%20Be%20A%20Millionaire%20-%20Final%20Answer.mp3" display="none"></source>
    </audio>
    <div id="FinalAnswer"><bgsound id="sound"></div>
<!-- Creating a login for people to go and give approval for our web app to access their Instagram Account after getting approval we are now -->
<ul>
    <font size="92"><a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo clientID; ?>&redirect_uri=<?php echo redirectURI; ?>&response_type=code" onmouseover="playclip();" onmouseout="stopclip();">Login</a></font>
<br>
<font size="92"><a href="JavaScript:window.close()" onmouseover="playclip();" onmouseout="stopclip();">Quit</a></font></center>

<marquee behavior="scroll" direction="left">This API is powered by our good friends at Instagram. Instagram is your only place to find all of your app-registered needs, including photos, videos and more! Visit www.instagram.com to get your free account today or check your local provider for details before it's too late!</marquee>

<script src="js/main.js"></script>
<script>
    var FinalAnswer = $("#final-answer")[0];
$("#nav-one a")
	.mouseenter(function() {
		FinalAnswer.play();
	});
        </script>
</body>
</html>
<?php 
}
?>