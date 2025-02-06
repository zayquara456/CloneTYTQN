function checkSubmit(f) {
	var err = 0;
	for(var i=0;i<f.elements.length;i++){
		var e = f.elements[i];
		if(e.type=='text')	e.value = Trim(e.value);
	}
	
	if(f.title.value ==''){
		showAlert('title',null);
		err = 1;
	}else{
		hideAlert('title');	
	}
	
	if(!isEmail(f.email.value)){
		showAlert('email',null);
		err = 1;
	}else{
		hideAlert('email');	
	}
	
	if(!err) {
		fetch_object('ajaxload_container').style.display ='block';
		f.submit.disabled = true;
		return true;	
	} else {
		return false;	
	}		
}

	