const starfield = document.getElementById('starfield');
var x = window.matchMedia("(max-width: 672px)")
var numStars=window.innerWidth;

function myFunction(x) {
    if (x.matches){
        return 75; // Number of stars
    }
    else{
        return 250; // Number of stars
    }
}
/*x.addEventListener("change", function() {
    x = window.matchMedia("(max-width: 672px)")
    for (let i = 0; i <myFunction(x); i++) {
        createStar();
    }
   });
   */
function createStar() {
    const star = document.createElement('div');
    star.className = 'star';
    star.style.top = `${Math.random() * 100}vh`;
    star.style.left = `${Math.random() * 100}vw`;
    star.style.animationDuration = `${Math.random() * 3 + 2}s`;
    star.style.width = `${Math.random() * 2 }px`;
    star.style.height = star.style.width;
    
    const colors = ['#ffffff', '#7aa5fa', '#fc835d','#fcb97e'];
    star.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
    star.style.boxShadow = `0 0 6px 2px ${star.style.backgroundColor}`;
    
    starfield.appendChild(star);
}

//Create stars
for (let i = 0; i <myFunction(x); i++) {
    createStar();
}

