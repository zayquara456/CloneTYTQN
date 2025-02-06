function set_cp_title()
{
	if (typeof(parent.document) != 'undefined' && typeof(parent.document) != 'unknown' && typeof(parent.document.title) == 'string')
	{
		parent.document.title = (document.title != '' ? document.title : 'Farsight Solutions');
	}
}

function show_edit_title(xid,xtitle,mod,z,l, act) {
	fetch_object(mod+'_title_edit_'+xid).innerHTML = '<input type="text" id="ctitle_'+xid+'" value="'+xtitle+'" size="'+z+'">&nbsp;<input type="button" value="'+l+'" onclick="return aj_base_title('+xid+',\''+mod+'\',\''+act+'\');">';
	return false;
}

function aj_base_title(xid,mod,act) {
	if(act =='') { act ='quick_title'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&id='+xid+'&title='+encodeURI(aj_fetch_string(fetch_object('ctitle_'+xid).value)),'ajaxload_container', mod+'_main');
	return false;
}

function show_edit_title_type(xid,xtitle,mod,z,l, act, type) {
	fetch_object(mod+'_title_edit_'+xid).innerHTML = '<input type="text" id="ctitle_'+xid+'" value="'+xtitle+'" size="'+z+'">&nbsp;<input type="button" value="'+l+'" onclick="return aj_base_title_type('+xid+',\''+mod+'\',\''+act+'\',\''+type+'\');">';
	return false;
}

function aj_base_title_type(xid,mod,act,type) {
	if(act =='') { act ='quick_title'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&menu_type='+type+'&id='+xid+'&title='+encodeURI(aj_fetch_string(fetch_object('ctitle_'+xid).value)),'ajaxload_container', mod+'_main');
	return false;
}

function aj_base_delete(xid,mod,la,act,mid) {
	if(confirm(la)) {
		if(act =='') { act ='delete'; }
		if(mid == '') { mid ='id'; }
		ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&load_hf=1','ajaxload_container', mod+'_main');
	}
	return false;
}

function aj_base_status(xid,stat,mod,act,mid) {
	if(act =='') { act ='status'; }
	if(mid == '') { mid ='id'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&stat='+stat+'&load_hf=1','ajaxload_container', mod+'_main');
	return false;
}
function aj_base_start(xid,stat,mod,act,mid) {
	if(act =='') { act ='start'; }
	if(mid == '') { mid ='id'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&stat='+stat+'&load_hf=1','ajaxload_container', mod+'_main');
	return false;
}
function aj_base_status_type(xid,stat,mod,act,mid,type) {
	if(act =='') { act ='status'; }
	if(mid == '') { mid ='id'; }
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+mid+'='+xid+'&stat='+stat+'&type='+type+'&load_hf=1','ajaxload_container', mod+'_main');
	return false;
}
function show_ajaxcontent_byid(xid,mod,act,id,content) {
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+id+'='+xid,'ajaxload_container', content);
	return false;
}


function show_ajaxcontent(xid,mod,act,id,content) {
	ajaxinfoget('modules.php?f='+mod+'&do='+act+'&'+id+'='+xid,'ajaxload_container', content);
	return false;
}