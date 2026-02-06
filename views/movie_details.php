<?php
ob_start();
$title = "Filmdetails";
?>

<h2><?php echo htmlspecialchars($movie['title']); ?></h2>

<div class="row">
    <div class="col-md-6">
        <?php if (!empty($movie['image_path'])): ?>
            <img src="<?php echo $movie['image_path']; ?>" class="img-fluid" alt="<?php echo htmlspecialchars($movie['title']); ?>">
        <?php else: ?>
            <img src="https://via.placeholder.com/400x600?text=No+Image" class="img-fluid" alt="No Image">
        <?php endif; ?>
    </div>
    
    <div class="col-md-6">
        <h3>Details</h3>
        <table class="table table-borderless">
            <tr>
                <td><strong>Besitzer:</strong></td>
                <td><?php echo htmlspecialchars($movie['owner_name']); ?></td>
            </tr>
            <tr>
                <td><strong>Zustand:</strong></td>
                <td><?php echo htmlspecialchars($movie['condition']); ?></td>
            </tr>
            <tr>
                <td><strong>EAN:</strong></td>
                <td><?php echo htmlspecialchars($movie['ean']); ?></td>
            </tr>
            <tr>
                <td><strong>Genre:</strong></td>
                <td><?php echo htmlspecialchars($movie['genres']); ?></td>
            </tr>
            <tr>
                <td><strong>Status:</strong></td>
                <td>
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
                    </span>
                </td>
            </tr>
        </table>
        
        <?php if ($movie['status'] === 'available' && isset($_SESSION['user_id']) && $_SESSION['user_id'] != $movie['owner_id']): ?>
            <form method="post" action="controllers/OrderController.php?action=create" class="mt-3">
                <input type="hidden" name="movie_id" value="<?php echo $movie['id']; ?>">
                <button type="submit" class="btn btn-success">Diesen Film bestellen</button>
            </form>
        <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $movie['owner_id']): ?>
            <div class="alert alert-info mt-3">Sie sind der Besitzer dieses Films.</div>
        <?php elseif ($movie['status'] !== 'available'): ?>
            <div class="alert alert-warning mt-3">Dieser Film ist momentan nicht verfügbar.</div>
        <?php else: ?>
            <div class="alert alert-info mt-3">Melden Sie sich an, um diesen Film zu bestellen.</div>
        <?php endif; ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>