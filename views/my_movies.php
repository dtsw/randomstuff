<?php
ob_start();
$title = "Meine Filme";
?>

<h2>Meine Filme</h2>

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
                            <strong>Zustand:</strong> <?php echo htmlspecialchars($movie['condition']); ?><br>
                            <strong>EAN:</strong> <?php echo htmlspecialchars($movie['ean']); ?><br>
                            <strong>Status:</strong> 
                            <span class="badge 
                                <?php 
                                    switch($movie['status']) {
                                        case 'available': echo 'bg-success'; break;
                                        case 'ordered': echo 'bg-warning'; break;
                                        case 'sent': echo 'bg-info'; break;
                                        case 'received': echo 'bg-secondary'; break;
                                        default: echo 'bg-light text-dark'; break;
                                    }
                                ?>">
                                <?php 
                                    switch($movie['status']) {
                                        case 'available': echo 'Verfügbar'; break;
                                        case 'ordered': echo 'Bestellt'; break;
                                        case 'sent': echo 'Versendet'; break;
                                        case 'received': echo 'Empfangen'; break;
                                        default: echo ucfirst($movie['status']); break;
                                    }
                                ?>
                            </span><br>
                            <strong>Genre:</strong> <?php echo htmlspecialchars($movie['genres']); ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <p>Sie haben noch keine Filme hinzugefügt.</p>
            <a href="index.php?page=add_movie" class="btn btn-primary">Film hinzufügen</a>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>