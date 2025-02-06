function updateAd(bnid) {
	var i = Math.round((id.length - 1) * Math.random());
	var bmod_arr = bmodules[i].split("|");
	if (bmodules == "all" || ("all" in oc(bmod_arr)) || (("home" in oc(bmod_arr)) && (home == 1)) || (module_name in oc(bmod_arr))) {
		if (getExtension(images[i]) == "swf") {
			var code = '<a href="' + links[i] + '" target="' + (target[i]==1)?'_blank':'_self' + '" title="' + imgtext[i] + '">';
			code += "<OBJECT classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" ";
			code += "codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0\" ";
			code += 'WIDTH="' + bwidth + '"';
			if (absbn == 1) code += ' HEIGHT="' + bheight + '"';
			code += ">";
			code += "<PARAM NAME=movie VALUE=\"" + path_upload + "/adv/" + images[i] + "\">";
			code += "<PARAM NAME=menu VALUE=false>";
			code += "<PARAM NAME=quality VALUE=high>";
			code += "<PARAM NAME=wmode VALUE=transparent>";
			code += "<PARAM NAME=scale VALUE=noscale>";
			code += "<EMBED src=\"" + path_upload + "/adv/" + images[i] + "\" menu=false quality=high wmode=opaque scale=noscale WIDTH=\"" + bwidth + '"';
			if (absbn == 1) code += ' HEIGHT="' + bheight + '"';
			code += " TYPE=\"application/x-shockwave-flash\" ";
			code += "PLUGINSPAGE=\"http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash\">";
			code += "</EMBED>";
			echo += "</OBJECT></a>";
		} else {
			var code = '<div style="margin-bottom: 1px; width: ' + bwidth + "px;\">";
			code += '<a href="' + links[i] + '" target="';
			code += (target[i]==1)?'_blank':'_self';
			code += '" title="' + imgtext[i] + '">';
			code += '<img border="0" src="' + path_upload + "/adv/" + images[i] + '" width="' + bwidth + '"';
			if (absbn == 1) code += ' height="' + bheight + '"';
			code += "></a></div>";
		}
		fetch_object("randomBanner" + bnid).innerHTML = code;
	}
}