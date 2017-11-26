
var messages = {

    popupChat: function(e)
    {
        var user = $(e);
        var id = user.attr('id');
        var type = user.attr('title');
        $('#receiver_id').attr('value',id);
        $('#receiver_id').attr('title',type);
        $('#game-section').empty();

        $('#userName').text(user.text());
        manager.getUserData(id,type);
        $('.chatMessage').empty();

        //show the chat option once clicked on user.

        // $('#chat-section').css('display',"block");


        // $('.tabcontent').last().css('display','none');
        // $('.tablinks').removeClass("active");
        // $('.tablinks').first().addClass("active");
    },





    /**
     * This function sends the single chat to the server.
     * @param e
     */
    // need to remove dependency.
    send : function(e) {
        var messageBox = $(e).prev();
        var message = messageBox.val();
        messageBox.val('');
        var id = $('#receiver_id').attr('value');
        var type = $('#receiver_id').attr('title');
        manager.myXHR('POST','./messages',{id:id,message: message,type: type},'').done(function(response){
            messages.putMessage(response);
        });
    },

    /**
     * This function takes the response from the server and create the message box.
     * @param res
     */
    createChat: function(res){
    res.data.forEach(function(e) {
        message = '<div class="row" style="color:#090909;height:auto;' +
            'border-bottom:0.5px dashed rgba(0,0,0,0.1);margin-top:5px;margin-bottom:5px;">' + '<div class="col-md-1"><img src="'+e.userimage+'" ' +
            'style="height:40px;width:40px"><span style="font-size:12px;font-weight:300;font-style:Sans Serif">'+e.created_at+'</span></div>' +
            '<div class="col-md-11"><h5 style="font-weight:bolder">'+e.username+'</h5><span>'+ e.body +'</span></div></div>';
        $('.chatMessage').append(message);
        $("#lst_saved").attr('value',e.created_at);
        });
    },
    putMessage :function(res) {
        if(res.status) {

            if (res.data.length === 0) return;
            if (res.data.length === 1) messages.createChat(res);
            else {
                $('.chatMessage').empty();
                messages.createChat(res);
            }
        }
    }

}


// setInterval(function() {
//     var id = $('#receiver_id').attr('value');
//     var date = $('#lst_saved').attr('value');
//     var url = './messages/getMore/' + id+'/'+date;
//     myXHR('GET',url,{id:id},'');
//     var chatBox = $('.chatMessage');
// }, 1000);


