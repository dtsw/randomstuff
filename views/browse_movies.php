<?php
ob_start();
$title = "Filme durchsuchen";
?>

<h2>Verfügbare Filme</h2>

<div class="row">
    <?php if (!empty($movies)): ?>
        <?php foreach ($movies as $movie): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <?php if (!empty($movie['image_path'])): ?>
                        <img src="<?php echo $movie['image_path']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <?php else: ?>
                        <img src="https://via.placeholder.com/300x400?text=No+Image" class="card-img-top" alt="No Image">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($movie['title']); ?></h5>
                        <p class="card-text">
                            <strong>Besitzer:</strong> <?php echo htmlspecialchars($movie['owner_name']); ?><br>
                            <strong>Zustand:</strong> <?php echo htmlspecialchars($movie['condition']); ?><br>
                            <strong>EAN:</strong> <?php echo htmlspecialchars($movie['ean']); ?><br>
                            <strong>Genre:</strong> <?php echo htmlspecialchars($movie['genres']); ?>
                        </p>
                        <a href="index.php?page=movie_details&id=<?php echo $movie['id']; ?>" class="btn btn-primary">Details ansehen</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p>Keine verfügbaren Filme zum Ausleihen.</p>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>