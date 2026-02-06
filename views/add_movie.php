<?php
ob_start();
$title = "Film hinzufügen";
?>

<h2>Film hinzufügen</h2>

<form method="post" action="controllers/MovieController.php?action=add" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="title" class="form-label">Filmtitel</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    
    <div class="mb-3">
        <label for="condition" class="form-label">Zustand</label>
        <select class="form-control" id="condition" name="condition" required>
            <option value="">Bitte wählen</option>
            <option value="Sehr gut">Sehr gut</option>
            <option value="Gut">Gut</option>
            <option value="Akzeptabel">Akzeptabel</option>
            <option value="Mangelhaft">Mangelhaft</option>
        </select>
    </div>
    
    <div class="mb-3">
        <label for="ean" class="form-label">EAN</label>
        <input type="text" class="form-control" id="ean" name="ean" required>
    </div>
    
    <div class="mb-3">
        <label for="genres" class="form-label">Genre(s)</label>
        <select multiple class="form-control" id="genres" name="genres[]">
            <?php foreach ($genres as $genre): ?>
                <option value="<?php echo $genre['id']; ?>"><?php echo htmlspecialchars($genre['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <small class="form-text text-muted">Halten Sie STRG gedrückt, um mehrere Genres auszuwählen</small>
    </div>
    
    <div class="mb-3">
        <label for="image" class="form-label">Bild hochladen</label>
        <input type="file" class="form-control" id="image" name="image" accept="image/*">
    </div>
    
    <button type="submit" class="btn btn-primary">Film hinzufügen</button>
</form>

<?php
$content = ob_get_clean();
include 'layout.php';
?>