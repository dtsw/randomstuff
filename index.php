<?php
session_start();

// Include database config
require_once 'config/database.php';

// Determine the page to load based on the 'page' parameter
$page = $_GET['page'] ?? 'home';

// Define allowed pages to prevent unauthorized access
$allowedPages = [
    'home', 'register', 'login', 'add_movie', 'my_movies', 
    'browse_movies', 'movie_details', 'my_orders', 'orders_for_me'
];

// If the requested page is not allowed, default to home
if (!in_array($page, $allowedPages)) {
    $page = 'home';
}

// Load the appropriate controller or view based on the page
switch ($page) {
    case 'register':
        include 'views/register.php';
        break;
    case 'login':
        include 'views/login.php';
        break;
    case 'add_movie':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/MovieController.php';
        break;
    case 'my_movies':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/MovieController.php';
        break;
    case 'browse_movies':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/MovieController.php';
        break;
    case 'movie_details':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/MovieController.php';
        break;
    case 'my_orders':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/OrderController.php';
        break;
    case 'orders_for_me':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include 'controllers/OrderController.php';
        break;
    case 'home':
    default:
        ob_start();
        $title = "Startseite";
        ?>
        
        <div class="jumbotron">
            <h1 class="display-4">Willkommen bei FilmTausch!</h1>
            <p class="lead">Tauschen Sie Ihre DVDs und Blu-Rays mit anderen Mitgliedern.</p>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p>Melden Sie sich an oder registrieren Sie sich, um Filme zu tauschen.</p>
                <a class="btn btn-primary btn-lg" href="index.php?page=register" role="button">Jetzt registrieren</a>
            <?php else: ?>
                <p>Entdecken Sie Filme, die andere Mitglieder zum Tausch anbieten.</p>
                <a class="btn btn-primary btn-lg" href="index.php?page=browse_movies" role="button">Filme durchsuchen</a>
            <?php endif; ?>
        </div>
        
        <?php
        $content = ob_get_clean();
        include 'views/layout.php';
        break;
}
?>