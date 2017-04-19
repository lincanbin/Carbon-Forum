/**
 * Created by lincanbin on 2017/4/17.
 */
$("#SendMessageButton").click(function(){
    $.ajax({
        url: WebsitePath + '/inbox/' + $('#InboxID').val(),
        type: 'POST',
        data: {
            Content: $('#MessageContent').val()
        },
        dataType: 'json',
        success: function(Result) {
            if (Result.Status === 1) {
                $("#MessagesList").html('');
                $("#MessagesPage").val('1');
                loadMoreMessages(true);
                $('#MessageContent').val('');
            } else {
                alert('failed');
            }
        },
        error: function() {
            alert('failed');
        }
    });
});