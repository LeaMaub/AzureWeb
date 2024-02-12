document.addEventListener('DOMContentLoaded', function() {
    const menuToggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.nav');
    const navLinks = nav.querySelectorAll('.nav-item .nav-link');
    const navItems = document.querySelectorAll('.nav-item a');
    const sections = document.querySelectorAll('section');

    function toggleNavDisplay() {
        if (nav.classList.contains('nav-closed')) {
            nav.classList.remove('nav-closed');
            nav.classList.add('nav-open');
            nav.style.display = 'block';
        } else {
            nav.classList.remove('nav-open');
            nav.classList.add('nav-closed');
            nav.style.display = 'none';
        }
    }

    menuToggle.addEventListener('click', function() {
        toggleNavDisplay();
    });

    navLinks.forEach(function(link) {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                toggleNavDisplay();
            }
        });
    });

    function activateLink(sectionId) {
        navItems.forEach(link => {
            if (link.getAttribute('href') === '#' + sectionId) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    }

    navItems.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href').substring(1);
            const section = document.getElementById(sectionId);
            window.scrollTo({ top: section.offsetTop, behavior: 'smooth' });
            activateLink(sectionId);
        });
    });

    window.addEventListener('scroll', function() {
        let currentSection = '';
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= (sectionTop - sectionHeight / 3)) {
                currentSection = section.getAttribute('id');
            }
        });
        activateLink(currentSection);
    });
});
