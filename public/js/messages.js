
function popupChat(e)
{
    var user = $(e);
    id = user.attr('title');

    console.log(id);
    $('#game').addClass('invisible');
    $('#message').removeClass('invisible')



    myXHR('post',{id:is,message:'Hi!'},'');

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
