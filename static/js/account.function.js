/*
 * Carbon-Forum
 * https://github.com/lincanbin/Carbon-Forum
 *
 * Copyright 2006-2015 Canbin Lin (lincanbin@hotmail.com)
 * http://www.94cb.com/
 *
 * Licensed under the Apache License, Version 2.0:
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * A high performance open-source forum software written in PHP. 
 */

function CheckUserName() {
	if($("#UserName").val() && $("#UserName").val().length >=4 && $("#UserName").val().length <= 20){
		$.ajax({
			url: WebsitePath + '/json/user_exist',
			data: {
				UserName: $("#UserName").val()
			},
			type: 'post',
			dataType: 'json',
			success: function(Json) {
				if (Json.Status == 1) {
					$("#UserName").addClass("inputnotice");
				}else{
					$("#UserName").removeClass("inputnotice");
				}
			}
		});
	}else{
		$("#UserName").addClass("inputnotice");
	}
	
}


function CheckPassword() {
	//if ($("#Password").val() != $("#Password2").val() || $("#Password").val().length < 6){
	if ($("#Password").val() != $("#Password2").val()){
		$("#Password").addClass("inputnotice");
		$("#Password2").addClass("inputnotice");
	}else{
		$("#Password").removeClass("inputnotice");
		$("#Password2").removeClass("inputnotice");
	}
}


function CheckMail() {
	if(Mail.indexOf("@") == -1 || Mail.indexOf(".") == -1) {
		ShowCheckResult("CheckMail", "您没有输入Email或输入有误","0");
		return;
	}

}