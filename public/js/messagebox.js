
function addMessageBox(type,msg){
    id = "messageBox_"+(new Date()).getTime() + Math.ceil(Math.random()*200);
    var boxMsg = '<div class="messageBox '+((type===0)?"messageBoxGreen":"messageBoxRed")+'" id="'+id+'">\
                <ul class="hl">\
                    <li>'+msg+'</li>\
                    <li class="close" onclick="closeMessageBox(\''+id+'\')">&times;</li>\
                </ul>\
            </div>';

    $("#rightMessageBox").prepend(boxMsg);
    

    var msgid = {boxid:id};
    var f = showMessageBox.bind(msgid);
    setTimeout(f,0);
}
function showMessageBox(){
    $("#"+this.boxid).css("opacity","1");
    var f = hideMessageBox.bind(this);
    setTimeout(f,3000);
}
function hideMessageBox(){
    $("#"+this.boxid).css("opacity:","0");
    f = removeMessageBox.bind(this);
    setTimeout(f,500);
}
function removeMessageBox(){
    $("#"+this.boxid).remove();

}
function closeMessageBox(id){
    var msgid = {boxid:id};
    f = hideMessageBox.bind(msgid);
    setTimeout(f,0);
}

