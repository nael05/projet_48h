<?php
namespace App\Controllers;

class AuthController {

    private const FEED_PATH = '/feed';
    private const ALLOWED_FILIERES = [
        'Informatique',
        'Cybersécurité',
        'IA & Data',
        '3D, Animation & Jeux Vidéo',
        'Création & Design',
        'Marketing & Communication',
        'Audiovisuel',
        "Architecture d'Intérieur",
        'Bâtiment Numérique',
        'Digital & IA',
    ];
    private const ALLOWED_CAMPUSES = [
        'Aix-en-Provence',
        'Bordeaux',
        'Casablanca',
        'Lille',
        'Lyon',
        'Montpellier',
        'Nantes',
        'Nice Sophia Antipolis',
        'Paris Est',
        'Paris Ouest',
        'Rennes',
        'Rouen',
        'Strasbourg',
        'Toulouse',
        'Ynov Connect',
    ];
    private const ALLOWED_PROMOTIONS = ['B1', 'B2', 'B3', 'M1', 'M2'];

    private function isAuthenticated(): bool {
        return !empty($_SESSION['user_id']) || !empty($_SESSION['is_authenticated']);
    }

    public function login() {
        if ($this->isAuthenticated()) {
            header('Location: ' . $this->buildUrl(self::FEED_PATH));
            exit;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = (string) ($_POST['password'] ?? '');

            $oldInput = ['email' => $email];

            if ($email === '' || $password === '') {
                $this->renderLogin('Merci de renseigner votre email et votre mot de passe.', $oldInput);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@ynov\\.com$/i', $email)) {
                $this->renderLogin('Seules les adresses email @ynov.com sont autorisees.', $oldInput);
                return;
            }

            try {
                $pdo = $this->getPdo();
                $stmt = $pdo->prepare('SELECT id, username, email, password, profile_picture FROM users WHERE email = :email LIMIT 1');
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch();

                if (!$user) {
                    $this->renderLogin('Aucun compte n\'existe avec cet email.', $oldInput);
                    return;
                }

                if (!password_verify($password, (string) $user['password'])) {
                    $this->renderLogin('Email ou mot de passe incorrect.', $oldInput);
                    return;
                }

                $_SESSION['is_authenticated'] = true;
                $_SESSION['user_id'] = (int) $user['id'];
                $_SESSION['username'] = (string) $user['username'];
                $_SESSION['email'] = (string) $user['email'];
                $_SESSION['profile_picture'] = (string) ($user['profile_picture'] ?? '');
                session_regenerate_id(true);

                header('Location: ' . $this->buildUrl(self::FEED_PATH));
                exit;
            } catch (\PDOException $exception) {
                $this->renderLogin('Connexion indisponible pour le moment. Reessayez dans quelques instants.', $oldInput);
                return;
            }
        }

        $this->renderLogin();
    }

    public function register() {
        if ($this->isAuthenticated()) {
            header('Location: ' . $this->buildUrl(self::FEED_PATH));
            exit;
        }

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $fullName = trim($_POST['name'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $filiere = trim($_POST['filiere'] ?? '');
            $campus = trim($_POST['campus'] ?? '');
            $promotion = trim($_POST['promotion'] ?? '');
            $password = (string) ($_POST['password'] ?? '');

            $oldInput = [
                'name' => $fullName,
                'email' => $email,
                'filiere' => $filiere,
                'campus' => $campus,
                'promotion' => $promotion,
            ];

            if ($fullName === '' || $email === '' || $password === '' || $filiere === '' || $campus === '' || $promotion === '') {
                $this->renderRegister('Merci de remplir tous les champs obligatoires.', $oldInput);
                return;
            }

            if (!in_array($filiere, self::ALLOWED_FILIERES, true)) {
                $this->renderRegister('Filiere invalide. Merci de choisir une filiere proposee.', $oldInput);
                return;
            }

            if (!in_array($campus, self::ALLOWED_CAMPUSES, true)) {
                $this->renderRegister('Campus invalide. Merci de choisir un campus propose.', $oldInput);
                return;
            }

            if (!in_array($promotion, self::ALLOWED_PROMOTIONS, true)) {
                $this->renderRegister('Promotion invalide. Merci de choisir une promotion proposee.', $oldInput);
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !preg_match('/@ynov\\.com$/i', $email)) {
                $this->renderRegister('Seules les adresses email @ynov.com sont autorisees.', $oldInput);
                return;
            }

            if (strlen($password) < 8) {
                $this->renderRegister('Le mot de passe doit contenir au moins 8 caracteres.', $oldInput);
                return;
            }

            try {
                $pdo = $this->getPdo();
                $this->ensureUsersAcademicColumns($pdo);

                $checkStmt = $pdo->prepare('SELECT id FROM users WHERE email = :email LIMIT 1');
                $checkStmt->execute(['email' => $email]);
                if ($checkStmt->fetch()) {
                    $this->renderRegister('Cette adresse email est deja utilisee.', $oldInput);
                    return;
                }

                $nameParts = preg_split('/\\s+/', $fullName);
                $prenom = $nameParts[0] ?? 'Etudiant';
                $nom = count($nameParts) > 1 ? implode(' ', array_slice($nameParts, 1)) : $prenom;

                $username = $this->generateUniqueUsername($pdo, $email);
                $passwordHash = password_hash($password, PASSWORD_BCRYPT);

                $insertStmt = $pdo->prepare(
                    'INSERT INTO users (username, nom, prenom, formation, campus, promotion, email, password)
                     VALUES (:username, :nom, :prenom, :formation, :campus, :promotion, :email, :password)'
                );

                $insertStmt->execute([
                    'username' => $username,
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'formation' => $filiere !== '' ? $filiere : null,
                    'campus' => $campus,
                    'promotion' => $promotion,
                    'email' => $email,
                    'password' => $passwordHash,
                ]);

                $freshUserStmt = $pdo->prepare('SELECT id, username, email, profile_picture FROM users WHERE email = :email LIMIT 1');
                $freshUserStmt->execute(['email' => $email]);
                $freshUser = $freshUserStmt->fetch();

                if (!$freshUser) {
                    $this->renderRegister("Inscription creee mais connexion automatique indisponible. Connectez-vous manuellement.", $oldInput);
                    return;
                }

                $_SESSION['is_authenticated'] = true;
                $_SESSION['user_id'] = (int) $freshUser['id'];
                $_SESSION['username'] = (string) $freshUser['username'];
                $_SESSION['email'] = (string) $freshUser['email'];
                $_SESSION['profile_picture'] = (string) ($freshUser['profile_picture'] ?? '');
                session_regenerate_id(true);
                session_write_close();

                header('Location: ' . $this->buildUrl(self::FEED_PATH));
                exit;
            } catch (\PDOException $exception) {
                $this->renderRegister("Impossible de finaliser l'inscription pour le moment.", $oldInput);
                return;
            }
        }

        $this->renderRegister();
    }

    public function logout(): void {
        // Supprimer les données de session
        $_SESSION['is_authenticated'] = null;
        $_SESSION['user_id'] = null;
        $_SESSION['username'] = null;
        $_SESSION['email'] = null;
        $_SESSION['profile_picture'] = null;
        
        // Détruire la session complètement
        session_destroy();
        
        // Rediriger vers la page de connexion
        header('Location: ' . $this->buildUrl('/login'));
        exit;
    }

    private function renderLogin(?string $errorMessage = null, array $oldInput = []): void {
        $activeTab = 'login';
        $activeNav = 'login';
        $mockUrl = 'localhost:3000/login';

        require __DIR__ . '/../Views/auth/login.php';
    }

    private function renderRegister(?string $errorMessage = null, array $oldInput = []): void {
        $activeTab = 'login';
        $activeNav = 'login';
        $mockUrl = 'localhost:3000/register';

        require __DIR__ . '/../Views/auth/register.php';
    }

    private function getPdo(): \PDO {
        static $pdo = null;

        if ($pdo instanceof \PDO) {
            return $pdo;
        }

        $host = getenv('DB_HOST') ?: '127.0.0.1';
        $port = getenv('DB_PORT') ?: '3306';
        $dbName = getenv('DB_NAME') ?: 'projet_48h';
        $username = getenv('DB_USER') ?: 'root';
        $password = getenv('DB_PASS') ?: '';

        $dsn = "mysql:host={$host};port={$port};dbname={$dbName};charset=utf8mb4";

        $pdo = new \PDO($dsn, $username, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        ]);

        return $pdo;
    }

    private function generateUniqueUsername(\PDO $pdo, string $email): string {
        $base = strtolower((string) strstr($email, '@', true));
        $base = preg_replace('/[^a-z0-9._-]/', '', $base);
        if ($base === '') {
            $base = 'user';
        }

        $candidate = $base;
        $suffix = 1;

        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
        while (true) {
            $stmt->execute(['username' => $candidate]);
            if (!$stmt->fetch()) {
                return $candidate;
            }

            $candidate = $base . $suffix;
            $suffix++;
        }
    }

    private function buildUrl(string $path): string {
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

        return $basePath . $path;
    }

    private function ensureUsersAcademicColumns(\PDO $pdo): void {
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'campus'");
        $campusExists = $stmt->fetch();
        if (!$campusExists) {
            $pdo->exec('ALTER TABLE users ADD COLUMN campus VARCHAR(100) NULL AFTER formation');
        }

        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'promotion'");
        $promotionExists = $stmt->fetch();
        if (!$promotionExists) {
            $pdo->exec('ALTER TABLE users ADD COLUMN promotion VARCHAR(10) NULL AFTER campus');
        }
    }
}