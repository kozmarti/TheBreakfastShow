let $heart = document.getElementsByClassName("fa-heart")[0];
console.log($heart);

$heart.addEventListener('click', (event)=>    {
    $splitData = event.target.id.split('-');
    console.log($splitData);
    if (event.target.classList.contains('fas')) {
        event.target.classList.remove('fas');
        event.target.classList.add('far');


    }  else {
        event.target.classList.remove('far');
        event.target.classList.add('fas');
    }
    fetch('/favorites?user-id=' + $splitData[0] + '&episode-id=' + $splitData[1])
        .then( res=>res.json())
});