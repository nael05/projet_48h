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
        $likesCountByPost = [];
        $likedPostIds = [];
        $commentsByPost = [];
        $suggestedUsers = [];
        $shareRecipients = [];
        try {
            $pdo = $this->getPdo();
            $this->ensurePostsImageColumn($pdo);
            $stmt = $pdo->query(
                'SELECT p.id, p.user_id, p.content, p.image_path, p.created_at, u.username, u.prenom, u.nom, u.profile_picture
                 FROM posts p
                 INNER JOIN users u ON u.id = p.user_id
                 ORDER BY p.created_at DESC'
            );
            $posts = $stmt->fetchAll() ?: [];

            if (!empty($posts)) {
                $postIds = array_map(static fn(array $row): int => (int) $row['id'], $posts);
                $placeholders = implode(',', array_fill(0, count($postIds), '?'));

                $likesStmt = $pdo->prepare(
                    "SELECT post_id, COUNT(*) AS total_likes
                     FROM likes
                     WHERE post_id IN ($placeholders)
                     GROUP BY post_id"
                );
                $likesStmt->execute($postIds);
                foreach ($likesStmt->fetchAll() ?: [] as $likeRow) {
                    $likesCountByPost[(int) $likeRow['post_id']] = (int) $likeRow['total_likes'];
                }

                $likedStmt = $pdo->prepare(
                    "SELECT post_id
                     FROM likes
                     WHERE user_id = ? AND post_id IN ($placeholders)"
                );
                $likedStmt->execute(array_merge([(int) $_SESSION['user_id']], $postIds));
                foreach ($likedStmt->fetchAll() ?: [] as $likedRow) {
                    $likedPostIds[(int) $likedRow['post_id']] = true;
                }

                $commentsStmt = $pdo->prepare(
                    "SELECT c.id, c.post_id, c.user_id, c.content, c.created_at, u.username, u.prenom, u.nom
                     FROM comments c
                     INNER JOIN users u ON u.id = c.user_id
                     WHERE c.post_id IN ($placeholders)
                     ORDER BY c.created_at ASC"
                );
                $commentsStmt->execute($postIds);
                foreach ($commentsStmt->fetchAll() ?: [] as $commentRow) {
                    $postId = (int) $commentRow['post_id'];
                    if (!isset($commentsByPost[$postId])) {
                        $commentsByPost[$postId] = [];
                    }

                    $commentsByPost[$postId][] = $commentRow;
                }
            }

            $suggestedStmt = $pdo->prepare(
                'SELECT id, username, prenom, nom, profile_picture
                 FROM users
                 WHERE id <> :current_user_id
                 ORDER BY id DESC
                 LIMIT 3'
            );
            $suggestedStmt->execute(['current_user_id' => (int) $_SESSION['user_id']]);
            $suggestedUsers = $suggestedStmt->fetchAll() ?: [];

            $shareRecipientsStmt = $pdo->prepare(
                'SELECT id, username, prenom, nom, profile_picture
                 FROM users
                 WHERE id <> :current_user_id
                 ORDER BY prenom ASC, nom ASC, username ASC
                 LIMIT 50'
            );
            $shareRecipientsStmt->execute(['current_user_id' => (int) $_SESSION['user_id']]);
            $shareRecipients = $shareRecipientsStmt->fetchAll() ?: [];
        } catch (\PDOException $exception) {
            $posts = [];
            $suggestedUsers = [];
            $shareRecipients = [];
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

    private function ensurePostsImageColumn(\PDO $pdo): void {
        $stmt = $pdo->query("SHOW COLUMNS FROM posts LIKE 'image_path'");
        $exists = $stmt->fetch();

        if (!$exists) {
            $pdo->exec('ALTER TABLE posts ADD COLUMN image_path VARCHAR(255) NULL AFTER content');
        }
    }
}