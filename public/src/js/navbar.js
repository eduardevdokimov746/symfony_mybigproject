var carouselPositions;
var currentItem = 0;

function getCarouselPositions() {
    carouselPositions = [];
    document.querySelectorAll('#navbar .navbar-item').forEach(function (div) {
        carouselPositions.push([div.offsetLeft, div.offsetLeft + div.offsetWidth]);
    })
}

getCarouselPositions();

function goCarousel(direction) {
    var currentScrollLeft = document.querySelector('#navbar').scrollLeft;
    var currentScrollLeftWithVisible = currentScrollLeft + document.querySelector('#navbar').offsetWidth;
    var currentScrollRight = carouselPositions[carouselPositions.length - 1][1] - currentScrollLeftWithVisible;

    if (direction === 'next' && currentScrollRight > 0 && carouselPositions.length - 1 > currentItem) {
        currentItem++;
    } else if (direction === 'previous' && currentItem > 0) {
        currentItem--
    }

    document.getElementById('navbar').scrollTo({
        left: carouselPositions[currentItem][0],
        behavior: 'smooth'
    });
}

window.addEventListener('resize', getCarouselPositions);