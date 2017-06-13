/**
 * Created by lincanbin on 2017/4/17.
 */
$("#SendMessageButton").click(function () {
	$("#SendMessageButton").val(Lang['Submitting']);
	$.ajax({
		url: WebsitePath + '/inbox/' + $('#InboxID').val(),
		type: 'POST',
		data: {
			Content: $('#MessageContent').val()
		},
		dataType: 'json',
		success: function (Result) {
			if (Result.Status === 1) {
				$("#MessagesList").html('');
				$("#MessagesPage").val('1');
				loadMoreMessages(true);
				$('#MessageContent').val('');
				$("#SendMessageButton").val(Lang['Send_Message']);
			} else {
				alert(Lang['Submit_Failure']);
				$("#SendMessageButton").val(Lang['Submit_Again']);
			}
		},
		error: function () {
			alert(Lang['Submit_Failure']);
			$("#SendMessageButton").val(Lang['Submit_Again']);
		}
	});
});