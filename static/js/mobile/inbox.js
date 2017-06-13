/**
 * Created by lincanbin on 2017/4/17.
 */

function loadMessagesList(_this) {
	if ((new RegExp("/inbox/[0-9]+$")).test(window.document.location.pathname)) {
		var top = _this.scrollTop();
		if (top + $(".panel.active[selected=true]").height() + 20 >= _this[0].scrollHeight && top > 20) {
			loadMoreMessages(false);
		}
	} else {
		return false;
	}
}

function loadMoreMessages(forceToShow) {
	var MessagesList = $("#MessagesList");
	var InboxID = $("#InboxID").val();
	var MessagesPage = $("#MessagesPage");
	var MessagesLoading = $("#MessagesLoading");

	if (forceToShow || MessagesLoading.val() !== "1") {
		MessagesLoading.val("1");
		$.ajax({
			url: WebsitePath + '/inbox/' + InboxID + '/list/page/' + MessagesPage.val(),
			type: 'GET',
			dataType: 'json',
			success: function(Result) {
				MessagesLoading.val("0");
				if (Result.Status === 1) {
					var Template = $("#MessageTemplate").html();
					for (var i = Result.MessagesArray.length - 1; i >= 0; i--) {
						Result.MessagesArray[i]['Position'] = Result.MessagesArray[i]['IsMe'] ? 'right' : 'left';
					}
					MessagesList.append(renderTemplate(Template, Result.MessagesArray));
					MessagesPage.val(parseInt(MessagesPage.val()) + 1);
					if (Result.MessagesArray.length === 0) {
						MessagesLoading.val("1");
					}
				}
			},
			error: function() {
				MessagesLoading.val("0");
			}
		});
	}
}


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