xMousePos = 0; 
yMousePos = 0; 
xMousePosMax = 0; 
yMousePosMax = 0; 
infoElement= null; 
xFromMouse=0;
yFromMouse=0;
yUpFromMouse=37;
standardStyle='position:absolute;left:0px;top:0px;visibility:hidden;text-align:left;z-index:999;';
vars=new Object();
vars.tags=[ 'a' ]; 
vars.infoAttributeName= 'info'; 
vars.infoElementCentered= true; 
vars.infoElementId=null; 
vars.infoElementStyleClass=null; 
vars.infoElementFontSize= '11px'; 
vars.infoElementTextColor='#000000';
vars.infoElementBGColor= '#FAFAFA';
function setVariables(){
	var settings= window.infoSettings;
	if( settings==null ){
	} else {
	    for( var varName in settings ){
	    	if( settings[varName]!=null )
	    		vars[varName]= settings[varName];	
	    }
	}	
};
setVariables();	
begin();
function begin(){	
	attachInfoElement();
	installTagListeners();
	oldOnmousemove= document.onmousemove;
	if (document.all) {
	    yFromMouse=17;
	    xFromMouse=2;
	    document.onmousemove = captureMousePosition;	
	} else if (document.getElementById) {
	    yFromMouse=21;
	    xFromMouse=2;
	    document.onmousemove = captureMousePosition;		
	}
}
function captureMousePosition(e) {
    if ( document.documentElement&& window.event&& document.documentElement.clientWidth>0 ){
	xMousePos = window.event.x+ document.documentElement.scrollLeft; 
        yMousePos = window.event.y+document.documentElement.scrollTop;
        xMousePosMax = document.documentElement.clientWidth+document.documentElement.scrollLeft;
        yMousePosMax = document.documentElement.clientHeight+document.documentElement.scrollTop-12;        	
   } else if (document.all) {
	xMousePos = window.event.x+document.body.scrollLeft;
        yMousePos = window.event.y+document.body.scrollTop;
        xMousePosMax = document.body.clientWidth+document.body.scrollLeft;
        yMousePosMax = document.body.clientHeight+document.body.scrollTop;
    } else if (document.getElementById) {
        xMousePos = e.pageX;
        yMousePos = e.pageY;
        xMousePosMax = window.innerWidth+window.pageXOffset;
        yMousePosMax = window.innerHeight+window.pageYOffset;
    }
	moveElements();	
	if( oldOnmousemove!=null ){		
		oldOnmousemove(e);
	}
}
function out( string ){
	var outDiv=document.getElementById('out')
	if(outDiv!=null) outDiv.firstChild.nodeValue+=string+" ";
}
function moveElements(){
	moveInfoElement();
}
function attachInfoElement( ){
	var str='<div style="'+standardStyle+'" id="';
	if(vars.infoElementId!=null &&vars.infoElementId.length>0){
		str+= vars.infoElementId+'" ';		
	} else {
		vars.infoElementId='infoElement';
		str+= vars.infoElementId+'"';	
		if(vars.infoElementStyleClass!=null && vars.infoElementStyleClass.length>0){
			str+= ' class="'+vars.infoElementStyleClass+'" ';			
		} else {
			str+=' class="'+vars.infoElementId+'" ';
			var sty='<style type="text/css"><!--';
			sty+=('#'+vars.infoElementId+' {');
			sty+=('background-color: '+vars.infoElementBGColor+';');	
			sty+='color:'+vars.infoElementTextColor+';';
			sty+=('border: 1px solid  #999999;');
			//sty+=('filter: shadow(direction=135,color=#8E8E8E,strength=3);');
			//sty+=('text-align:left;WIDTH: 100%;');
			//sty+=('border-bottom: 2px inset  #424242;');
			//sty+=('border-right: 2px inset  #424242;');
			sty+=('font-size: '+vars.infoElementFontSize+';');
			sty+=('font-family: Verdana,Arial, Helvetica, sans-serif;');
			sty+=('margin:4px;');
			sty+='padding:4px;';
			sty+=('}	--></style>');			
			document.writeln( sty );
		}
	}	
	str+=' > </div>'	
	out(str);
	document.writeln( str );	
}
function installTagListeners( delayTime ){
	infoElement=document.getElementById( vars.infoElementId );
	if( infoElement==null ){} else {		
		for( var k=0; k<vars.tags.length; k++){		
			var links=document.getElementsByTagName(  vars.tags[k].toUpperCase() );
			for(var i=0;links!=null&&i<links.length;i++){				
				var node= links[i];
				var infoText=node.getAttribute( vars.infoAttributeName ); 
				if( infoText!=null && infoText.length > 0&& node.infoText_==null ){
						node.infoText_= infoText;
						node.style.cursor='pointer';
						if( node.onmouseover==null ){
							node.onmouseover= showInfo;					
						} else {
							node.oldOnmouseover= node.onmouseover;
							node.newOnmouseover= showInfo;
							node.onmouseover= function(){
								this.newOnmouseover();
								this.oldOnmouseover();
							}
						}
						if( node.onmouseout==null ){
							node.onmouseout= hideInfo;					
						} else {
							node.oldOnmouseout= node.onmouseout;
							node.newOnmouseout= hideInfo;
							node.onmouseout= function(){
								this.newOnmouseout();
								this.oldOnmouseout();
							}
					   }
				}
			}
		}
	}	
	if( delayTime==null)
		delayTime=0;
	if( delayTime < 2 ){
		delayTime+=0.2;
	}	
	setTimeout('installTagListeners('+delayTime+')',delayTime*1000); 
}
function moveInfoElement(){	
	if( infoElement ==null ){
	} else if( infoElement.style.visibility != 'hidden'){		
		var x=xFromMouse+xMousePos;
		if(vars.infoElementCentered==true){
			x-=0.5*infoElement.offsetWidth;			
		}
		if( xMousePosMax>0 && x+ infoElement.offsetWidth> xMousePosMax-20 ){
			x= xMousePosMax-20- infoElement.offsetWidth;			
		}
		if( x<5 ){
			x=5;
		}		
		var y=yFromMouse+yMousePos;
		if( yMousePosMax>0 && y+ infoElement.offsetHeight> yMousePosMax ){
			y-= yFromMouse+yUpFromMouse;
		}		
		infoElement.style.left=x+'px'; 
		infoElement.style.top=y+'px';		
	} 
}
function setInfoText( text ){			
	var subtext='';
	for(var i=0;i<text.length;++i){		
		if(text.substr(i,4)=='<br/>'){			
			infoElement.appendChild(document.createTextNode(subtext));
			infoElement.appendChild(document.createElement('br'));
			subtext='';
			i+=3;
		} else {
			subtext=subtext+text.charAt(i)
		}
	}	
	infoElement.appendChild( document.createTextNode(subtext));		
}
function unsetInfoText(){
	while(infoElement.hasChildNodes()){
		infoElement.removeChild(infoElement.lastChild);
	}	
}
function showInfo(){		
	unsetInfoText();
	infoElement.left='0px';
	infoElement.top='0px';
	setInfoText( this.infoText_ );		
	infoElement.style.visibility='visible';
	moveInfoElement();			
}
function hideInfo(){	
	if(infoElement) {	
		infoElement.style.visibility='hidden';
	}
	unsetInfoText();	
}



