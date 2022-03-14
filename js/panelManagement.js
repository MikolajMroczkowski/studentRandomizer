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