<?php
namespace App\Controllers;

class MessageController {
    public function index(): void {
        $this->requireAuth();

        $activeTab = 'messages';
        $activeNav = 'messages';
        $mockUrl = 'localhost:3000/messages';
        $basePath = $this->getBasePath();

        $currentUserId = (int) $_SESSION['user_id'];
        $contacts = [];
        $selectedUser = null;
        $conversationMessages = [];

        try {
            $pdo = $this->getPdo();
            $contacts = $this->loadContacts($pdo, $currentUserId);

            $requestedUserId = (int) ($_GET['with'] ?? 0);
            if ($requestedUserId <= 0 && !empty($contacts)) {
                $requestedUserId = (int) $contacts[0]['id'];
            }

            if ($requestedUserId > 0) {
                $selectedUser = $this->loadUserById($pdo, $requestedUserId);
                if ($selectedUser) {
                    $conversationMessages = $this->loadConversationMessages($pdo, $currentUserId, $requestedUserId);
                }
            }
        } catch (\PDOException $exception) {
            $contacts = [];
            $selectedUser = null;
            $conversationMessages = [];
        }

        require_once __DIR__ . '/../Views/layout/header.php';
        require_once __DIR__ . '/../Views/messages.php';
        require_once __DIR__ . '/../Views/layout/footer.php';
    }

    public function send(): void {
        $this->requireAuth();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->jsonResponse(['ok' => false, 'error' => 'Method not allowed'], 405);
            return;
        }

        $currentUserId = (int) $_SESSION['user_id'];
        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $content = trim((string) ($_POST['content'] ?? ''));

        if ($receiverId <= 0 || $content === '' || strlen($content) > 2000 || $receiverId === $currentUserId) {
            $this->jsonResponse(['ok' => false, 'error' => 'Message invalide'], 422);
            return;
        }

        try {
            $pdo = $this->getPdo();
            $receiver = $this->loadUserById($pdo, $receiverId);
            if (!$receiver) {
                $this->jsonResponse(['ok' => false, 'error' => 'Destinataire introuvable'], 404);
                return;
            }

            $stmt = $pdo->prepare(
                'INSERT INTO messages (sender_id, receiver_id, content) VALUES (:sender_id, :receiver_id, :content)'
            );
            $stmt->execute([
                'sender_id' => $currentUserId,
                'receiver_id' => $receiverId,
                'content' => $content,
            ]);

            $messageId = (int) $pdo->lastInsertId();
            $messageStmt = $pdo->prepare(
                'SELECT id, sender_id, receiver_id, content, created_at
                 FROM messages
                 WHERE id = :id LIMIT 1'
            );
            $messageStmt->execute(['id' => $messageId]);
            $message = $messageStmt->fetch() ?: null;

            $this->jsonResponse(['ok' => true, 'message' => $message]);
            return;
        } catch (\PDOException $exception) {
            $this->jsonResponse(['ok' => false, 'error' => 'Envoi impossible'], 500);
            return;
        }
    }

    public function poll(): void {
        $this->requireAuth();

        $currentUserId = (int) $_SESSION['user_id'];
        $otherUserId = (int) ($_GET['with'] ?? 0);
        $sinceId = (int) ($_GET['since_id'] ?? 0);

        if ($otherUserId <= 0 || $otherUserId === $currentUserId) {
            $this->jsonResponse(['ok' => false, 'error' => 'Conversation invalide'], 422);
            return;
        }

        try {
            $pdo = $this->getPdo();
            $otherUser = $this->loadUserById($pdo, $otherUserId);
            if (!$otherUser) {
                $this->jsonResponse(['ok' => false, 'error' => 'Utilisateur introuvable'], 404);
                return;
            }

            $stmt = $pdo->prepare(
                'SELECT id, sender_id, receiver_id, content, created_at
                 FROM messages
                 WHERE (
                    (sender_id = :me AND receiver_id = :other)
                    OR
                    (sender_id = :other AND receiver_id = :me)
                 )
                 AND id > :since_id
                 ORDER BY id ASC'
            );
            $stmt->execute([
                'me' => $currentUserId,
                'other' => $otherUserId,
                'since_id' => $sinceId,
            ]);

            $messages = $stmt->fetchAll() ?: [];
            $this->jsonResponse(['ok' => true, 'messages' => $messages]);
            return;
        } catch (\PDOException $exception) {
            $this->jsonResponse(['ok' => false, 'error' => 'Polling impossible'], 500);
            return;
        }
    }

    private function loadContacts(\PDO $pdo, int $currentUserId): array {
        $stmt = $pdo->prepare(
            'SELECT u.id, u.username, u.prenom, u.nom,
                    lm.content AS last_message,
                    lm.created_at AS last_message_at
             FROM users u
             LEFT JOIN (
               SELECT m1.sender_id, m1.receiver_id, m1.content, m1.created_at, m1.id
               FROM messages m1
               INNER JOIN (
                 SELECT
                   LEAST(sender_id, receiver_id) AS user_a,
                   GREATEST(sender_id, receiver_id) AS user_b,
                   MAX(id) AS max_id
                 FROM messages
                 WHERE sender_id = :me OR receiver_id = :me
                 GROUP BY LEAST(sender_id, receiver_id), GREATEST(sender_id, receiver_id)
               ) t ON t.max_id = m1.id
             ) lm
               ON (
                 (lm.sender_id = :me AND lm.receiver_id = u.id)
                 OR
                 (lm.receiver_id = :me AND lm.sender_id = u.id)
               )
             WHERE u.id <> :me
             ORDER BY (lm.created_at IS NULL) ASC, lm.created_at DESC, u.prenom ASC, u.nom ASC'
        );
        $stmt->execute(['me' => $currentUserId]);

        return $stmt->fetchAll() ?: [];
    }

    private function loadUserById(\PDO $pdo, int $userId): ?array {
        $stmt = $pdo->prepare('SELECT id, username, prenom, nom FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    private function loadConversationMessages(\PDO $pdo, int $currentUserId, int $otherUserId): array {
        $stmt = $pdo->prepare(
            'SELECT id, sender_id, receiver_id, content, created_at
             FROM messages
             WHERE (
                (sender_id = :me AND receiver_id = :other)
                OR
                (sender_id = :other AND receiver_id = :me)
             )
             ORDER BY id ASC'
        );
        $stmt->execute([
            'me' => $currentUserId,
            'other' => $otherUserId,
        ]);

        return $stmt->fetchAll() ?: [];
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

    private function jsonResponse(array $payload, int $statusCode = 200): void {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload);
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