<?php
namespace App\Controllers;

class ProfileController {
    public function show() {
        $this->requireAuth();

        $this->renderProfileByUserId((int) $_SESSION['user_id']);
    }

    public function showUser(): void {
        $this->requireAuth();

        $userId = (int) ($_GET['id'] ?? 0);
        if ($userId <= 0) {
            $basePath = $this->getBasePath();
            header('Location: ' . $basePath . '/feed');
            exit;
        }

        $this->renderProfileByUserId($userId);
    }

    private function renderProfileByUserId(int $userId): void {
        $basePath = $this->getBasePath();

        $activeTab = 'profile';
        $activeNav = 'profile';
        $mockUrl = 'localhost:3000/user?id=' . $userId;

        $profileFullName = (string) ($_SESSION['username'] ?? 'Utilisateur');
        $profileFormation = 'Profil Ynov';
        $profilePosts = [];
        $profileStats = [
            'posts' => 0,
            'likes' => 0,
            'comments' => 0,
        ];

        try {
            $pdo = $this->getPdo();
            $this->ensurePostsImageColumn($pdo);

            $stmt = $pdo->prepare('SELECT nom, prenom, formation FROM users WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $userId]);
            $user = $stmt->fetch();

            if (!$user) {
                $_SESSION['publish_error'] = 'Utilisateur introuvable.';
                header('Location: ' . $basePath . '/feed');
                exit;
            }

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

            $postsStmt = $pdo->prepare(
                'SELECT p.id, p.content, p.image_path, p.created_at,
                        COALESCE(l.total_likes, 0) AS like_count,
                        COALESCE(c.total_comments, 0) AS comment_count
                 FROM posts p
                 LEFT JOIN (
                    SELECT post_id, COUNT(*) AS total_likes
                    FROM likes
                    GROUP BY post_id
                 ) l ON l.post_id = p.id
                 LEFT JOIN (
                    SELECT post_id, COUNT(*) AS total_comments
                    FROM comments
                    GROUP BY post_id
                 ) c ON c.post_id = p.id
                 WHERE p.user_id = :user_id
                 ORDER BY p.created_at DESC'
            );
            $postsStmt->execute(['user_id' => $userId]);
            $profilePosts = $postsStmt->fetchAll() ?: [];

            $profileStats['posts'] = count($profilePosts);
            foreach ($profilePosts as $profilePost) {
                $profileStats['likes'] += (int) ($profilePost['like_count'] ?? 0);
                $profileStats['comments'] += (int) ($profilePost['comment_count'] ?? 0);
            }
        } catch (\PDOException $exception) {
            // Fallback sur les valeurs de session en cas d'indisponibilite DB.
        }
        
        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/profile.php';
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

    private function ensurePostsImageColumn(\PDO $pdo): void {
        $stmt = $pdo->query("SHOW COLUMNS FROM posts LIKE 'image_path'");
        $exists = $stmt->fetch();

        if (!$exists) {
            $pdo->exec('ALTER TABLE posts ADD COLUMN image_path VARCHAR(255) NULL AFTER content');
        }
    }
}