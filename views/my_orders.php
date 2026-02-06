<?php
ob_start();
$title = "Meine Bestellungen";
?>

<h2>Meine Bestellungen</h2>

<div class="table-responsive">
    <?php if (!empty($orders)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Besitzer</th>
                    <th>Bestelldatum</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['movie_title']); ?></td>
                        <td><?php echo htmlspecialchars($order['owner_name']); ?></td>
                        <td><?php echo date('d.m.Y H:i', strtotime($order['created_at'])); ?></td>
                        <td>
                            <span class="badge 
                                <?php 
                                    switch($order['status']) {
                                        case 'pending': echo 'bg-warning'; break;
                                        case 'sent': echo 'bg-info'; break;
                                        case 'received': echo 'bg-success'; break;
                                        case 'confirmed': echo 'bg-primary'; break;
                                        default: echo 'bg-light text-dark'; break;
                                    }
                                ?>">
                                <?php 
                                    switch($order['status']) {
                                        case 'pending': echo 'Ausstehend'; break;
                                        case 'sent': echo 'Versendet'; break;
                                        case 'received': echo 'Empfangen'; break;
                                        case 'confirmed': echo 'Bestätigt'; break;
                                        default: echo ucfirst($order['status']); break;
                                    }
                                ?>
                            </span>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Sie haben keine Bestellungen.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>