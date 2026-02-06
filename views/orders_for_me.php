<?php
ob_start();
$title = "Bestellungen für mich";
?>

<h2>Bestellungen für meine Filme</h2>

<div class="table-responsive">
    <?php if (!empty($orders)): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Film</th>
                    <th>Leihnehmer</th>
                    <th>Bestelldatum</th>
                    <th>Status</th>
                    <th>Aktionen</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['movie_title']); ?></td>
                        <td><?php echo htmlspecialchars($order['borrower_name']); ?></td>
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
                        <td>
                            <?php if ($order['status'] === 'pending'): ?>
                                <form method="post" action="controllers/OrderController.php?action=update_status" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="status" value="sent">
                                    <button type="submit" class="btn btn-sm btn-info">Als versendet markieren</button>
                                </form>
                            <?php elseif ($order['status'] === 'sent'): ?>
                                <span class="text-muted">Warten auf Empfangsbestätigung</span>
                            <?php elseif ($order['status'] === 'received'): ?>
                                <form method="post" action="controllers/OrderController.php?action=update_status" class="d-inline">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-sm btn-primary">Als bestätigt markieren</button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">Abgeschlossen</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Es gibt keine Bestellungen für Ihre Filme.</p>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include 'layout.php';
?>