//提交前的检查
function SubmitCheck()
{
	if(!UE.getEditor('editor').getContentTxt().length){
		alert("内容不能为空！");
		UE.getEditor('editor').focus();
		return false;
	}else{
		if(window.localStorage){
			//清空草稿箱
			StopAutoSave();
		}
		UE.getEditor('editor').setDisabled('fullscreen');
		return true;
	}
}

//回复某人
function Reply(UserName, PostFloor, PostID)
{
	UE.getEditor('editor').setContent('<p>回复<a href="'+location.pathname+'#Post'+PostID+'">#'+PostFloor+'</a> @'+UserName+' :<br /></p>', false);
	UE.getEditor('editor').focus(true);
}

if(window.localStorage){
	var saveTimer= setInterval(function(){
		if(UE.getEditor('editor').getContentTxt().length>=4){
			localStorage.setItem(Prefix+"PostContent", UE.getEditor('editor').getContent());
		}
	},2000); //每隔N秒保存一次

	function StopAutoSave(){
		clearInterval(saveTimer); //停止保存
		localStorage.removeItem(Prefix+"PostContent"); //清空内容
		UE.getEditor('editor').execCommand("clearlocaldata");//清空Ueditor草稿箱
	}

	function RecoverContents(){
		var DraftContent = localStorage.getItem(Prefix+"PostContent");
		if(DraftContent)
		{
			UE.getEditor('editor').setContent(DraftContent);
		}
	}
}