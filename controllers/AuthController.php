<?php
session_start();

require_once '../../autoload.php';
require_once '../config/database.php';

class AuthController {
    private $userModel;

    public function __construct($database) {
        $this->userModel = new User($database);
    }

    // Handle registration
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirm_password'];

            // Validation
            if (empty($username) || empty($email) || empty($password)) {
                $error = "Alle Felder sind Pflichtfelder.";
                include '../views/register.php';
                return;
            }

            if ($password !== $confirmPassword) {
                $error = "Die Passwörter stimmen nicht überein.";
                include '../views/register.php';
                return;
            }

            if (strlen($password) < 6) {
                $error = "Das Passwort muss mindestens 6 Zeichen lang sein.";
                include '../views/register.php';
                return;
            }

            // Check if username or email already exists
            $checkStmt = $GLOBALS['connection']->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $checkResult = $checkStmt->get_result();

            if ($checkResult->num_rows > 0) {
                $error = "Benutzername oder E-Mail bereits vergeben.";
                include '../views/register.php';
                return;
            }

            // Register the user
            if ($this->userModel->register($username, $email, $password)) {
                $success = "Registrierung erfolgreich! Sie können sich nun anmelden.";
                include '../views/login.php';
            } else {
                $error = "Registrierung fehlgeschlagen. Bitte versuchen Sie es erneut.";
                include '../views/register.php';
            }
        } else {
            include '../views/register.php';
        }
    }

    // Handle login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            if (empty($username) || empty($password)) {
                $error = "Benutzername und Passwort sind erforderlich.";
                include '../views/login.php';
                return;
            }

            $user = $this->userModel->login($username, $password);

            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: ../index.php');
                exit();
            } else {
                $error = "Ungültige Anmeldedaten.";
                include '../views/login.php';
            }
        } else {
            include '../views/login.php';
        }
    }

    // Handle logout
    public function logout() {
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
}

// Process the request
$authController = new AuthController($connection);

$action = $_GET['action'] ?? '';
switch ($action) {
    case 'register':
        $authController->register();
        break;
    case 'login':
        $authController->login();
        break;
    case 'logout':
        $authController->logout();
        break;
    default:
        $authController->login();
        break;
}
?>