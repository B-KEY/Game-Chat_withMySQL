
function invite(e){
    var opponent = $('#opponent_id').attr('value');
    console.log(opponent);
    var url = './game/invite/'+ opponent;
    myXHR('POST',url,'','').done(function(response){
        setGame(response);
        console.log(response);
    });
}

function setGame(res){

    if(res.status)
    {
            console.log(res.data.message);
            $('#invite_message').removeClass('invisible');
            $('#invite_message').prev().remove();

       console.log(res.data);
    }
    else{
            console.log(res.data);
    }

}

function showChallenges(e){
    $('#showChallengers').toggleClass('invisible');
}
function acceptChallenge(e){
    var id = $(e).attr('id');
    var url = './game/accept/' + id;
    myXHR('POST',url,'','').done(function(response){
        if(response){
            $('#gameBox').addClass('invisible');
            $('#playground').removeClass('invisible');
            createPlaygroung();
        }
    });

}
function makeElements(tag, attrs, isSVG) {
    var elem;
    (isSVG) ? elem=  document.createElementNS('http://www.w3.org/2000/svg', tag)
        : elem = document.createElement(tag);

    for (var k in attrs) {
        elem.setAttribute(k, attrs[k]);
    }
    return elem;
}


function createPlaygroung() {
    for (x = 0, i = 0; x < 800; x += 100) {
        for (y = 0; y < 800; y += 100) {
            var square = makeElements('rect', {
                x: x,
                y: y,
                width: 100,
                height: 100,
                fill: (i++ % 2 === 0) ? 'white' : 'black'
            }, true);
            $('#gameBoard').append(square);

            square.onmousedown = function () {
                console.log($(this).attr('fill'));
            }
        }
        i++;
    }
}

//
//
// function myXHR(methodName,url,data,id){
//     $.ajax({
//         url: url,
//         headers: {'X-CSRF-TOKEN': $('input[name=_token]').val()},
//         data: data,
//         type: methodName,
//         datatype: 'JSON',
//         success: function(response){
//             setGame(response);
//             console.log(response);
//         },
//         beforeSend:function(){
//             //turn on spinner if needed
//             if(id){
//                 $(id).append('<img src="path/spinner.jpg" class="spinner"/>');
//             }
//         }
//     }).always(function(){
//         //clean up, kill spinner
//         if(id){
//             $(id).find('.spinner').fadeOut(2000,function(){
//                 $(this).remove();
//             });
//         }
//     }).fail(function(err){
//         //put message out
//         console.log(err);
//     });
// }