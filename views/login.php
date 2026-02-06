<?php
ob_start();
$title = "Anmelden";
?>

<h2>Anmelden</h2>

<form method="post" action="controllers/AuthController.php?action=login">
    <div class="mb-3">
        <label for="username" class="form-label">Benutzername oder E-Mail</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Passwort</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Anmelden</button>
</form>

<p class="mt-3">
    <a href="index.php?page=register">Noch kein Konto? Hier registrieren</a>
</p>

<?php
$content = ob_get_clean();
include 'layout.php';
?>