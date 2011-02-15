function doSlide(id){
	timeToSlide = 20; // in milliseconds
	obj = document.getElementById(id);
	if(obj.style.display == "none"){ // si c hidden on fait le slide
		obj.style.visibility = "hidden";
		obj.style.display = "block";
		height = obj.offsetHeight;
		obj.style.height="0px";
		obj.style.visibility = "visible";
		pxPerLoop = height/timeToSlide;
		slide(obj,0,height,pxPerLoop);
	} else {
	obj.style.display = "none";
	}
}

function slide(obj,offset,full,px){
	if(offset < full){
		obj.style.height = offset+"px";
		offset=offset+px;
		setTimeout((function(){slide(obj,offset,full,px);}),1);
	} else {
		obj.style.height = "auto"; //Can be usefull in updated divs otherwise
//just use full+"px"
	}
}
///////////////////////////////////////////////////////////////////////////////
var min=5;
var max=16;
function increaseFontSize() {
//   var p = document.getElementsByTagName('p');
   var p = document.getElementById('details');
      if(p.style.fontSize) {
         var s = parseInt(p.style.fontSize.replace("px",""));
      } else {
         var s = 11;
      }
      if(s!=max) {
         s += 1;
      }
      p.style.fontSize = s+"px"
   
}
function decreaseFontSize() {
//   var p = document.getElementsByTagName('p');
   var p = document.getElementById('details');
      if(p.style.fontSize) {
         var s = parseInt(p.style.fontSize.replace("px",""));
      } else {
         var s = 16;
      }
      if(s!=min) {
         s -= 1;
      }
      p.style.fontSize = s+"px"
}
function normalFontSize() {
//   var p = document.getElementsByTagName('p');
   var p = document.getElementById('details');
   var s = 11;
   p.style.fontSize = s+"px"
}
function ajaxLoader(url,id) {
  if (document.getElementById) {
    var x = (window.ActiveXObject) ? new ActiveXObject("Microsoft.XMLHTTP") : new XMLHttpRequest();
  }
  if (x) {
    x.onreadystatechange = function() {
      if (x.readyState == 4 && x.status == 200) {
        el = document.getElementById(id);
        el.innerHTML = x.responseText;
      }
    }
    x.open("GET", url, true);
    x.send(null);
  }
}
/////////////////////////////
function calculate_mortgage() {
	var form = document.mortgage_calc_form;
	
	// do field validation
	if (form.loan.value == ""){
		alert( "Loan amount is required." );
	} else if (form.duration.value == ""){
		alert( "Duration is required." );
	} else if (form.interest_rate.value == ""){
		alert( "Interest rate is required." );
	} else {
		var loan = form.loan.value;
		loan = loan.replace(",",""); // Remove commas
		
		//Round instead of replace decimal
		//loan = loan.replace(".","");    // Remove preiods
        loan = Math.round(loan);

		form.loan.value = loan; // refresh loan amount in form without commas or periods
		
		var duration = (form.duration.value*12); // in months
		
		var interest_rate = form.interest_rate.value.replace(",","."); // Replace comma with period
		form.interest_rate.value = interest_rate; // refresh duration in form without commas
		interest_rate = (interest_rate/12); // monthly
		
		var quote = (loan * interest_rate) / ( 100 * ( 1 - Math.pow ( ( 1 + (interest_rate/100) ), -duration ) ) );
		
		if (quote.toFixed) { //if browser supports toFixed() method
			quote = quote.toFixed(2);
		}
		
		form.quote.value = quote; // monthly
	}
}


var mortgage_calc_popUpWin=0;
function mortgage_calc_popUpWindow(URLStr, left, top, width, height) {
  if(mortgage_calc_popUpWin) {
    if(!mortgage_calc_popUpWin.closed) mortgage_calc_popUpWin.close();
  }
  mortgage_calc_popUpWin = open(URLStr, 'mortgage_calc_popUpWin', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=yes,width='+width+',height='+height+',left='+left+', top='+top+',screenX='+left+',screenY='+top+'');
}

//////////////////////////
var xmlhttp_handle   = ajax_connect();
function fetchElementById(id) 
{ 
	if (document.getElementById) {
		var return_var = document.getElementById(id); 
	} else if (document.all) {
		var return_var = document.all[id]; 
	} else if (document.layers) { 
		var return_var = document.layers[id]; 
	} else {
		alert("Failed to fetch element ID '" + id + "'");
	}
	return return_var; 
}

function ajax_connect()
{
	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else {
		try {
			xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e) {
			try {
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e) {
				alert("Your browser does not support AJAX!");
			}
		}
	}

	return xmlhttp;
}

function get_cookie(cookie_name)
{
	if (document.cookie.length > 0) {
		cookie_start = document.cookie.indexOf(cookie_name + "=");
		
		if (cookie_start != -1) { 
			cookie_start = ((cookie_start + cookie_name.length) + 1); 
			cookie_end   = document.cookie.indexOf(";", cookie_start);
			
			if ( cookie_end == -1) {
				cookie_end = document.cookie.length;
			}
			
			return unescape(document.cookie.substring(cookie_start, cookie_end));
		} 
	}
	
	return false;
}

function set_cookie(cookie_name, value, expire)
{
	var expire_date = new Date();
	
	expire_date.setDate(expire_date.getDate() + expire);
	document.cookie = (cookie_name + "=" + escape(value) + ((expire == null) ? "" : ";expires=" + expire_date.toGMTString()));

	return true;
}
function toggle_lightbox(url, div)
{
	var block_id    = fetchElementById("page_body");
	var request_url = (url + (((url.match(/\?/)) ? "&" : "?") + "lb_div=" + div));

	if (url != "no_url") {
		var lightbox_id  = document.createElement("div");

// set loading text
		lightbox_id.innerHTML  = "<div class=\"lightbox_main_loading\"><br/><br/><br/><br/></div>";		
		lightbox_id.innerHTML += "<div class=\"lightbox_background\">&nbsp;</div>";
		block_id.appendChild(lightbox_id);
		
		scroll(0, 0);
		lightbox_id.setAttribute("id", div);
		
		xmlhttp_handle.open("GET", request_url, false);
		xmlhttp_handle.send(null);
		block_id.removeChild(lightbox_id);
		lightbox_id.innerHTML  = "<div class=\"lightbox_main\">" + xmlhttp_handle.responseText + "</div>";
		lightbox_id.innerHTML += "<div class=\"lightbox_background\">&nbsp;</div>";
		
		block_id.appendChild(lightbox_id);
	} else {
		var lightbox_id = fetchElementById(div);

		block_id.removeChild(lightbox_id);
	}

	return;
}
//////////////

function form_is_modified(oForm)
{
	var el, opt, hasDefault, i = 0, j;
	while (el = oForm.elements[i++]) {
		switch (el.type) {
			case 'text' :
                   	case 'textarea' :
                   	case 'hidden' :
                         	if (!/^\s*$/.test(el.value) && el.value != el.defaultValue) return false;
                         	break;
                   	case 'checkbox' :
                   	case 'radio' :
                         	if (el.checked != el.defaultChecked) return true;
                         	break;
                   	case 'select-one' :
                   	case 'select-multiple' :
                         	j = 0, hasDefault = false;
                         	while (opt = el.options[j++])
                                	if (opt.defaultSelected) hasDefault = true;
                         	j = hasDefault ? 0 : 1;
                         	while (opt = el.options[j++]) 
                                	if (opt.selected != opt.defaultSelected) return true;
                         	break;
		}
	}
	return false;
}

////////////////////
function disableForm(theform,value) {
	if (document.all || document.getElementById) {
		for (i = 0; i < theform.length; i++) {
			var tempobj = theform.elements[i];
			if (tempobj.type.toLowerCase() == "submit" || tempobj.type.toLowerCase() == "reset") {
				tempobj.disabled = true;
				tempobj.value = value;
			}
		}
	return true;
	}
}

///////////////////////////////////////////////////////////////////////
function hiding(what) { 
      var tmp = document.getElementById(what); 
      if (tmp.style.display == 'none') { 
        tmp.style.display = 'block'; 
      } else { 
        tmp.style.display = 'none'; 
      } 
}
function hidecartbt(what) { 
      var tmp = document.getElementById(what); 
      if (tmp.style.display == 'none') { 
        tmp.style.display = 'inline'; 
      } else { 
        tmp.style.display = 'none'; 
      } 
}

function DeleteItem(idurl)
{
	go_on = confirm("Are you sure?");
	if (go_on)
	{
		document.location.href=idurl;
	}
}	

function formCheck(formobj){
	// Enter name of mandatory fields
//	var fieldRequired = Array("username", "password");
	// Enter field description to appear in the dialog box
//	var fieldDescription = Array("Username", "Password");
	// dialog message
	var alertMsg = "Please complete the following fields:\n";
	
	var l_Msg = alertMsg.length;
	
	for (var i = 0; i < fieldRequired.length; i++){
		var obj = formobj.elements[fieldRequired[i]];
		if (obj){
			switch(obj.type){
			case "select-one":
				if (obj.selectedIndex == -1 || obj.options[obj.selectedIndex].text == ""){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "select-multiple":
				if (obj.selectedIndex == -1){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			case "text":
			case "textarea":
				if (obj.value == "" || obj.value == null){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
				break;
			default:
			}
			if (obj.type == undefined){
				var blnchecked = false;
				for (var j = 0; j < obj.length; j++){
					if (obj[j].checked){
						blnchecked = true;
					}
				}
				if (!blnchecked){
					alertMsg += " - " + fieldDescription[i] + "\n";
				}
			}
		}
	}

	if (alertMsg.length == l_Msg){
		return true;
	}else{
		alert(alertMsg);
		return false;
	}
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function popUp(URL) {
 day = new Date();
 id = day.getTime();
 eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=1,menubar=0,resizable=1,width=500,height=600,left=280,top=60');");
}

function SelectAllCheckbox(what, val) {
    for (var i = 0; i < document.frm.elements.length; i++) {
		if(document.frm.elements[i].type == 'checkbox' && document.getElementById(document.frm.elements[i].id) == document.getElementById(what)) {
			document.frm.elements[i].checked = !(document.frm.elements[i].checked);
			document.frm.elements[i].checked = val;
		}
    }
}

function SelectAllMessages(val) {
    for (var i = 0; i < document.messages.elements.length; i++) {
		if(document.messages.elements[i].type == 'checkbox') {
			document.messages.elements[i].checked = !(document.messages.elements[i].checked);
			document.messages.elements[i].checked = val;
		}
    }
}

var horizontal_offset="9px"
var vertical_offset="0" 
var ie=document.all
var ns6=document.getElementById&&!document.all
if (window.addEventListener)
window.addEventListener("load", createhintbox, false)
else if (window.attachEvent)
window.attachEvent("onload", createhintbox)
else if (document.getElementById)
window.onload=createhintbox
//TIP
function getposOffset(what, offsettype){
var totaloffset=(offsettype=="left")? what.offsetLeft : what.offsetTop;
var parentEl=what.offsetParent;
while (parentEl!=null){
totaloffset=(offsettype=="left")? totaloffset+parentEl.offsetLeft : totaloffset+parentEl.offsetTop;
parentEl=parentEl.offsetParent;
}
return totaloffset;
}

function iecompattest(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function clearbrowseredge(obj, whichedge){
var edgeoffset=(whichedge=="rightedge")? parseInt(horizontal_offset)*-1 : parseInt(vertical_offset)*-1
if (whichedge=="rightedge"){
var windowedge=ie && !window.opera? iecompattest().scrollLeft+iecompattest().clientWidth-30 : window.pageXOffset+window.innerWidth-40
dropmenuobj.contentmeasure=dropmenuobj.offsetWidth
if (windowedge-dropmenuobj.x < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure+obj.offsetWidth+parseInt(horizontal_offset)
}
else{
var windowedge=ie && !window.opera? iecompattest().scrollTop+iecompattest().clientHeight-15 : window.pageYOffset+window.innerHeight-18
dropmenuobj.contentmeasure=dropmenuobj.offsetHeight
if (windowedge-dropmenuobj.y < dropmenuobj.contentmeasure)
edgeoffset=dropmenuobj.contentmeasure-obj.offsetHeight
}
return edgeoffset
}

function showhint(menucontents, obj, e, tipwidth){
if ((ie||ns6) && document.getElementById("hintbox")){
dropmenuobj=document.getElementById("hintbox")
dropmenuobj.innerHTML=menucontents
dropmenuobj.style.left=dropmenuobj.style.top=-500
if (tipwidth!=""){
dropmenuobj.widthobj=dropmenuobj.style
dropmenuobj.widthobj.width=tipwidth
}
dropmenuobj.x=getposOffset(obj, "left")
dropmenuobj.y=getposOffset(obj, "top")
dropmenuobj.style.left=dropmenuobj.x-clearbrowseredge(obj, "rightedge")+obj.offsetWidth+"px"
dropmenuobj.style.top=dropmenuobj.y-clearbrowseredge(obj, "bottomedge")+"px"
dropmenuobj.style.visibility="visible"
obj.onmouseout=hidetip
}
}

function hidetip(e){
dropmenuobj.style.visibility="hidden"
dropmenuobj.style.left="-500px"
}

function createhintbox(){
var divblock=document.createElement("div")
divblock.setAttribute("id", "hintbox")
document.body.appendChild(divblock)
}

