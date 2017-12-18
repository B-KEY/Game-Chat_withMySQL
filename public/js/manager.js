/**
 * Manages AJAX calls, notification and challenge for a particular user.
 * @type {{myXHR: manager.myXHR, showChallenges: manager.showChallenges, acceptChallenge: manager.acceptChallenge, invite: manager.invite, getChallenge: manager.getChallenge}}
 */

manager = {
    myXHR : function (methodName,url,data,id){
    return $.ajax({
            url: url,
            headers: { 'X-CSRF-TOKEN': $('input[name=_token]').val() },
            data: data,
            type: methodName,
            datatype: 'JSON',
            beforeSend:function(){}
    }).always(function(){})
        .fail(function(err){
        console.log(err);
        });
    },


    showChallenges : function(e){
        $('#showChallengers').toggleClass('invisible');
    },

    acceptChallenge: function (e){
        var id = $(e).attr('id');
        $(e).remove();
        manager.myXHR('POST','./game/accept/' + id,'','').done(function( res ){
            if ( res.status ) {
                messages.setMessageVaraibles(id, 'individual',$(e).text());
                manager.getUserData(id,'individual');
            }
        });
    },
    getGameData: function ( id ) {
        var url = './game/board/' + id;
        manager.myXHR('GET',url,'','').done(function(response){
            if(response){
                variable._gameSection$.append(game.PLAYGOUND_DIV);
                game.drawGame(response.data.gameData);
            }
        });
    },

    invite: function(){
        var id = $('#receiver_id').attr('value');
        var url = './game/invite/'+ id;
            manager.myXHR('POST',url,'','').done(function(response){
            $('#inviteThisUser').addClass('invisible');
        });
    },

    getChallenge: function(id){
        var url = './game/challenge/' + id;
        return manager.myXHR('GET',url);
    },

    /**
     * This function is used to get all the messges between individual or group users.
     * @param id id of the receiver.
     * @param type type of the user.
     */
    getUserData:function (id,type) {
        manager.myXHR('GET','./dashboard/'+ type + '/' +id,{type:type},'').done(function(response) {
            manager.setSections(response);
        });
    },

    setSections :function(res) {
        if(res.status){
            variable._userStat$.remove();
            variable._game$.removeClass('invisible');

            messages.handleMessageSection(res.data.messageData);
            //messages.updateChat = setInterval(messages.getMoreMessages, 3000);
            if(res.challengeStatus){
                variable._inviteThisUser$.addClass('invisible');
                variable._chatSection$.removeClass('col-md-12');
                variable._gameSection$.addClass('col-md-8');
                variable._chatSection$.addClass('col-md-4');

                if(res.challengeStatus === 'requested'){
                    variable._gameSection$.append(variable._invitedDiv);
                } else if(res.challengeStatus === 'accepted'){
                    variable._gameSection$.append(variable._playgroundDiv);
                    game.drawGame(res.data.gameData);
                }else{
                    manager.getGameData($('#receiver_id').attr('value'));
                }
            }
            else{
                variable._inviteThisUser$.removeClass('invisible');
                $('#game-section').empty();
                variable._chatSection$.addClass('col-md-12');
                variable._gameSection$.removeClass('col-md-8');
            }
        } else    {
            console.log(res);
        }
    },

    getThisUserData: function (e) {
        if(game.canChangeUser()){
            util.allWarning('Can\'t change user[drag your piece]');
            return;
        }
        clearInterval(messages.updateChat);
        var id = $(e).attr('id');
        var type = $(e).attr('title');
        var text = $(e).text();
        messages.setMessageVaraibles(id, type, text);
        variable._gameSection$.empty();
        manager.getUserData(messages.receiver,messages.type);
        variable._charMessages$.empty();
    }
};


// Get the modal
var modal = document.getElementById('myModal');

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// // When the user clicks on the button, open the modal
// btn.onclick = function() {
//     modal.style.display = "block";
// }

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}