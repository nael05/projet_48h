<?php
namespace App\Controllers;

class SearchController {
    public function users(): void {
        $this->requireAuth();

        $activeTab = 'search';
        $activeNav = 'search';
        $mockUrl = 'localhost:3000/search/users';
        $basePath = $this->getBasePath();

        $searchQuery = trim((string) ($_GET['q'] ?? ''));
        $filterFiliere = trim((string) ($_GET['filiere'] ?? ''));
        $filterPromo = trim((string) ($_GET['promo'] ?? ''));
        $currentUserId = (int) $_SESSION['user_id'];
        $results = [];
        $allFieres = [];
        $allPromos = [];

        try {
            $pdo = $this->getPdo();
            $this->ensureUsersAcademicColumns($pdo);

            // Récupérer toutes les filières et promos pour les bouttons de filtrage
            $stmtFiliere = $pdo->query('SELECT DISTINCT formation FROM users WHERE formation IS NOT NULL AND formation != "" ORDER BY formation');
            $allFieres = array_column($stmtFiliere->fetchAll() ?: [], 'formation');

            $stmtPromo = $pdo->query('SELECT DISTINCT promotion FROM users WHERE promotion IS NOT NULL AND promotion != "" ORDER BY promotion');
            $allPromos = array_column($stmtPromo->fetchAll() ?: [], 'promotion');

            // Construire la requête de recherche
            $query = 'SELECT id, nom, prenom, username, formation, promotion, profile_picture FROM users WHERE id != :current_user_id';
            $params = ['current_user_id' => $currentUserId];

            if ($searchQuery !== '') {
                $query .= ' AND (nom LIKE :search OR prenom LIKE :search OR username LIKE :search)';
                $params['search'] = '%' . $searchQuery . '%';
            }

            if ($filterFiliere !== '') {
                $query .= ' AND formation = :filiere';
                $params['filiere'] = $filterFiliere;
            }

            if ($filterPromo !== '') {
                $query .= ' AND promotion = :promo';
                $params['promo'] = $filterPromo;
            }

            $query .= ' ORDER BY prenom, nom LIMIT 50';

            $stmt = $pdo->prepare($query);
            $stmt->execute($params);
            $results = $stmt->fetchAll() ?: [];
        } catch (\PDOException $exception) {
            $results = [];
            $allFieres = [];
            $allPromos = [];
        }

        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/search-users.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }

    private function requireAuth(): void {
        if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
            $basePath = $this->getBasePath();
            header('Location: ' . $basePath . '/login');
            exit;
        }
    }

    private function getBasePath(): string {
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        return ($basePath === '/' || $basePath === '.') ? '' : $basePath;
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
