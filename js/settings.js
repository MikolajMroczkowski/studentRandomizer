function init(){
    var speed = getCookie("AlgoSpeed");
    if(speed===null){
        speed = 1500;
    }
    document.getElementById("speedOfAlgoritm").value=speed;
    document.getElementById("currentAlgoritmSpeed").innerText = ((Math.floor(speed/100))/10)+"s"
    var repeatTime = getCookie("repeatTime");
    if(repeatTime===null){
        repeatTime = 5000;
    }
    document.getElementById("repeatTime").value=repeatTime;
    document.getElementById("currentRepeatTime").innerText = ((Math.floor(repeatTime/100))/10)+"s"
    var algo = getCookie("algo");
    if(algo===null){
        algo = 0
    }
    document.getElementById("algo"+algo).checked = "true"
    var repeat = getCookie("repeat")
    if(repeat===null){
        repeat="0"
    }
    document.getElementById("repeat").checked = parseInt(repeat)===1
}
function algo(obj){
    var name = obj.id;
    name = name.replace("algo","");
    setCookie("algo",name,10000);
}
function saveAlgoritmSpeed(obj){
    setCookie("AlgoSpeed",obj.value,10000);
    document.getElementById("currentAlgoritmSpeed").innerText = ((Math.floor(obj.value/100))/10)+"s"
}
function changeRepeatTime(obj){
    setCookie("repeatTime",obj.value,10000);
    document.getElementById("currentRepeatTime").innerText = ((Math.floor(obj.value/100))/10)+"s"
}
function resetAlgoSpeed(){
    setCookie("AlgoSpeed",1500,10000);
    init()
}
function setMainAlgo(){
    setCookie("algo","0",10000);
    init()
}
function repeat(doRepeat){
    if(doRepeat){
        setCookie("repeat","1",10000)
    }
    else{
        setCookie("repeat","0",10000)
    }
    document.getElementById("repeat").checked = doRepeat
}