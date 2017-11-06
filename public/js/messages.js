
function popupChat(e)
{
    var user = $(e);
    id = user.attr('title');

    console.log(id);

    var url = './messages/' + id;

    myXHR('GET',url,{id:id},'');
    $('.chatMessage').empty();
    $('#receiver_id').attr('value',id);

}

function send(e){
    var messageBox = $(e).prev();
    var message = messageBox.val();
    messageBox.val('');
    var id = $('#receiver_id').attr('value');
    myXHR('POST','./messages',{id:id,message:message},'');
}

function putMessage(res){


    $('#game').addClass('invisible');
    $('#message').removeClass('invisible');
    console.log("this should work");
    console.log(res);
    var messages = '';
    if(res.length === 1)
    {
        res.forEach(function(e) {
            messages = '<div><span>' + e.sender +'</span><p>'+ e.body +'</p></div>';
            $('.chatMessage').append(messages);
            $("#lst_saved").attr('value',e.created_at);
        });return;
    }
        res.forEach(function(e) {
            messages = '<div><span>' + e.sender +'</span><p>'+ e.body +'</p></div>';
            $('.chatMessage').append(messages);
            $("#lst_saved").attr('value',e.created_at);
        });


}


function myXHR(methodName,url,data,id){
    $.ajax({
        url: url,
        headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
        data: data,
        type: methodName,
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

setInterval(function() {
    var id = $('#receiver_id').attr('value');
    var date = $('#lst_saved').attr('value');
    var url = './messages/getMore/' + id+'/'+date;
    myXHR('GET',url,{id:id},'');
}, 2000);
