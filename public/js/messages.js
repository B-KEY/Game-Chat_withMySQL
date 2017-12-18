
var messages = {

    receiver: '',
    lastRetrieved: '',
    type:'',
    updateChat: '',

    /**
     * This function sends the single chat to the server.
     * @param e
     */
    send : function() {
        var message = variable._chatInputText$.val();
        variable._chatInputText$.val('');
        manager.myXHR('POST','./messages',{id:messages.receiver, message: message, type: messages.type}, '')
            .done(function(response){
                (response.status)
                    ? messages.handleMessageSection(response.data.messageData)
                    : util.showModal('error');
        });
    },

    /**
     * This function takes the response from the server and create the message box.
     * @param res
     */
    createChat: function(res) {
        res.forEach(function(e) {
            message = '<div class="row" style="color:#090909;height:auto;' +
            'border-bottom:0.5px dashed rgba(0,0,0,0.1);margin-top:5px;margin-bottom:5px;">' + '<div class="col-md-1"><img src="'+e.userImage+'" ' +
            'style="height:40px;width:40px"><span style="font-size:12px;font-weight:300;font-style:Sans Serif">'+e.created_at+'</span></div>' +
            '<div class="col-md-11"><h5 style="font-weight:bolder">'+e.userName+'</h5><span>'+ e.body +'</span></div></div>';
        variable._chatMessages$.append(message);
        $("#lst_saved").attr('value',e.created_at);
        messages.lastRetrieved = e.created_at;
        });
    },

    handleMessageSection: function(response) {
        if( response.length === 0 ) return;
        if( response.length === 1 ) {
            messages.createChat(response);
        } else {
            variable._chatMessages$.empty();
            messages.createChat(response);
        }
    },

    setMessageVaraibles: function (id, type, text){
        messages.receiver = id;
        messages.type  = type;
        variable._receiverID.setAttribute('value', messages.receiver);
        variable._receiverID.setAttribute('title', messages.type);
        variable._userName$.text(text);
    },
    getMoreMessages : function() {
        var url = './messages/getMore/' + messages.receiver+'/'+messages.lastRetrieved+'/'+messages.type;
        manager.myXHR('GET',url,{id:messages.receiver},'').done(function (response) {
            messages.handleMessageSection(response.data.messageData);
        });
    }
}




