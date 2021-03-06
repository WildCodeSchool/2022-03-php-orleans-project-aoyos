const typeWriting =  document.getElementById('typeWriting');
const words = typeWriting.dataset.words.split(',');
let i = 0;
let timer;

function typingEffect() {
    let word = words[i].split("");
    var loopTyping = function() {
        if (word.length > 0) {
            document.getElementById('typeWriting').innerHTML += word.shift();
        } else {
            timer = setTimeout(deletingEffect, 1000);
            return false;
        }
        timer = setTimeout(loopTyping, 90);
    };
    loopTyping();
}

function deletingEffect() {
    let word = words[i].split("");
    var loopDeleting = function() {
        if (word.length > 0) {
            word.pop();
            document.getElementById('typeWriting').innerHTML = word.join("");
        } else {
            if (words.length > (i + 1)) {
                i++;
            } else {
                i = 0;
            }
            typingEffect();
            return false;
        }
        timer = setTimeout(loopDeleting, 150);
    };
    loopDeleting();
}

typingEffect();