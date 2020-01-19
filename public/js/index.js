function e(msg) {
    console.log(msg)
}

function changeAddr(e) {
    window.history.pushState("", "", e);

}

