

var isIE=(navigator.userAgent.indexOf('MSIE')!=-1);function $(objID)

{return document.getElementById(objID);};function $SetTargetBlankForAllHref()

{var _as=document.getElementsByTagName('a');for(var i=0;i<_as.length;i++)

{if(_as[i].target=='')

{var hrf=_as[i].href;if(hrf=='')

break;var reg=new RegExp(/javascript/ig);if(!reg.test(hrf))

_as[i].target='_blank';}}};function $CreateFlashControl(src_,width,height)

{var HTML='<object align="baseline" border="0" classid="clsid:D27CDB6E-AE6D-11CF-96B8-444553540000" ';if(width!=0)

HTML+=' width="'+width+'"';else

HTML+=' width="200"';if(height!=0)

HTML+=' height="'+height+'"';else

HTML+=' height="150"';HTML+=' >';HTML+='<param name="_cx" value="5292" />';HTML+='<param name="_cy" value="3969" />';HTML+='<param name="FlashVars" value="5292" />';HTML+='<param name="Movie" value="'+src_+'" />';HTML+='<param name="Src" value="'+src_+'" />';HTML+='<param name="WMode" value="Transparent" />';HTML+='<param name="Play" value="-1" />';HTML+='<param name="Loop" value="-1" />';HTML+='<param name="Quality" value="High" />';HTML+='<param name="SAlign" value="" />';HTML+='<param name="Menu" value="-1" />';HTML+='<param name="Base" value="" />';HTML+='<param name="AllowScriptAccess" value="always" />';HTML+='<param name="Scale" value="ShowAll" />';HTML+='<param name="DeviceFont" value="0" />';HTML+='<param name="EmbedMovie" value="0" />';HTML+='<param name="BGColor" value="" />';HTML+='<param name="SWRemote" value="" />';HTML+='<embed src="'+src_+'"';HTML+=' wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"';HTML+=' type="application/x-shockwave-flash"';HTML+=' name="AdObj1" quality="High"';if(width!=0)

HTML+=' width="'+width+'"';else

HTML+=' width="200"';if(height!=0)

HTML+=' height="'+height+'"';else

HTML+=' height="150"';HTML+=' >';HTML+='</object>';return HTML;};function $TabClick(this_)

{Os_Tab.$Click(this_,function(){document.getElementById('mode').value=this_.id;});};var o_Tab={$c:function(this_,function_)

{var _parentNode=this_.parentNode;var Nodes=_parentNode.childNodes;for(var i=0;i<Nodes.length;i++)

{var tab_=Nodes[i];if(tab_.nodeName.toLowerCase()!='a'&&tab_.nodeName.toLowerCase()!='div')

continue;if(this_==tab_)

tab_.className='curnav';else

tab_.className='';}

if(function_)

function_();}};function o_Tab1(this_,search_,url_)

{o_Tab.$c(this_,function()

{var osearch=$('otextsearch');var frm=$('o_frmSearch');var mod=$('o_SearchMode');if(search_=='news')

{frm.action='http://news.zing.vn/news/search.aspx';return;}

else

if(search_=='mp3')

{frm.action='http://mp3.zing.vn/mp3/search/do.html';return;}

if(search_!='')

mod.value=search_;else

window.location=url_;osearch.name='q';});};function o_Tab2(this_,id_)

{o_Tab.$c(this_,function()

{});};function o_Tab3(this_,id_)

{o_Tab.$c(this_,function()

{var objid=$('_box_'+id_);if(objid)

{var objcontainer=objid.parentNode.childNodes;for(var i=0;i<objcontainer.length;i++)

{var tab_=objcontainer[i];if(tab_.nodeName.toLowerCase()!='div')

continue;if(objid.id==tab_.id)

tab_.style.display='block';else

tab_.style.display='none';}}});};function $(objID)

{return document.getElementById(objID);};function openWindow(src,title,width,height)

{var leftPos=(screen.availWidth-width)/2;var topPos=(screen.availHeight-height)/2;if(isIE)

{window.open(src,title,'toolbar=no'+',location=no'+',status=yes'+',menubar=no'+',scrollbars=yes'+',resizable=yes'+',width='+width+',height='+height+',top='+topPos+',left='+leftPos);return false;}

else

{window.open(src,title,'height='+height+', width='+width+', left='+leftPos+', top='+topPos+', resizable=yes, scrollbars=yes, toolbar=no, status=yes');return false;}};function voidnull(para)

{};function zThanhVienHot()

{for(var i=0;i<3;i++)

{var obj=$('ltrBaiThu'+i);var contTVHs=obj.innerHTML.split('|');obj.innerHTML='Bài thu: '+contTVHs[1]+'<br/><strong class="clr04">'+contTVHs[0]+' điểm</strong>';}};function zMovieScrn(itemid,itemindex)

{var zmItems=$(itemid+itemindex);var arrs=zmItems.innerHTML.split(',');var cont='';for(var i=0;i<arrs.length-1;i++)

{var item=arrs[i].split('|');var id=parseInt(item[0]);var title=item[1];switch(itemid)

{case'zmDaoDien':cont+='<a title="'+title+'" href="http://movie.zing.vn/movie/zing/dao-dien/d'+id+'.html">'+title+'</a>, ';break;case'zmDienVien':cont+='<a title="'+title+'" href="http://movie.zing.vn/movie/zing/dien-vien/a'+id+'.html">'+title+'</a>, ';break;case'zmTheLoai':cont+='<a title="'+title+'" href="http://movie.zing.vn/movie/zing/g'+id+'.html">'+title+'</a>, ';break;}}

zmItems.innerHTML=cont;};function zMovieScr(spanid)

{for(var j=0;j<3;j++)

zMovieScrn(spanid,j);};var initWeatherValue='t-p-ha-noi';var arrwea_=['AUD','USD','JPY','EUR','HKD'];function initGold()

{var scon='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl01"><tr><th align="right"><img alt="" src="http://www.zing.vn/news/images/icon_gold.gif" width="17" height="20" align="absmiddle" /></th>';scon+='<th><strong class="clr01">VÀNG</strong></th><th align="right">Mua</th><th align="right">Bán</th></tr>';for(var i=0;i<gold.length;i++)

scon+='<tr class="tr01"><td colspan="2">'+gold[i][0]+'</td><td align="right">'+gold[i][1]+'</td><td align="right">'+gold[i][2]+'</td></tr>';$('otblGold').innerHTML=scon+'</table>';};function showTyGia(this_)

{var id=this_.getAttribute('alt')=='1';this_.setAttribute('alt',id?'0':'1');this_.src=id?'http://www.zing.vn/news/images/ico_minus.gif':'http://www.zing.vn/news/images/ico_plus.gif';var trs=$('otblTygia').getElementsByTagName('tbody')[0].childNodes;var ftr=false;for(var t=0;t<trs.length;t++)

{var tr=trs[t];if(tr.nodeName.toLowerCase()!='tr')

continue;if(!ftr)

{ftr=true;continue;}

if(isInTyGia(tr.title))

tr.className='tr02';else

tr.className=id?'tr02':'tr02 hidden';}};function isInTyGia(itemid)

{for(var i=0;i<arrwea_.length;i++)

if(itemid==arrwea_[i])

return true;return false;};function initTyGia()

{var scon='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tbl01">';scon+='<tr align="left"><th style="width: 60%"><img alt="" src="http://www.zing.vn/news/images/icon_exchange.gif" width="17" height="20" hspace="3" vspace="0" align="absmiddle" />';scon+='<strong class="clr01">NGOẠI TỆ</strong><img alt="1" onclick="showTyGia(this);" src="http://www.zing.vn/news/Images/ico_plus.gif" alt="" />';scon+='</th><th style="width: 40%"></th></tr>';for(var i=0;i<rate.length;i++)

{var val=rate[i][0];if(isInTyGia(val))

scon+=('<tr title="'+val+'" class="tr02"><td title="'+rate[i][1]+'">'+val+'</td><td colspan="-1" align="right">'+rate[i][3]+'</td></tr>');else

scon+=('<tr title="'+val+'" class="tr02 hidden"><td title="'+rate[i][1]+'">'+val+'</td><td colspan="-1" align="right">'+rate[i][3]+'</td></tr>');}

scon+='</table>';$('otblTygia').innerHTML=scon;};function zfWeContent(idx)

{var strc='<p class="degree"><strong>'+weather[idx][2]+'</strong></p><div class="cont01">';strc+='<div class="cont01im"><img src="http://www.zing.vn/news/images/weather/'+weather[idx][3]+'" alt="" /></div>';strc+='<div class="cont01desc">'+weather[idx][4]+'<br />Độ ẩm:'+weather[idx][5]+'<br />';strc+=weather[idx][6]+'</div><br class="clear" /></div>';$('zfWeContent').innerHTML=strc;};function zfChange(this_)

{zfWeContent(this_.selectedIndex);};function zfShowWeather()

{var strcboWeather='<select class="sltbox01" onchange="zfChange(this);">';var itemindex=0;for(var i=0;i<weather.length;i++)

{var id=weather[i][0];if(initWeatherValue==id)

itemindex=i;strcboWeather+='<option value="'+id+'"'+(initWeatherValue==id?' selected ':'')+'>'+weather[i][1]+'</option>';}

$('cboWeather').innerHTML=strcboWeather+'</select>';zfWeContent(itemindex);};function setOpacity(this_,opacity)

{if(opacity==0)

{if(this_.style.visibility!="hidden")

this_.style.visibility="hidden";}

else

{if(this_.style.visibility!="visible")

this_.style.visibility="visible";}

if(isIE)

this_.style.filter=(opacity==1)?'':"alpha(opacity="+opacity*100+")";this_.style.opacity=opacity;return this;};function bgOver(this_)

{setOpacity(this_,0.5);};function bgOut(this_)

{setOpacity(this_,1);};function rosrc(src)

{if(src.indexOf('http://')==-1)

src='Advertisement/'+src;return src;};function $rAd(boxID,arrAd)

{var n=arrAd.length;var rand=Math.floor(Math.random()*n+1);rand=arrAd[rand%n];var width=0;var height=0;var adBox=document.getElementById(boxID);if(adBox)

{switch(rand[0])

{case'fla':width=rand[2];height=rand[3];adBox.innerHTML=$CreateFlashControl(rosrc(rand[1]),width,height);break;case'img':width=rand[3];height=rand[4];adBox.innerHTML='<a href="'+rand[1]+'" target="_blank"><img src="'+rosrc(rand[2])+'" width="'+width+'px" height="'+height+'px" alt="" /></a>';break;case'ifr':width=rand[2];height=rand[3];adBox.innerHTML='<iframe src="'+rosrc(rand[1])+'" height="'+height+'" scrolling="no" width="'+width+'" frameborder="0"></iframe>';break;}

adBox.style.display='block';}};