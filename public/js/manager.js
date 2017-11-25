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
                util.showPlayGround();
                game.init(response);
            }
        });
    },

    invite: function(){
        var id = $('#receiver_id').attr('value');
        var url = './game/invite/'+ id;
        manager.myXHR('POST',url,'','').done(function(response){
            util.showInviteMessage();
        });
    },

    getChallenge: function(id){
        var url = './game/challenge/' + id;
        return manager.myXHR('GET',url);
    }
};