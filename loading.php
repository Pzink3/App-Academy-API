<!DOCTYPE html>
<html>
<head>

<style>
    @font-face {
  font-family: 'Eight Track';
  src: url('http://www.fontsaddict.com/fontface/eight-track.TTF');
}

div#load_screen{
	background: #000;
	opacity: 1;
	position: fixed;
    z-index:10;
	top: 0px;
	width: 100%;
	height: 1600px;
}
div#load_screen > div#loading{
	color:#FFF;
	width:120px;
	height:24px;
	margin: 300px auto;
        font-family: 'Eight Track';
  src: url('http://www.fontsaddict.com/fontface/eight-track.TTF');
}
</style>
<script>
window.addEventListener("load", function(){
	var load_screen = document.getElementById("load_screen");
	document.body.removeChild(load_screen);
});
</script>
</head>
<body>
   
   <div id="load_screen" style='font-color:white'><div id="loading"><center><img src='https://www.greenedu.com/img/loading-small.gif'></center><center>Loading...</div></font></div>
<?php include_once("index.php");?>
</body>
</html>