var userAgent = navigator.userAgent.toLowerCase();
var is_opera  = ((userAgent.indexOf('opera') != -1) || (typeof(window.opera) != 'undefined'));
var is_saf    = ((userAgent.indexOf('applewebkit') != -1) || (navigator.vendor == 'Apple Computer, Inc.'));
var is_webtv  = (userAgent.indexOf('webtv') != -1);
var is_ie     = ((userAgent.indexOf('msie') != -1) && (!is_opera) && (!is_saf) && (!is_webtv));
var is_ie4    = ((is_ie) && (userAgent.indexOf('msie 4.') != -1));
var is_moz    = ((navigator.product == 'Gecko') && (!is_saf));
var is_kon    = (userAgent.indexOf('konqueror') != -1);
var is_ns     = ((userAgent.indexOf('compatible') == -1) && (userAgent.indexOf('mozilla') != -1) && (!is_opera) && (!is_webtv) && (!is_saf));
var is_ns4    = ((is_ns) && (parseInt(navigator.appVersion) == 4));
var is_mac    = (userAgent.indexOf('mac') != -1);

// convert an array to an object to do string search in that array: "needle" in oc(haystackArray)
function oc(a) {
	var o = {};
	for (var i = 0; i < a.length; i++) o[a[i]] = '';
	return o;
}

String.prototype.trim = function () {
    return this.replace(/^\s*/, "").replace(/\s*$/, "");
}

function fetch_object(idname)
{
	if (document.getElementById)
	{
		return document.getElementById(idname);
	}
	else if (document.all)
	{
		return document.all[idname];
	}
	else if (document.layers)
	{
		return document.layers[idname];
	}
	else
	{
		return null;
	}
}
// form seach full
function selectText_b() {
	document.getElementById("q").value = "";
}
	
function addText_b() {
	if(document.getElementById("q").value == "") {
		document.getElementById("q").value= "Nhập từ khóa tìm kiếm";	
	}
	else {}
}
function validate_search() {
	var _search = document.getElementById('search');
	if (_search.value.length<3 || _search.value == "Nhập từ khóa tìm kiếm") {
		alert("Từ khóa không được để trống và phải dài hơn 2 ký tự!");
		_search.focus();
		return false;
	}
	document.formSearch.submit();
	return;
}
var dayarray=new Array("Chủ nhật","Thứ hai","Thứ ba","Thứ tư","Thứ năm","Thứ sáu","Thứ bẩy")
var montharray=new Array("01","02","03","04","05","06","07","08","09","10","11","12")

function getthedate(){
var mydate=new Date()
var year=mydate.getYear()
if (year < 1000)
year+=1900
var day=mydate.getDay()
var month=mydate.getMonth()
var daym=mydate.getDate()
if (daym<10)
daym="0"+daym
var hours=mydate.getHours()
var minutes=mydate.getMinutes()
var seconds=mydate.getSeconds()
var dn="AM"
if (hours>=12)
dn="PM"
if (hours>12){
hours=hours-12
}
if (hours==0)
hours=12
if (minutes<=9)
minutes="0"+minutes
if (seconds<=9)
seconds="0"+seconds
//change font size here
var cdate=""+dayarray[day]+", "+daym+"-"+montharray[month]+"-"+year+" | "+hours+":"+minutes+":"+seconds+" "+dn
+""
if (document.all)
document.all.clock.innerHTML=cdate
else if (document.getElementById)
document.getElementById("clock").innerHTML=cdate
else
document.write(cdate)
}
if (!document.all&&!document.getElementById)
getthedate()
function goforit(){
	if (document.all||document.getElementById)
	setInterval("getthedate()",1000)
}

function fetch_tags(tag)
{
	if (typeof document.getElementsByTagName != 'undefined')
	{
		return document.getElementsByTagName(tag);
	}
	else if (document.all && document.all.tags)
	{
		return document.all.tags(tag);
	}
	else
	{
		return new Array();
	}
}

// Cac ham ve ajax

function createxmlHttp()
{
		var xmlHttp;
		try	{
			xmlHttp = new XMLHttpRequest();
		}
		catch(e) {
				var XmlHttpVersions = new Array('MSXML2.XMLHTTP.6.0',
												'MSXML2.XMLHTTP.5.0',
												'MSXML2.XMLHTTP.4.0',
												'MSXML2.XMLHTTP.3.0',
												'MSXML2.XMLHTTP',
												'Microsoft.XMLHTTP');
				for (var i=0; i<XmlHttpVersions.length && !xmlHttp; i++){
					try	{
							xmlHttp = new ActiveXObject(XmlHttpVersions[i]);
					}
					catch (e) {}
				}
		}
		if (!xmlHttp)
			alert('Sorry we can not create an ActiveX in your browser, please update your browser or switch to another one.');
		else
		return xmlHttp;
}

// Cac ham ve ajax

function CheckAllCheckbox(f,checkboxname){
	var len=f.elements.length;
	for(var i=0;i<len;i++){
		if(f.elements[i].name==checkboxname){
			f.elements[i].checked=true;
		}
	}
	return;
}

//-------------------------------------------------------------------------------------------
function UnCheckAllCheckbox(f,checkboxname){
	var len=f.elements.length;
	for(var i=0;i<len;i++){
		if(f.elements[i].name==checkboxname){
			f.elements[i].checked=false;
		}
	}
	return;
}

//------------------------------------------------
function LTrim(Str) {
	return Str.replace(/^\s+/, '');
}

//------------------------------------------------
function RTrim(Str) {
	return Str.replace(/\s+$/, '');
}
//------------------------------------------------
function Trim(Str) {
	return RTrim(LTrim(Str));
}
//-------------------------------------------------------------------------------------------
function isEmpty(Str) {
	empty = (Str === "") ? true :  false;
	return empty;
}

//-------------------------------------------------------------------------------
function isNumber(Digit) {
	return /^\d+[\.\d*]?$/.test(Digit);
}

//------------------------------------------------------------------------------
function isAlphabet(Digit) {
	return /^[a-zA-Z]$/.test(Digit);
}

//-------------------------------------------------------------------------------
function isInteger(Str) {
	return /^[+-]?\d+$/.test(Str);
}

//-------------------------------------------------------------------------------
function isFloat(Str) {
		return /^[+-]?\d+\.{1}\d*$/.test(Str);
}

//-------------------------------------------------------------------------------
function isCurrency(Str) {
		return /^\d+[.]{1}[0-9]{2,}$/.test(Str);
}

function isDomain (Str) {
	// The pattern for matching all special characters. 
  	//These characters include ( ) < > [ ] " | \ / ~ ! @ # $ % ^ & ? ` ' : ; , 
	var specialChars="\\(\\)<>#\\$&\\*!`\\^\\?~|/@,;:\\\\\\\"\\.\\[\\]";
	// The range of characters allowed in a username or domainname. 
	// It really states which chars aren't allowed. 
	var validChars="\[^\\s" + specialChars + "\]";
	 // An atom (basically a series of  non-special characters.) 
	var atom=validChars + '+';
	// The structure of a normal domain 
	var domainPat=new RegExp("^" + atom + "(\\." + atom +")*$");
	
	// Check if IP
	var ipDomainPat=/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/;
	var IPArray=Str.match(ipDomainPat);
	if (IPArray!=null) {
  	// this is an IP address
	 	 for (var i=1;i<=4;i++) {
	    		if (IPArray[i]>255) {
	 			return false
	   		 }
   		 }
	}
	// Check Domain
	var domainArray=Str.match(domainPat)
	if (domainArray==null) {
    		return false;
	}

	/* domain name seems valid, but now make sure that it ends in a
	 three-letter word (like com, edu, gov ... ) or a two-letter word,
   	representing country (uk, vn) or a four-letter word (.info), and that there's a hostname preceding 
   	the domain or country. */

	/* Now we need to break up the domain to get a count of how many atoms
	it consists of. */
	var atomPat=new RegExp(atom,"g")
	var domArr=Str.match(atomPat)
	var len=domArr.length
	if (domArr[domArr.length-1].length<2 || domArr[domArr.length-1].length>4) {
	 // the address must end in a two letter or three letter word or four-letter word.
		return false;
	}

	// Make sure there's a host name preceding the domain.
	if (len<2) {
   		 return false;
	}

	return true;
}

function isPhone(strPhone) {
	return  /^(\d{6,15})$/.test(strPhone);
	//return  /^[\+\-\(]?(\d*[\.\-\(\)\s\+]*\d*)*$/.test(strPhone);
}

function isMobile(strMobile) {
	return  /^(\d{9,15})$/.test(strMobile);
	//return  /^[\+\-\(]?(\d*[\.\-\(\)\s\+]*\d*)*$/.test(strPhone);
}

function isUser (Str) {
	var specialChars="\\(\\)<>#\\$&\\*!`\\^\\?~|/@,;:\\\\\\\"\\.\\[\\]";
	var validChars="\[^\\s" + specialChars + "\]";
	/* The pattern applies if the "user" is a quoted string (in
   	which case, there are no rules about which characters are allowed
   	and which aren't; anything goes).  E.g. "le nguyen vu"@webtome.com
   	is a valid (legal) e-mail address. */
	var quotedUser="(\"[^\"]*\")";
	var atom=validChars + '+'
	var word="(" + atom + "|" + quotedUser + ")";
	var userPat=new RegExp("^" + word + "(\\." + word + ")*$");
	// See if "user" is valid 
	if (Str.match(userPat)==null) {
    		return false ;
	}
	return true;
}

function isEmail (emailStr) {
	/* The pattern for matching fits the user@domain format. */
	var emailPat=/^(.+)@(.+)$/ ;
	var matchArray=emailStr.match(emailPat);
	if (matchArray==null) {
 	 /* Too many/few @'s or something; basically, this address doesn't
    	 even fit the general mould of a valid e-mail address. */
		return false;
	}
	var user=matchArray[1];
	var domain=matchArray[2];

	// See if "user" is valid 
	if (!isUser(user)) {
    	// user is not valid
   		 return false ;
	}

	// Check Domain
	if (!isDomain(domain)) {
   		return false;
	}
	return true;
}

function openNewWindow(linkurl,imgh,imgw,s) {
	var w = screen.availWidth;
	var h = screen.availHeight;
	var leftPos = (w-imgw)/2, topPos = (h-imgh)/2;
	window.open(linkurl,'popup','location=0,status=0,scrollbars='+s+',width=' + imgw + ',height=' + imgh + ',top=' + topPos + ',left=' + leftPos);
}

function getExtension(fileName){
		return fileName.substr(fileName.lastIndexOf(".")+1);
}

function hiddeContentBlock(blid) {
	if(fetch_object('block_content_'+blid).style.display == 'none') {
		fetch_object('block_content_'+blid).style.display ='block';
	} else {
		fetch_object('block_content_'+blid).style.display ='none';
	}				
}	

function showAlert(e_name,msg){
	var eObj = document.getElementById(e_name + '_err');
	if(eObj){
		if(msg!=null){	
			eObj.innerHTML = msg + "<br/>";
		}
		eObj.style.display = '';	
	}
}
function hideAlert(e_name){
	var eObj = document.getElementById(e_name + '_err');
	if(eObj){
		eObj.style.display = 'none';	
	}
}

function FormatNumber(str){
			var strTemp = GetNumber(str);
			if(strTemp.length <= 3)
				return strTemp;
			strResult = "";
			for(var i =0; i< strTemp.length; i++)
				strTemp = strTemp.replace(",", "");
			for(var i = strTemp.length; i>=0; i--)
			{
				if(strResult.length >0 && (strTemp.length - i -1) % 3 == 0)
					strResult = "," + strResult;
				strResult = strTemp.substring(i, i + 1) + strResult;
			}	
			return strResult;
		}
		
function GetNumber(str,ale)
			{
				for(var i = 0; i < str.length; i++)
				{	
					var temp = str.substring(i, i + 1);		
					if(!(temp >= 0 && temp <=9))
					{
						alert(ale);
						return str.substring(0, i);
					}
					
				}
				return str;
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

function tcstoggle(id) {
	xid =  document.getElementById(id);
	if (xid) { 
		if (xid.className == "invnav") {
			xid.className = "subnav";
		} else {
			xid.className = "invnav";
		}
	}
}	

function aj_fetch_string(str) {
	str = str.replace(/&/g,"**am**");
	str = str.replace(/=/g,"**eq**");
	str = str.replace(/\+/g,"**pl**");	
	return str;
}

function ajaxinfoget(url,loadbar,receive,uglyHack) {
	var xmlHttp3 = createxmlHttp();
	xmlHttp3.onreadystatechange = function(){
		if(xmlHttp3.readyState == 1 || xmlHttp3.readyState == 2){
			fetch_object(loadbar).style.display = 'block';
		}
		else if (xmlHttp3.readyState == 4 && xmlHttp3.status == 200){
			fetch_object(loadbar).style.display = 'none';
			if ((uglyHack) && (receive != null)) {
				var r = xmlHttp3.responseText;
				var s = new Array();
				r = r.split('<script>');
				for (var i = 1; i < r.length; i++) {
					s[s.length] = r[i].substr(0, r[i].indexOf('</script>'))
					r[i] = r[i].substr(r[i].indexOf('</script>') + 9)
				}
				r = r.join("")
				fetch_object(receive).innerHTML = r
				var scriptTag;
				for (i = 0; i < s.length; i++) {
					scriptTag = document.createElement('script')
					if (is_ie) scriptTag.text = s[i]
					else scriptTag.appendChild(document.createTextNode(s[i]))
					fetch_object(receive).appendChild(scriptTag)
				}
			} else if (receive != null) fetch_object(receive).innerHTML = xmlHttp3.responseText;
		}
	}
	xmlHttp3.open("GET", url, true);
	xmlHttp3.send(null);
}

function ajaxinfopost(url,request,loadbar,receive,uglyHack) {
	var xmlHttp4 = createxmlHttp();
	if ((xmlHttp4.readyState == 4) || (xmlHttp4.readyState == 0 )) {
		xmlHttp4.open("POST", url, true);
		xmlHttp4.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttp4.setRequestHeader("Content-length", request.length);
		xmlHttp4.setRequestHeader("Connection", "close");
		xmlHttp4.onreadystatechange = function() {
			if ((xmlHttp4.readyState == 1) || (xmlHttp4.readyState == 2)) {
				fetch_object(loadbar).style.display ='block';
			} else if ((xmlHttp4.readyState == 4) && (xmlHttp4.status == 200)) {
				fetch_object(loadbar).style.display ='none';
				if ((receive != null) && (!uglyHack)) fetch_object(receive).innerHTML = xmlHttp4.responseText;
				else if (uglyHack) document.getElementById(uglyHack).value = xmlHttp4.responseText
			}
		}
		xmlHttp4.send(request);
	} else setTimeout('ajaxinfopost(url,request,loadbar,receive)',10000);
}
function show_video_block(vlink,imglink,title) {
	fetch_object('divplayer12').innerHTML = '<div style="font-weight:bold; padding:4px 0px 4px 0px; border-bottom:1px solid #CCC">'+title+'</div><iframe width="300" height="250" src="//www.youtube.com/embed/'+vlink+'?rel=0" frameborder="0" allowfullscreen></iframe>';
	return false;
}
