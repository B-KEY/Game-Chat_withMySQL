

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
    if(section === 'chat-section') {messages.getAllMessages(id, type);} else {
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
    },

    setTransform:function(id,x,y){
        document.getElementById(id).setAttributeNS(null,'transform','translate('+x+','+y+')');
    },
    maximizeGameWindow: function(){
        $('#chat-section').removeClass('col-md-12');
        $('#game-section').addClass('col-md-12');
        $('#chat-section').addClass('invisible');
    },
    maximizeChatWindow: function(){
        $('#game-section').removeClass('col-md-12');
        $('#chat-section').addClass('col-md-12');
        $('#game-section').addClass('invisible');
    },

    warning:function(){
        console.log('This is an error');
        if(document.getElementById('error').getAttributeNS(null,'display') === 'none'){
            document.getElementById('error').setAttributeNS(null,'display','inline');
            setTimeout(util.warning,2000);
        }else{
            document.getElementById('error').setAttributeNS(null,'display','none');
        }
    },

    allWarning: function(someText) {
        if(variable._error$.css('display') === 'none'){
            variable._error$.css('display', 'inline');
            variable._error$.text(someText);
            setTimeout(util.allWarning,2000);
        }else{
            variable._error$.css('display','none');
        }
    }

};






