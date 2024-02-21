<?php
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => $_SERVER['HTTP_HOST'],
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
    require_once __DIR__ . '/../classes/Database.php';
    require_once __DIR__ . '/../classes/Header.php';
    require_once __DIR__ . '/../vendor/autoload.php';
    require_once __DIR__ . '/../classes/Footer.php';
    require_once __DIR__ . '/../classes/SocialNetworkButtons.php';
    require_once __DIR__ . '/../classes/Projects.php';
    require_once __DIR__ . '/../classes/ReviewManager.php';

    use AzurWeb\Project;
    use AzurWeb\Portfolio;
    use AzurWeb\Database;
    use AzurWeb\Header;
    use AzurWeb\Footer;
    use AzurWeb\SocialNetworkButtons;
    use AzurWeb\ReviewManager;

    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
    $dotenv->load();

    $db = new Database($_ENV['DB_HOST'], $_ENV['DB_NAME'], $_ENV['DB_USER'], $_ENV['DB_PASS']);
    $pdo = $db->getConnection();

    $header = new Header();
    $footer = new Footer();
    $socialNetworkButtons = new SocialNetworkButtons();
    $socialNetworkButtons->addLink('https://www.facebook.com/team.azureweb', 'fa-brands fa-facebook fa-2xl', 'Facebook');
    $socialNetworkButtons->addLink('https://www.linkedin.com/in/léa-maubert-45633614a', 'fa-brands fa-linkedin fa-2xl', 'LinkedIn');
    $socialNetworkButtons->addLink('https://www.instagram.com/azure_web/', 'fa-brands fa-instagram fa-2xl', 'Instagram');
    $socialNetworkButtons->addLink('https://github.com/LeaMaub', 'fa-brands fa-github fa-2xl', 'GitHub');

    $projectData = $db->getProjects();

    $portfolio = new Portfolio();
    foreach ($projectData as $data) {
        $portfolio->addProject(new Project($data['url'], $data['image'], $data['altText'], $data['title']));
    }

    $reviewManager = new ReviewManager($pdo);
    $reviews = $reviewManager->getReviews();

    $header->render();
?>
    <section class="accueil" id="accueil">
        <div class="container__accueil">
            <div class="accueil__presentation">
                <h1 class="accueil__title">AzureWeb</h1>
                <h2 class="accueil__subtitle">Création de sites vitrine</h2>
                <p class="accueil__description">Nous sommes là pour vous aider à façonner votre présence en ligne. Que ce soit pour votre site web ou votre application mobile, nous 
                    mettons notre expertise à votre service pour créer des solutions qui répondent parfaitement à vos besoins. Avec notre savoir-faire en développement web et mobile, 
                    nous nous engageons à vous fournir des solutions efficaces. Ensemble, bâtissons l'avenir de votre entreprise sur le web.</p>
                <?php $socialNetworkButtons->render();?>
            </div>
            <div class="accueil__background"></div>
        </div>
        <div class="portfolio__container">
            <span class="portfolio__icon fa-solid fa-briefcase fa-2xl"></span>
            <h3 class="portfolio__title">Portfolio</h3>
            <?= $portfolio->render(); ?>
        </div>
    </section>

    <section class="services" id="services">
        <div class="services__background">
            <h3 class="services__title">Services</h3>
        </div>
        <div class="services__grid">
            <div class="service-card">
                <img src="/public/images/palette.jpg" alt="Logo palette" class="service__logo">
                <h4 class="service__subtitle">Un Style Frais et Actuel</h4>
                <p class="service__description">Apportons un souffle de modernité à votre présence en ligne. 
                    Des designs contemporains qui évoluent avec votre clientèle et les tendances du moment.</p>
            </div>
            <div class="service-card">
                <img src="/public/images/smartphone.webp" alt="Logo smartphone" class="service__logo">
                <h4 class="service__subtitle">Boostez Votre Visibilité</h4>
                <p class="service__description">Assurez-vous que votre entreprise soit visible là où vos clients 
                    vous cherchent. Nous mettons en place des techniques pour que vous soyez facilement trouvé et apprécié sur Internet.</p>
            </div>
            <div class="service-card">
                <img src="/public/images/tapis_rouge.webp" alt="Logo tapis rouge" class="service__logo">
                <h4 class="service__subtitle">Votre Entreprise en Vedette</h4>
                <p class="service__description">Offrons à votre entreprise une présence en ligne unique qui 
                    reflète parfaitement votre identité. Des sites web personnalisés sur mesure, conçus pour attirer et captiver vos clients.</p>
            </div>
            <div class="service-card">
                <img src="/public/images/carnet.webp" alt="Logo carnet de note" class="service__logo">
                <h4 class="service__subtitle">À l'Écoute de Votre Projet</h4>
                <p class="service__description">Nous sommes attentifs à votre projet, prêts à le comprendre et à le concrétiser selon vos besoins 
                    et vos aspirations.</p>
            </div>
        </div>
    </section>
    <section class="tarifs" id="tarifs">
        <div class="background__tarifs">
            <div class="tarifs__container">
                <div class="tarif">
                    <h2>Site vitrine WordPress</h2>
                    <p class="from">à partir de</p>
                    <p class="price">1 500€</p>
                    <ul>
                        <li>Création sur la plateforme WordPress : <span class="lighter">outil de gestion de contenu</span></li>
                        <li class="blue">Conception de la charte graphique basée sur un modèle préétabli</li>
                        <li>Choix d'une palette de couleurs spécifique</li>
                        <li class="blue">Inclusion du Domaine et Hébergement</li>
                        <li>Site Responsive : <span class="lighter">adaptation à tous les supports</span></li>
                        <li class="blue">Intégration du Contenu : <span class="lighter">textes et images</span></li>
                        <li>Formulaire de contact</li>
                        <li class="blue">Liens de Partage sur Réseaux Sociaux</li>
                        <li>Optimisation du référencement</li>
                        <li class="blue">Statistiques de Visite</li>
                        <li>Mise en Ligne du Site</li>
                        <li class="blue">Formation à l'Utilisation du Site</li>
                    </ul>
                    <div class="btn__container">
                        <a href="mailto:azweb.connect@outlook.fr" class="btn">Demander un devis</a>
                    </div>
                </div>
                <div class="tarif">
                    <h2>Site vitrine personnalisé</h2>
                    <p class="from">à partir de</p>
                    <p class="price">2 500€</p>
                    <ul>
                        <li>Développement <span class="uppercase">sur-mesure</span> : <span class="lighter">Codage manuel et personnalisé</span></li>
                        <li class="blue">Conception de Charte Graphique Personnalisée</li>
                        <li>Proposition de Logo</li>
                        <li class="blue">Inclusion du Domaine et Hébergement</li>
                        <li>Site Responsive : <span class="lighter">adaptation à tous les supports</span></li>
                        <li class="blue">Intégration du Contenu : <span class="lighter">textes et images</span></li>
                        <li>Formulaire de Contact et Carte Intégrée</li>
                        <li class="blue">Liens de Partage sur Réseaux Sociaux</li>
                        <li>Optimisation du Référencement et des Performances</li>
                        <li class="blue">Statistiques de Visite</li>
                        <li>Mise en Ligne du Site</li>
                        <li class="blue">Formation à l'Utilisation du Site</li>
                    </ul>
                    <div class="btn__container">
                        <a href="mailto:azweb.connect@outlook.fr" class="btn">Demander un devis</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="testimonials" id="avis">
        <div class="testimonials__background">
            <h3 class="testimonials__title">Mes clients témoignent</h3>
        </div>
        <div class="testimonials__container">
            <?php foreach ($reviews as $review) { ?>
                <div class="testimonial">
                <h4><span class="blue"><?= htmlspecialchars(substr($review['title'], 0, 1)) ?></span><?= htmlspecialchars(substr($review['title'], 1)) ?></h4>
                <div class="stars"><?= str_repeat("★", $review['note']) . str_repeat('<span class="empty-star">★</span>', 5 - $review['note']) ?></div>
                <blockquote> <?= htmlspecialchars($review['reviewText']) ?></blockquote>
                <div class="customer">
                    <img src="/public/images/<?= htmlspecialchars($review['profilPicture']) ?>" alt="Photo de profil client" class="profil-picture">
                    <cite class="customer-name"><?= htmlspecialchars($review['customerName']) ?></cite>
                </div>
            </div>
            <?php } ?>
            <div class="zero__testimonials">
                <h4><span class="blue">V</span>otre Avis Compte</h4>
                <blockquote>
                    Cher visiteur,</br></br>

                    Bienvenue sur notre page de témoignages. En tant que nouvelle entreprise passionnée par la création de sites web, votre satisfaction est notre priorité absolue.</br></br>

                    Vos retours sont essentiels et nous sommes impatients de les recueillir pour façonner un service qui vous ressemble. Nous nous engageons à fournir des solutions web de haute qualité et à construire avec vous une relation de confiance et de proximité.</br></br>

                    Votre expérience avec nous mérite d'être partagée et c'est ici que vos histoires prendront vie. Invitez-nous dans votre projet, et nous ferons de cet espace le reflet de notre collaboration réussie.</br></br>

                    Merci de nous accompagner au début de cette aventure numérique.
                </blockquote>
                <img src="/public/images/photo_profil.jpg" alt="Photo de profil" class="profil-picture">
                <cite>Maubert Léa</cite>
                <p>Fondatrice de <span class="blue">AzureWeb</span></p>
            </div>
        </div>
        <!-- <div class="congratulation">
            <p>Je remercie infiniment tous mes clients pour leur confiance.</p>
        </div> -->
    </section>
    
    <?php $footer->render(); ?>

