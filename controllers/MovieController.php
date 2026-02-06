<?php
session_start();

require_once '../../autoload.php';
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: auth.php?action=login');
    exit();
}

class MovieController {
    private $movieModel;
    private $genreModel;

    public function __construct($database) {
        $this->movieModel = new Movie($database);
        $this->genreModel = new Genre($database);
    }

    // Display form to add a movie
    public function addMovieForm() {
        $genres = $this->genreModel->getAllGenres();
        include '../views/add_movie.php';
    }

    // Handle adding a movie
    public function addMovie() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $condition = trim($_POST['condition']);
            $ean = trim($_POST['ean']);
            $genreIds = $_POST['genres'] ?? [];
            $status = 'available';
            
            // Handle image upload
            $imagePath = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $targetDir = "../assets/images/";
                $fileName = basename($_FILES['image']['name']);
                $fileType = pathinfo($fileName, PATHINFO_EXTENSION);
                
                // Generate unique filename
                $uniqueFileName = uniqid() . '_' . $fileName;
                $targetFilePath = $targetDir . $uniqueFileName;
                
                // Allow certain file formats
                $allowTypes = array('jpg','png','jpeg','gif');
                if(in_array($fileType, $allowTypes)){
                    if(move_uploaded_file($_FILES['image']['tmp_name'], $targetFilePath)){
                        $imagePath = 'assets/images/' . $uniqueFileName;
                    } else {
                        $error = "Fehler beim Hochladen des Bildes.";
                        $genres = $this->genreModel->getAllGenres();
                        include '../views/add_movie.php';
                        return;
                    }
                } else {
                    $error = "Nur JPG, JPEG, PNG und GIF Dateien sind erlaubt.";
                    $genres = $this->genreModel->getAllGenres();
                    include '../views/add_movie.php';
                    return;
                }
            }
            
            // Validate required fields
            if (empty($title) || empty($condition) || empty($ean)) {
                $error = "Titel, Zustand und EAN sind Pflichtfelder.";
                $genres = $this->genreModel->getAllGenres();
                include '../views/add_movie.php';
                return;
            }
            
            // Add movie to database
            $userId = $_SESSION['user_id'];
            $result = $this->movieModel->addMovie($userId, $title, $condition, $ean, $genreIds, $imagePath);
            
            if ($result) {
                $success = "Film erfolgreich hinzugefügt!";
                header('Location: ../index.php?page=my_movies');
                exit();
            } else {
                $error = "Fehler beim Hinzufügen des Films.";
                $genres = $this->genreModel->getAllGenres();
                include '../views/add_movie.php';
                return;
            }
        }
    }

    // View user's movies
    public function viewMyMovies() {
        $userId = $_SESSION['user_id'];
        $movies = $this->movieModel->getMoviesByUser($userId);
        include '../views/my_movies.php';
    }

    // View available movies
    public function viewAvailableMovies() {
        $movies = $this->movieModel->getAvailableMovies();
        include '../views/browse_movies.php';
    }

    // View movie details
    public function viewMovieDetails() {
        $movieId = $_GET['id'] ?? 0;
        $movie = $this->movieModel->getMovieById($movieId);
        
        if (!$movie) {
            $error = "Film nicht gefunden.";
            include '../views/error.php';
            return;
        }
        
        include '../views/movie_details.php';
    }
}

$movieController = new MovieController($connection);

$action = $_GET['action'] ?? '';
switch ($action) {
    case 'add_form':
        $movieController->addMovieForm();
        break;
    case 'add':
        $movieController->addMovie();
        break;
    case 'my_movies':
        $movieController->viewMyMovies();
        break;
    case 'browse':
        $movieController->viewAvailableMovies();
        break;
    case 'details':
        $movieController->viewMovieDetails();
        break;
    default:
        $movieController->viewAvailableMovies();
        break;
}
?>