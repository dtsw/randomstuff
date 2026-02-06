<?php
class Movie {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Add a movie to the collection
    public function addMovie($userId, $title, $condition, $ean, $genreIds, $imagePath) {
        $stmt = $this->db->prepare("INSERT INTO movies (owner_id, title, condition, ean, image_path) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $userId, $title, $condition, $ean, $imagePath);
        
        if ($stmt->execute()) {
            $movieId = $this->db->insert_id;
            
            // Insert genre associations
            foreach ($genreIds as $genreId) {
                $stmtGenre = $this->db->prepare("INSERT INTO movie_genres (movie_id, genre_id) VALUES (?, ?)");
                $stmtGenre->bind_param("ii", $movieId, $genreId);
                $stmtGenre->execute();
            }
            
            return $movieId;
        } else {
            return false;
        }
    }

    // Get all movies owned by a user
    public function getMoviesByUser($userId) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as owner_name, 
                   GROUP_CONCAT(g.name) as genres
            FROM movies m
            LEFT JOIN users u ON m.owner_id = u.id
            LEFT JOIN movie_genres mg ON m.id = mg.movie_id
            LEFT JOIN genres g ON mg.genre_id = g.id
            WHERE m.owner_id = ?
            GROUP BY m.id
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get all available movies for borrowing
    public function getAvailableMovies() {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as owner_name, 
                   GROUP_CONCAT(g.name) as genres
            FROM movies m
            LEFT JOIN users u ON m.owner_id = u.id
            LEFT JOIN movie_genres mg ON m.id = mg.movie_id
            LEFT JOIN genres g ON mg.genre_id = g.id
            WHERE m.status = 'available'
            GROUP BY m.id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get movie by ID
    public function getMovieById($id) {
        $stmt = $this->db->prepare("
            SELECT m.*, u.username as owner_name, 
                   GROUP_CONCAT(g.name) as genres
            FROM movies m
            LEFT JOIN users u ON m.owner_id = u.id
            LEFT JOIN movie_genres mg ON m.id = mg.movie_id
            LEFT JOIN genres g ON mg.genre_id = g.id
            WHERE m.id = ?
            GROUP BY m.id
        ");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>