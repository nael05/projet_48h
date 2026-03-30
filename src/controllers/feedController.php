<?php
namespace App\Controllers;

class FeedController {
    public function index() {
        if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
            $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

            header('Location: ' . $basePath . '/login');
            exit;
        }

        $activeTab = 'feed';
        $activeNav = 'feed';
        $mockUrl = 'localhost:3000/feed';
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
        $basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

        $publishError = $_SESSION['publish_error'] ?? null;
        $oldPostContent = $_SESSION['old_post_content'] ?? '';
        unset($_SESSION['publish_error'], $_SESSION['old_post_content']);

        $posts = [];
        try {
            $pdo = $this->getPdo();
            $stmt = $pdo->query(
                'SELECT p.id, p.content, p.created_at, u.username, u.prenom, u.nom
                 FROM posts p
                 INNER JOIN users u ON u.id = p.user_id
                 ORDER BY p.created_at DESC'
            );
            $posts = $stmt->fetchAll() ?: [];
        } catch (\PDOException $exception) {
            $posts = [];
            if ($publishError === null) {
                $publishError = 'Le fil est indisponible pour le moment.';
            }
        }
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/feed.php';
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