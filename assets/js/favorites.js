let imgs = document.images,
    len = imgs.length,
    counter = 0;

[].forEach.call(imgs, function(img) {
    if (img.complete)
        incrementCounter();
    else
        img.addEventListener('load', incrementCounter, false);
});

function incrementCounter() {
    counter++;
    if (counter === len) {
        let toHide = document.getElementById("spinner-to-hide")
        let toShow = document.getElementById("content-to-show")
        toShow.classList.remove("d-none");
        toShow.classList.add("fade-in-effect");
        toHide.classList.add("d-none");
    }
}

let $heart = document.getElementsByClassName("fa-heart")[0];
console.log($heart);

$heart.addEventListener('click', (event) => {
    $splitData = event.target.id.split('-');
    console.log($splitData);
    if (event.target.classList.contains('fas')) {
        event.target.classList.remove('fas');
        event.target.classList.add('far');


    } else {
        event.target.classList.remove('far');
        event.target.classList.add('fas');
    }
    fetch('/favorites?user-id=' + $splitData[0] + '&episode-id=' + $splitData[1])
        .then(res => res.json())
});