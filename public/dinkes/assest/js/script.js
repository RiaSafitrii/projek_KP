const scrollBtn = document.getElementById("scrollTop");
const circle = document.querySelector(".scroll-progress circle");

const radius = 45;
const circumference = 2 * Math.PI * radius;

circle.style.strokeDasharray = circumference;

window.addEventListener("scroll", () => {

    const scrollTop = window.scrollY;
    const docHeight = document.documentElement.scrollHeight - window.innerHeight;
    const progress = scrollTop / docHeight;

    const offset = circumference - progress * circumference;
    circle.style.strokeDashoffset = offset;

    if(scrollTop > 200){
        scrollBtn.classList.add("show");
    }else{
        scrollBtn.classList.remove("show");
    }

});

scrollBtn.addEventListener("click", () => {
    window.scrollTo({
        top:0,
        behavior:"smooth"
    });
});