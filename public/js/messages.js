function popupChat(e)
{
    var user = $(e);
    id = user.attr('title');

    console.log(id);

    var chatBox = '<div style="float:left;border:2px solid #090909;padding:5px;margin-left:10px; width:200px;overflow:hidden;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;" class="chatBox">' +
        '<div style="border:1px solid #337ab7;padding-left:5px;border-radius:2px;background-color: #1b6d85;color:#fff">' +
        '<h5>' + user.prev().text() + '</h5></div><div>' +
        '<div class="chatMessage" style="height:200px">' +
        '</div><div style="width:100%;color:#000;text-align:right"><input style="border:none;width:100%;height:40px;margin-top:5px;margin-bottom: 5px;" type="text" name="message" class="message">' +
        '<button onclick="send(this)" style="border:1px solid #080808; -webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;background-color: #337ab7">Send</button>' +
        '<input type="hidden" value="'+id+'"</div></div>';

    $('body').append(chatBox);
    myXHR('post',{id:id,message:'Hi!'},'');

}

function send(e){
    var messageBox = $(e).prev();
    var message = messageBox.val();
    messageBox.val('');
    var id = $(e).next().val();
    //console.log(message);
    myXHR('post',{id:id,message:message});
}

function putMessage(res){

}


function myXHR(methodName,data,id){
    $.ajax({
        url: './messages',
        headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
        data: data,
        type: 'POST',
        datatype: 'JSON',
        beforeSend:function(){
            //turn on spinner if needed
            if(id){
                $(id).append('<img src="path/spinner.jpg" class="spinner"/>');
            }
        }
    }).always(function(){
        //clean up, kill spinner
        if(id){
            $(id).find('.spinner').fadeOut(2000,function(){
                $(this).remove();
            });
        }
    }).fail(function(err){
        //put message out
        console.log(err);
    }).done(function(response){putMessage(response);});
}
