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


$(document).ready(function() {
    $('#contact-form').on('submit', function(e) {
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: $(this).serialize(),
            success: function(response) {
                $('#modalTitle').text('Message envoyé !');
                $('#modalBody').text('Nous vous remercions de votre intérêt, vous recevrez une réponse de notre équipe prochainement.');
                
                const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
                responseModal.show();
            },
            error: function() {
                $('#modalTitle').text('Echec de l\'envoi');
                $('#modalBody').text('Nous sommes désolés, une erreur est survenue lors de l\'envoi du message.');

                const responseModal = new bootstrap.Modal(document.getElementById('responseModal'));
                responseModal.show();
            }
        });
    });
});


function onSubmit(token) {
    document.getElementById("contact-form").submit();
}

document.addEventListener('DOMContentLoaded', function() {
    const replyButtons = document.querySelectorAll('.reply-button');

    replyButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const messageId = this.dataset.messageId;
            const email = this.dataset.email;
            const subject = encodeURIComponent(this.dataset.subject);
            const body = encodeURIComponent(this.dataset.body);

            fetch('/admin/archiveMessage.php', {
                method: 'POST',
                body: JSON.stringify({ message_id: messageId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.href = `mailto:${email}?subject=${subject}&body=${body}`;
                    window.location.reload();
                } else {
                    alert('Erreur lors de l\'archivage du message.');
                }
            });
        });
    });
});
