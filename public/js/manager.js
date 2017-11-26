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
        var url = './game/accept/' + id;
        manager.myXHR('POST',url,'','').done(function(response){
            if(response){
                game.init(response);
            }
        });
    },
    getGameData: function(id){
        $('#game-section').append(game.PLAYGOUND_DIV);
        var url = './game/board/' + id;
        manager.myXHR('GET',url,'','').done(function(response){
            if(response){
                game.init(response);
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
        var url = './messages/' + id;
        manager.myXHR('GET',url,{type:type},'').done(function(response){
            manager.setSection(response);
        });
    },


    setSection :function(res) {
        if(res.status){

            $('#game').removeClass('invisible');
            $('#game').prev().remove();
            console.log(res);

            if(res.data.length === 0) return;
            if(res.data.length === 1) messages.createChat(res);
            else{
                $('.chatMessage').empty();
                messages.createChat(res);
            }
            if(res.challengeData.length!=0){
                $('#inviteThisUser').addClass('invisible');
                $('#chat-section').removeClass('col-md-12');
                $('#game-section').addClass('col-md-8');
                $('#chat-section').addClass('col-md-4');

                if(res.challengeData.challengeStatus === 'requested'){
                    $('#game-section').append(game.INVITED_DIV);
                    console.log(game.INVITED_DIV);
                } else if(res.challengeData.challengeStatus === 'accepted'){
                    manager.getGameData($('#receiver_id').attr('value'));
                }else{
                    manager.getGameData($('#receiver_id').attr('value'));
                    //game.playForUser($('#gameID').attr('value'));
                }
            }
            else{
                $('#inviteThisUser').removeClass('invisible');
                $('#game-section').empty();
                $('#chat-section').addClass('col-md-12');
                $('#game-section').removeClass('col-md-8');
            }
        } else    {
            console.log(res);
        }
    },
};