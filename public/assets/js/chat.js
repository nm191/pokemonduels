/**
 * Created by Nick van Meel on 16-1-2017.
 */
$(document).ready(function(){
    $(".send").on('click', function(){
        addLine();
    });

    function addLine(){
        var user_id = $('#user_id').val();
        var text = $("#text").val();
        var formname = 'addLine';
        return $.ajax({
            method:'POST',
            url: '../app/controllers/chatController.php',
            data: {user_id: user_id, text: text, formname: formname}
        }).done(function(data){
            console.log(data);
            $("#text").val('');
            loadChatLines('Ja');
        });
    }

    function loadChatLines(returnOwn){
        var user_id = $('#user_id').val();
        var formname = 'getLines';
        return $.ajax({
            method:'POST',
            url: '../app/controllers/chatController.php',
            data: {user_id: user_id, formname: formname, returnOwn: returnOwn}
        }).done(function(data){
            $('.chat-container').append(data);
        });
    }

    setInterval(function(){
        loadChatLines('Nee');
    }, 5000);
});