// Set --vh to the initial viewport height
function setInitialVh() {
    let vh = window.innerHeight;
    let nh = document.getElementsByTagName("header")[0].offsetHeight;
    document.documentElement.style.setProperty('--hero-section-height', `${vh - nh}px`);
}

function isTouchEnabled() {
    return ('ontouchstart' in window || navigator.maxTouchPoints > 0 || navigator.msMaxTouchPoints > 0);
}

// Run once on load
window.addEventListener('load', function () {
    setInitialVh();

    let calendar_cards = document.getElementsByClassName("calendar-section")[0].getElementsByClassName("card");

    for (let i = 0; i < calendar_cards.length; i++) {
        calendar_cards[i].classList.add("card-hover");
    }

    checkForTouch();

    // Create a media query list for portrait orientation
    const portraitMediaQuery = window.matchMedia("(orientation: portrait)");

    // Add an event listener to detect changes
    portraitMediaQuery.addEventListener("change", (e) => {
        if (e.matches) {
        // The device is now in portrait mode
        console.log("Orientation changed to Portrait");
        // Perform actions specific to portrait mode
        } else {
        // The device is now in landscape mode
        console.log("Orientation changed to Landscape");
        // Perform actions specific to landscape mode
        }
        setInitialVh();
    });

    // You can also check the initial orientation
    /*if (portraitMediaQuery.matches) {
        console.log("Initial orientation is Portrait");
    } else {
        console.log("Initial orientation is Landscape");
    }*/

    function checkForTouch() {
        if (isTouchEnabled()) {
            console.log("This device supports touch events.");
            
            for (let i = 0; i < calendar_cards.length; i++) {
                calendar_cards[i].classList.remove("card-hover");
                calendar_cards[i].addEventListener('click', function () {
                    calendar_cards[i].classList.toggle("card-click");
                })
            }
        } else {
            console.log("This device does not support touch events.");
            for (let i = 0; i < calendar_cards.length; i++) {
                calendar_cards[i].classList.add("card-hover");
            }
        }
    }
});
