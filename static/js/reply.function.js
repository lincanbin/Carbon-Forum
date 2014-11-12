//提交前的检查
function ReplyToTopic()
{
	if(!UE.getEditor('editor').getContent().length){
		alert("内容不能为空！");
		UE.getEditor('editor').focus();
	}else{
		$("#ReplyButton").val(" 回复中…… ");
		UE.getEditor('editor').setDisabled('fullscreen');
		$.ajax({
			url: WebsitePath+'/reply',
			data:{
				FormHash: document.reply.FormHash.value,
				TopicID: document.reply.TopicID.value,
				Content: UE.getEditor('editor').getContent()
			},
			type:'post',
			cache:false,
			dataType:'json',
			async:false,//阻塞防止干扰
			success:function(data){
				if(data.Status==1){
					$("#ReplyButton").val(" 回复成功 ");
					location.href = WebsitePath+"/t/"+data.TopicID+(data.Page>1?"-"+data.Page:"");  
					if(window.localStorage){
						//清空草稿箱
						StopAutoSave();
					}
				}else{
					alert(data.ErrorMessage);
					UE.getEditor('editor').setEnabled();
				}
			},
			error:function(){
				alert("回复失败，请再次提交");
				UE.getEditor('editor').setEnabled();
				$("#ReplyButton").val(" 再次提交 ");
			}
		});
	}
	return true;
}

//回复某人
function Reply(UserName, PostFloor, PostID)
{
	UE.getEditor('editor').setContent('<p>回复<a href="'+location.pathname+'#Post'+PostID+'">#'+PostFloor+'</a> @'+UserName+' :<br /></p>', false);
	UE.getEditor('editor').focus(true);
}

if(window.localStorage){
	var saveTimer= setInterval(function(){
		if(UE.getEditor('editor').getContent().length>=10){
			localStorage.setItem(Prefix+"PostContent", UE.getEditor('editor').getContent());
		}
	},1000); //每隔N秒保存一次

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