<?php
ob_start();
$title = "Registrieren";
?>

<h2>Registrieren</h2>

<form method="post" action="controllers/AuthController.php?action=register">
    <div class="mb-3">
        <label for="username" class="form-label">Benutzername</label>
        <input type="text" class="form-control" id="username" name="username" required>
    </div>
    
    <div class="mb-3">
        <label for="email" class="form-label">E-Mail</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    
    <div class="mb-3">
        <label for="password" class="form-label">Passwort</label>
        <input type="password" class="form-control" id="password" name="password" required>
    </div>
    
    <div class="mb-3">
        <label for="confirm_password" class="form-label">Passwort bestätigen</label>
        <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
    </div>
    
    <button type="submit" class="btn btn-primary">Registrieren</button>
</form>

<p class="mt-3">
    <a href="index.php?page=login">Bereits registriert? Hier anmelden</a>
</p>

<?php
$content = ob_get_clean();
include 'layout.php';
?>