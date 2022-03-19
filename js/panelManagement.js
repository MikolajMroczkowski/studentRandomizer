function deleteClass(id){
    location=location.href.split('?')[0]+"?id="+id+"&action=REMOVE";
}
function deleteStudent(id){
    location=location.href.split('?')[0]+"?id="+id+"&action=REMOVE&class="+document.getElementById("classSelector").value;
}
function loadClass(val){
    location=location.href.split('?')[0]+"?class="+val;
}
function checkClass(){
    if(location.href.split('?')[1] == null){
        location=location+"?class="+document.getElementById("classSelector").value;
    }
}
function toogleDisable(id) {
    if (disabled[id] !== true) {
        disabled[id] = true;
        document.getElementById(id).classList.add("disabled")
    } else {
        disabled[id] = false;
        document.getElementById(id).classList.remove("disabled")
    }
    console.log(disabled[id])

}