

function playclip() {
if (navigator.appName == "Google Chrome") {
if (document.all)
 {
  document.all.sound.src = "http://www.televisiontunes.com/themesongs/Who%20Wants%20To%20Be%20A%20Millionaire%20-%20Final%20Answer.mp3";
 }
}

else {
{
var audio = document.getElementsByTagName("audio")[0];
audio.play();
}
}
}

//-->


