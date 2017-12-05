/**
 * Manages AJAX calls, notification and challenge for a particular user.
 * @type {{myXHR: manager.myXHR, showChallenges: manager.showChallenges, acceptChallenge: manager.acceptChallenge, invite: manager.invite, getChallenge: manager.getChallenge}}
 */

manager = {

    myXHR : function (methodName,url,data,id){
    return $.ajax({
            url: url,
            headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
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
        manager.myXHR('POST','./game/accept/' + id,'','').done(function(res){
            if(res){
                variable._gameSection$.append(variable._playgroundDiv);
                game.drawGame(res.data.gameData);
            }
        });
    },
    getGameData: function(id){
        //
        var url = './game/board/' + id;
        manager.myXHR('GET',url,'','').done(function(response){
            if(response){
                variable._gameSection$.append(game.PLAYGOUND_DIV);
                game.drawGame(res.data.gameData);
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
        manager.myXHR('GET','./dashboard/'+ type + '/' +id,{type:type},'').done(function(response){
            console.log(id, type);
           // variable._receiverId$.attr('value',id);
            //variable._receiverId$.attr('title',type);
            manager.setSections(response);
        });
    },

    setSections :function(res) {
        if(res.status){

            $('#game').removeClass('invisible');
            $('#game').prev().remove();
            console.log(res);

            if(res.data.messageData.length === 0) return;

            if(res.data.messageData.length === 1) {
                messages.createChat(res);
            } else {
                $('.chatMessage').empty();
                messages.createChat(res);
            }

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
    getThisUserData: function(e){
        var user = $(e),
            id = user.attr('id'),
            type = user.attr('title');
            variable._receiverID.setAttribute('value', id);
            variable._receiverID.setAttribute('title', type);
            variable._gameSection$.empty();
            variable._userName$.text(user.text());

            manager.getUserData(id,type);
            variable._charMessages$.empty();
    }
};