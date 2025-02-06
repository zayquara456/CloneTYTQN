function checkAddAuthor(f) {
	var err = 0;
	for(var i=0;i<f.elements.length;i++){
		var e = f.elements[i];
		if(e.type=='text')	e.value = Trim(e.value);
	}
	
	if(f.adacc.value ==''){
		showAlert('adacc',null);
		err = 1;
	}else{
		hideAlert('adacc');	
	}
	
	if(f.adname.value ==''){
		showAlert('adname',null);
		err = 1;
	}else{
		hideAlert('adname');	
	}
	
	if(!isEmail(f.email.value)){
		showAlert('email',null);
		err = 1;
	}else{
		hideAlert('email');	
	}
	
	if(f.password.value ==''){
		showAlert('password',null);
		err = 1;
	}else{
		hideAlert('password');	
	}
	
	if(!err) {
		fetch_object('ajaxload_container').style.display ='block';
		f.submit.disabled = true;
		return true;	
	} else {
		return false;	
	}		
}

function checkEditAuthor(f) {
	var err = 0;
	for(var i=0;i<f.elements.length;i++){
		var e = f.elements[i];
		if(e.type=='text')	e.value = Trim(e.value);
	}
	
	if(f.adacc.value ==''){
		showAlert('adacc',null);
		err = 1;
	}else{
		hideAlert('adacc');	
	}
	
	if(f.adname.disabled == false) {
		if(f.adname.value ==''){
			showAlert('adname',null);
			err = 1;
		}else{
			hideAlert('adname');	
		}
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

	