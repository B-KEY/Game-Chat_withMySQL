
var variable = {
    //javascript object
    _receiverID : document.getElementById('receiver_id'),


    //html strings
    _playgroundDiv:' <div id="playground" style="position:relative;border-right: 1px solid #4C516D;">' +
                    '<svg xmlns="http://www.w3.org/2000/svg" version="1.1"  width="600px" height="650px">' +
                    'Sorry! Your browser doesn\'t support SVG.' +
                    '</svg>' +
                    '<div style="position:absolute;top:30px;right:20px;"><input type="button" value="Roll">' +
                    '<div id="dice"></div>' +
                    '<div><ul><li  style="list-style:none; font-size:20px; margin-top: 20px; color:red"><span id="player0Name">Something</span><span  id="player0Score"></span></li>' +
                    '<li  style="list-style:none;font-size:20px; margin-top: 20px; color:green"><span id="player1Name"></span><span id="player1Score"></span></li></ul></div></div>' +
                    '</div>',

    _invitedDiv:    '<div id="invite_message_sent">' +
                    '<h3>Your request has been sent.</h3>' +
                    '<span>Game will begin once the user accept the request</span></div>',

    //jquery object
    _gameSection$ : $('#game-section'),
    _inviteThisUser$ : $('#inviteThisUser'),
    _chatSection$ : $('#chat-section'),
    _userName$ : $('#userName'),
    _charMessages$: $('.chatMessage'),
    //_receiverId$ : $('#receiver_id'),
    _player0Name$: '',
    _player0Score$: '',
    _player1Name$: '',
    _player1Score$: '',


}

