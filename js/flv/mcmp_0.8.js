// MC Media Player: JavaScript file v 0.8
// This script performs two functions:
// (1) It writes the HTML code which embeds the Flash media player file on your web page.
// (2) It allows you to customize your player by editing variables.
// More information: www.mcmediaplayer.com

/* Config Variables - Uncomment and edit values to customize your player.
Note: Config variables are normally defined on the web page for maximum versatility, which is why they are commented out in this file.
Any variables defined below will override web page variables and affect all instances of the player that refer to this file.
If you define the variables on the web page instead, you can configure each player differently. */

//playerFile = "http://www.mcmediaplayer.com/publicmc/mcmp_0.7.swf";
//streamingServerURL = "";
//fpFileURL = "";
//fpPreviewImageURL = "";
//fpAction = "";
//colorScheme = "";
//cpBackgroundColor = "000000";
//cpBackgroundOpacity = "60";
//cpButtonsOpacity = "100";
//cpCounterPosition = "330x4";
//cpFullscreenBtnPosition = "454x12";
//cpHidePanel = "button";
//cpHideDelay = "0";
//cpInfoBtnPosition = "470x12";
//cpPlayBtnPosition = "60x12";
//cpPlayBtnColor = "";
//cpPosition = "0x246";
//cpRepeatBtnPosition = "438x12";
//cpScrubberPosition = "194x8";
//cpScrubberColor = "";
//cpScrubberLoadedColor = "";
//cpScrubberElapsedColor = "";
//cpVolumeStart = "100";
//cpStopBtnPosition = "85x12";
//cpStopBtnColor = "";
//cpVolumeBtnPosition = "118x2";
//cpVolumeCtrlColor = "";
//cpSize = "480x24";
//defaultBufferLength = "1";
//defaultEndAction = "previewImage";
//defaultStopAction = "previewImage";
//fpButtonOpacity = "60";
//fpButtonPosition = "240x118";
//fpButtonSize = "126x126";
//fpButtonColor = "";
//fpPreviewImageSize = "fit";
//msgBackgroundColor = "000000";
//msgBackgroundOpacity = "90";
//playerBackgroundColor = "525252";
//playerSize = "480x270";
//playerAutoResize = "on";
//videoScreenPosition = "0x0";
//videoScreenSize = "480x270";
//tooltipTextColor = "000000";
//tooltipBGColor = "CCCCCC";


//////////////////////////////
// Nothing below here needs to be edited.
//////////////////////////////
if (typeof playerFile == 'undefined') { playerFile = 'mcmp.swf'; }
if (typeof fpFileURL != 'undefined') { mcflashvars = 'fpFileURL='+fpFileURL; }
if (typeof playerSize == 'undefined') { playerSize = '480x270'; }
var psep = playerSize.indexOf("x");
var playerWidth = playerSize.substring(0,psep);
var playerHeight = playerSize.substring(psep+1);
if (typeof streamingServerURL != 'undefined') { mcflashvars += '&streamingServerURL='+streamingServerURL; }
if (typeof fpAction != 'undefined') { mcflashvars += '&fpAction='+fpAction; }
if (typeof fpPreviewImageURL != 'undefined') { mcflashvars += '&fpPreviewImageURL='+fpPreviewImageURL; }
if (typeof colorScheme != 'undefined') { mcflashvars += '&colorScheme='+colorScheme; }// New in v0.8
if (typeof cpBackgroundColor != 'undefined') { mcflashvars += '&cpBackgroundColor='+cpBackgroundColor; }
if (typeof cpBackgroundOpacity != 'undefined') { mcflashvars += '&cpBackgroundOpacity='+cpBackgroundOpacity; }
if (typeof cpButtonsOpacity != 'undefined') { mcflashvars += '&cpButtonsOpacity='+cpButtonsOpacity; }
if (typeof cpCounterPosition != 'undefined') { mcflashvars += '&cpCounterPosition='+cpCounterPosition; }
if (typeof cpFullscreenBtnPosition != 'undefined') { mcflashvars += '&cpFullscreenBtnPosition='+cpFullscreenBtnPosition; }
if (typeof cpHideDelay != 'undefined') { mcflashvars += '&cpHideDelay='+cpHideDelay; }
if (typeof cpHidePanel != 'undefined') { mcflashvars += '&cpHidePanel='+cpHidePanel; }
if (typeof cpInfoBtnPosition != 'undefined') { mcflashvars += '&cpInfoBtnPosition='+cpInfoBtnPosition; }
if (typeof cpPlayBtnPosition != 'undefined') { mcflashvars += '&cpPlayBtnPosition='+cpPlayBtnPosition; }
if (typeof cpPlayBtnColor != 'undefined') { mcflashvars += '&cpPlayBtnColor='+cpPlayBtnColor; }// New in v0.8
if (typeof cpPosition != 'undefined') { mcflashvars += '&cpPosition='+cpPosition; }
if (typeof cpRepeatBtnPosition != 'undefined') { mcflashvars += '&cpRepeatBtnPosition='+cpRepeatBtnPosition; }
if (typeof cpScrubberPosition != 'undefined') { mcflashvars += '&cpScrubberPosition='+cpScrubberPosition; }
if (typeof cpScrubberColor != 'undefined') { mcflashvars += '&cpScrubberColor='+cpScrubberColor; }// New in v0.8
if (typeof cpScrubberLoadedColor != 'undefined') { mcflashvars += '&cpScrubberLoadedColor='+cpScrubberLoadedColor; }// New in v0.8
if (typeof cpScrubberElapsedColor != 'undefined') { mcflashvars += '&cpScrubberElapsedColor='+cpScrubberElapsedColor; }// New in v0.8
if (typeof cpVolumeStart != 'undefined') { mcflashvars += '&cpVolumeStart='+cpVolumeStart; }
if (typeof cpStopBtnPosition != 'undefined') { mcflashvars += '&cpStopBtnPosition='+cpStopBtnPosition; }
if (typeof cpStopBtnColor != 'undefined') { mcflashvars += '&cpStopBtnColor='+cpStopBtnColor; }// New in v0.8
if (typeof cpVolumeBtnPosition != 'undefined') { mcflashvars += '&cpVolumeBtnPosition='+cpVolumeBtnPosition; }
if (typeof cpVolumeCtrlColor != 'undefined') { mcflashvars += '&cpVolumeCtrlColor='+cpVolumeCtrlColor; }// New in v0.8
if (typeof cpSize != 'undefined') { mcflashvars += '&cpSize='+cpSize; }
if (typeof defaultBufferLength != 'undefined') { mcflashvars += '&defaultBufferLength='+defaultBufferLength; }
if (typeof defaultEndAction != 'undefined') { mcflashvars += '&defaultEndAction='+defaultEndAction; }
if (typeof defaultStopAction != 'undefined') { mcflashvars += '&defaultStopAction='+defaultStopAction; }
if (typeof fpButtonOpacity != 'undefined') { mcflashvars += '&fpButtonOpacity='+fpButtonOpacity; }
if (typeof fpButtonPosition != 'undefined') { mcflashvars += '&fpButtonPosition='+fpButtonPosition; }
if (typeof fpButtonSize != 'undefined') { mcflashvars += '&fpButtonSize='+fpButtonSize; }
if (typeof fpButtonColor != 'undefined') { mcflashvars += '&fpButtonColor='+fpButtonColor; }// New in v0.8
if (typeof fpPreviewImageSize != 'undefined') { mcflashvars += '&fpPreviewImageSize='+fpPreviewImageSize; }
if (typeof msgBackgroundColor != 'undefined') { mcflashvars += '&msgBackgroundColor='+msgBackgroundColor; }
if (typeof msgBackgroundOpacity != 'undefined') { mcflashvars += '&msgBackgroundOpacity='+msgBackgroundOpacity; }
if (typeof playerBackgroundColor != 'undefined') { mcflashvars += '&playerBackgroundColor='+playerBackgroundColor; }
if (typeof playerAutoResize != 'undefined') { mcflashvars += '&playerAutoResize='+playerAutoResize; }// New in 0.7
if (typeof playerSize != 'undefined') { mcflashvars += '&playerSize='+playerSize; }
if (typeof videoScreenSize != 'undefined') { mcflashvars += '&videoScreenSize='+videoScreenSize; }
if (typeof videoScreenPosition != 'undefined') { mcflashvars += '&videoScreenPosition='+videoScreenPosition; }
if (typeof tooltipTextColor != 'undefined') { mcflashvars += '&tooltipTextColor='+tooltipTextColor; }// New in 0.7
if (typeof tooltipBGColor != 'undefined') { mcflashvars += '&tooltipBGColor='+tooltipBGColor; }// New in 0.7

function mccode() {
	var str='';
	str+='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http:\/\/download.macromedia.com\/pub\/shockwave\/cabs\/flash\/swflash.cab#version=7,0,19,0" width="'+playerWidth+'" height="'+playerHeight+'">\n';
	str+='<param name="movie" value="'+playerFile+'">';
	str+='<param name="allowScriptAccess" value="always">';
	str+='<param name="quality" value="high">';
	str+='<param name="allowFullScreen" value="true">';
	str+='<param name="FlashVars" value="'+mcflashvars+'">\n';
	str+='<embed src="'+playerFile+'" width="'+playerWidth+'" height="'+playerHeight+'" quality="high" allowFullScreen="true" allowscriptaccess="always" pluginspage="http:\/\/www.macromedia.com\/go\/getflashplayer" type="application\/x-shockwave-flash" FlashVars="'+mcflashvars+'"><\/embed>\n';
	str+='<\/object>';
	document.write(str);
}
mccode();