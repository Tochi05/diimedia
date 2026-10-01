const navbar = document.getElementById('navbar');
const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');
const navClose = document.getElementById('navClose');

// Navbar scroll & logo switch effect 
window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

// Mobile menu toggle 
if (hamburger && navLinks) {
    hamburger.addEventListener('click', (e) => {
        e.preventDefault(); // Prevents page jump for href="#"
        navLinks.classList.add('active');
    });
}

// Mobile menu close button
if (navClose && navLinks) {
    navClose.addEventListener('click', () => {
        navLinks.classList.remove('active');
    });
}





// Hero Video button 
const openVideoBtn = document.getElementById('openVideoBtn');
const closeVideoBtn = document.getElementById('closeVideoBtn');
const videoModal = document.getElementById('videoModal');
const storyVideo = document.getElementById('storyVideo');

if (openVideoBtn && closeVideoBtn && videoModal && storyVideo) {

    openVideoBtn.addEventListener('click', () => {
        videoModal.classList.add('active');
        storyVideo.play();
    });

    closeVideoBtn.addEventListener('click', closeModal);

    videoModal.addEventListener('click', (e) => {
        if (e.target === videoModal) closeModal();
    });

    function closeModal() {
        videoModal.classList.remove('active');
        storyVideo.pause();
        storyVideo.currentTime = 0;
    }
}



// text slide starts here
const slides = document.querySelectorAll('.slide');
let currentSlide = 0;

function showSlide(newIndex) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active', 'prev');
        if (i === currentSlide) slide.classList.add('prev');
    });
    slides[newIndex].classList.add('active');
    currentSlide = newIndex;
}

setInterval(() => {
    const nextSlide = (currentSlide + 1) % slides.length;
    showSlide(nextSlide);
}, 6000);
// Ends here




// Slideshows
document.addEventListener('DOMContentLoaded', () => {

    const revealEls = document.querySelectorAll('[data-reveal]');

    revealEls.forEach(el => {
        const delay = el.dataset.delay || 0;
        el.style.setProperty('--reveal-delay', `${delay}s`);
    });

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15
    });

    revealEls.forEach(el => {
        revealObserver.observe(el);

        // 👇 handle already visible elements
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight && rect.bottom > 0) {
            el.classList.add('is-visible');
        }
    });

});




// cursor effect
const dot = document.querySelector('.cursor-dot');
let mouseX = 0, mouseY = 0;
let dotX = 0, dotY = 0;

document.addEventListener('mousemove', (e) => {
  mouseX = e.clientX;
  mouseY = e.clientY;
});

function animateDot() {
  // Easing — dot moves a fraction of the distance each frame
  dotX += (mouseX - dotX) * 0.15;
  dotY += (mouseY - dotY) * 0.15;

  dot.style.left = `${dotX}px`;
  dot.style.top = `${dotY}px`;

  requestAnimationFrame(animateDot);
}

animateDot();