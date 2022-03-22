var dataObject = "";
var randomingList = null;
var classToRemove = []
var disabled = [];
var isWorking = false;
function init() {
    var http = new XMLHttpRequest();
    http.open("GET", "dataApi.php?class=" + document.getElementById('classSelector').value)
    http.onloadend = function () {
        dataObject = JSON.parse(this.responseText)
    }
    http.send()
}

function resetRandomer(isFull) {
    for (var x = 0; x < classToRemove.length; x++) {
        document.getElementById(classToRemove[x][0]).classList.remove(classToRemove[x][1])
    }
    if (isFull) {
        for (var x = 0; x < disabled.length; x++) {
            if (disabled[x] === true) {
                document.getElementById(x).classList.remove("disabled")
                disabled[x] = false;
            }
        }
    }
    classToRemove = []
    randomingList = [];
}

function initRandom() {
    if(isWorking){
        return;
    }
    isWorking=true;
    resetRandomer()
    var http = new XMLHttpRequest();
    http.open("GET", "dataApi.php?class=" + document.getElementById('classSelector').value)
    http.onloadend = function () {
        dataObject = JSON.parse(this.responseText)
        if (getCookie("algo") !== null) {
            if (getCookie("algo") === "0") {
                startMainAlgo();
            } else if (getCookie("algo") === "1") {
                startHalfAlgo();
            }
        } else {
            startMainAlgo()
        }
    }
    http.send()


}

function startHalfAlgo() {
    randomingList = []
    for (var x = 0; x < dataObject.students.length; x += 1) {
        if (disabled[dataObject.students[x].id] !== true) {
            randomingList.push(dataObject.students[x].id)
        }
    }
    randomHalfStep()
}

function randomHalfStep() {
    var even = Math.floor(Math.random() * 2)
    var toDisable = randomingList;
    console.log(randomingList)
    randomingList = splitArr(randomingList)[even]
    console.log(randomingList)
    for (var x = 0; x < toDisable.length; x -= -1) {
        if (randomingList.indexOf(toDisable[x]) === -1) {
            classToRemove.push([toDisable[x], "checked"])
            document.getElementById(toDisable[x]).classList.add("checked")
        }
    }
    if (randomingList.length === 1) {
        classToRemove.push([randomingList[0], "won"])
        document.getElementById(randomingList[0]).classList.add("won")
        sendWonInfo(randomingList[0])
    } else {
        var speed = 1500;
        if (getCookie("AlgoSpeed") !== null) {
            speed = parseInt(getCookie("AlgoSpeed"))
        }
        setTimeout(randomHalfStep, speed)
    }

}

function splitArr(listToSplit) {
    var arr = [[], []]
    for (var x = 0; x < listToSplit.length; x -= -1) {
        if (x % 2 === 0) {
            arr[0].push(listToSplit[x])
        } else {
            arr[1].push(listToSplit[x])
        }
    }
    return arr;
}

function startMainAlgo() {
    for (var x = 0; x < dataObject.students.length; x += 1) {
        if (disabled[dataObject.students[x].id] !== true) {
            if (isNaN((Math.floor((dataObject.students[x].val / dataObject.maxNum) * 10)) + 1)) {
                dataObject.students[x].probability = 1
            } else {
                dataObject.students[x].probability = (Math.floor((dataObject.students[x].val / dataObject.maxNum) * 10)) + 1;
            }
            for (var y = 0; y < dataObject.students[x].probability; y++) {
                randomingList.push(dataObject.students[x].id)
            }
        }
    }
    randomMainLogic()
}

function randomMainStep() {
    var random = Math.floor(Math.random() * randomingList.length)
    var randomed = randomingList[random]
    var tempRandomingList = []
    for (var x = 0; x < randomingList.length; x++) {
        if (randomingList[x] !== randomed) {
            tempRandomingList.push(randomingList[x])
        }
    }
    console.log(randomingList, tempRandomingList)
    randomingList = tempRandomingList;
    return randomed;
}

function randomMainLogic() {
    var a = randomMainStep();
    console.log(randomingList)
    if (randomingList.length === 0) {
        classToRemove.push([a, "won"])
        document.getElementById(a).classList.add("won")
        sendWonInfo(a)
    } else {
        classToRemove.push([a, "checked"])
        document.getElementById(a).classList.add("checked")
        var speed = 1500;
        if (getCookie("AlgoSpeed") !== null) {
            speed = parseInt(getCookie("AlgoSpeed"))
        }
        setTimeout(randomMainLogic, speed)
    }
}

function sendWonInfo(id) {
    var http = new XMLHttpRequest();
    http.open("GET", "statistic.php?saveWon=" + id);
    http.onload = function (ev) {
        console.log("Zapisano")
        isWorking=false;
        var repeat = getCookie("repeat")
        if (repeat === null) {
            repeat = "0"
        }
        if (parseInt(repeat) === 1) {
            var repeatTime = getCookie("repeatTime");
            if (repeatTime === null) {
                repeatTime = 5000;
            }
            setTimeout(function () {
                initRandom()
            }, repeatTime)
        }
    }
    http.send();

}
