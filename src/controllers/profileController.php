<?php
namespace App\Controllers;

class ProfileController {
    public function show() {
        if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
            $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $activeTab = 'profile';
        $activeNav = 'profile';
        $mockUrl = 'localhost:3000/profile/1';

        $profileFullName = (string) ($_SESSION['username'] ?? 'Utilisateur');
        $profileFormation = 'Profil Ynov';

        try {
            $pdo = $this->getPdo();
            $stmt = $pdo->prepare('SELECT nom, prenom, formation FROM users WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => (int) $_SESSION['user_id']]);
            $user = $stmt->fetch();

            if ($user) {
                $nom = trim((string) ($user['nom'] ?? ''));
                $prenom = trim((string) ($user['prenom'] ?? ''));
                $formation = trim((string) ($user['formation'] ?? ''));

                $fullName = trim($prenom . ' ' . $nom);
                if ($fullName !== '') {
                    $profileFullName = $fullName;
                }

                if ($formation !== '') {
                    $profileFormation = $formation;
                }
            }
        } catch (\PDOException $exception) {
            // Fallback sur les valeurs de session en cas d'indisponibilite DB.
        }
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/profile.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
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
}