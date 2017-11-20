

var util = {

    openSection: function(evt, section) {
    util.clearGameSection();
    // Declare all variables
    var i, tabcontent, tablinks;
    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(section).style.display = "block";
    evt.currentTarget.className += " active";
    var id = $('#receiver_id').attr('value');
    var type = $('#receiver_id').attr('title');
    if(section === 'chat-section') {getAllMessages(id, type);} else {
        if(type === 'group') {
            $('#challenge').addClass('invisible');
            $('#invite_message_sent').addClass('invisible');
            $('#playground').addClass('invisible');
            $('#groupText').removeClass('invisible');
            return;
        }
        manager.getChallenge(id).done(function(res){
            if(res.status){
                if(res.data.challenge === null) util.showChallenge();
                else if(res.data.challenge.status === 'requested') showInviteMessage();
                else  util.showPlayGround();
            }
        });
    }
  },

    showPlayGround: function(){
        $('#playground').removeClass('invisible');
        $('#challenge').addClass('invisible');
        $('#invite_message_sent').addClass('invisible');
        $('#groupText').addClass('invisible');
        //createPlayground();
        game.init();
    },

    showInviteMessage: function(){
        $('#invite_message_sent').removeClass('invisible');
        $('#challenge').addClass('invisible');
        $('#playground').addClass('invisible');
        $('#groupText').addClass('invisible');
    },



    showChallenge: function(){
        $('#challenge').removeClass('invisible');
        $('#invite_message_sent').addClass('invisible');
        $('#playground').addClass('invisible');
        $('#groupText').addClass('invisible');
    },

    clearGameSection: function(){
    $('#challenge').addClass('invisible');
    $('#invite_message_sent').addClass('invisible');
    $('#playground').addClass('invisible');
    $('#groupText').addClass('invisible');
    }
};






