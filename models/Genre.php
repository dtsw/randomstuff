<?php
class Genre {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    // Get all genres
    public function getAllGenres() {
        $stmt = $this->db->prepare("SELECT * FROM genres ORDER BY name");
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get genre by ID
    public function getGenreById($id) {
        $stmt = $this->db->prepare("SELECT * FROM genres WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
}
?>