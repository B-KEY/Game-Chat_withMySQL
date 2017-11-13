
function popupChat(e)
{
    var user = $(e);
    var id = user.attr('id');
    var type = user.attr('title');

    var url = './messages/' + id;
    var userName = user.text();
    $('#userName').text(userName);


    myXHR('GET',url,{type:type},'').done(function(response){
        putMessage(response);
    });
    $('.chatMessage').empty();
    $('')
    $('#receiver_id').attr('value',id);
    $('#receiver_id').attr('title',type);
    $('#opponent_id').attr('value',id);

}

/*function popupGroupChat(e)
{
    var user = $(e);
    id = user.attr('id');

    console.log(id);

    var url = './messages/' + id;

    myXHR('GET',url,{type:'group'},'');
    $('.chatMessage').empty();
    $('#receiver_id').attr('value',id);
    $('#reciever_id').attr('title','group');
}*/

function send(e) {
    var messageBox = $(e).prev();
    var message = messageBox.val();
    messageBox.val('');
    var id = $('#receiver_id').attr('value');
    var type = $('#receiver_id').attr('title');
    myXHR('POST','./messages',{id:id,message: message,type: type},'').done(function(response){
        putMessage(response);
    });
}

function putMessage(res) {

    if(res.status){
        $('#game').removeClass('invisible');
        $('#game').prev().remove();
        console.log(res);
        if(res.data.length === 0)
            return;

        res.data.forEach(function(e) {
            message = '<div class="row" style="color:#090909;height:auto;' +
                'border-bottom:0.5px dashed rgba(0,0,0,0.1);margin-top:5px;margin-bottom:5px;">' + '<div class="col-md-1"><img src="'+e.userimage+'" ' +
                'style="height:40px;width:40px"><span style="font-size:12px;font-weight:300;font-style:Sans Serif">'+e.created_at+'</span></div>' +
                '<div class="col-md-11"><h5 style="font-weight:bolder">'+e.username+'</h5><span>'+ e.body +'</span></div></div>';
            $('.chatMessage').append(message);
            $("#lst_saved").attr('value',e.created_at);
        });
    }
    else{
        console.log(res);
    }



}

function invite(e){
    var opponent = $('#opponent_id').attr('value');


}


function myXHR(methodName,url,data,id){
    return $.ajax({
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
    });//.done(function(response){putMessage(response);});
}

// setInterval(function() {
//     var id = $('#receiver_id').attr('value');
//     var date = $('#lst_saved').attr('value');
//     var url = './messages/getMore/' + id+'/'+date;
//     myXHR('GET',url,{id:id},'');
//     var chatBox = $('.chatMessage');
// }, 1000);
