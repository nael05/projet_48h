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
            $this->ensureMessagesMediaColumns($pdo);
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
        $sharedPostId = (int) ($_POST['shared_post_id'] ?? 0);
        $imagePaths = [];

        if (!empty($_FILES['image']) && is_array($_FILES['image'])) {
            $imagePaths = $this->storeMessageImagesUpload($_FILES['image']);
            if ($imagePaths === false) {
                $this->jsonResponse(['ok' => false, 'error' => 'Image invalide (jpg, png, gif, webp, 8MB max)'], 422);
                return;
            }
        }

        if (
            $receiverId <= 0
            || strlen($content) > 2000
            || $receiverId === $currentUserId
            || ($content === '' && empty($imagePaths) && $sharedPostId <= 0)
        ) {
            $this->jsonResponse(['ok' => false, 'error' => 'Message invalide'], 422);
            return;
        }

        try {
            $pdo = $this->getPdo();
            $this->ensureMessagesMediaColumns($pdo);
            $receiver = $this->loadUserById($pdo, $receiverId);
            if (!$receiver) {
                $this->jsonResponse(['ok' => false, 'error' => 'Destinataire introuvable'], 404);
                return;
            }

            if ($sharedPostId > 0) {
                $sharedPost = $this->loadPostById($pdo, $sharedPostId);
                if (!$sharedPost) {
                    $this->jsonResponse(['ok' => false, 'error' => 'Publication introuvable'], 404);
                    return;
                }
            }

            $imagePath = $imagePaths[0] ?? null;
            $imagePathsJson = !empty($imagePaths) ? json_encode(array_values($imagePaths), JSON_UNESCAPED_SLASHES) : null;

            $stmt = $pdo->prepare(
                 'INSERT INTO messages (sender_id, receiver_id, content, image_path, image_paths, shared_post_id)
                  VALUES (:sender_id, :receiver_id, :content, :image_path, :image_paths, :shared_post_id)'
            );
            $stmt->execute([
                'sender_id' => $currentUserId,
                'receiver_id' => $receiverId,
                'content' => $content,
                'image_path' => $imagePath,
                'image_paths' => $imagePathsJson,
                'shared_post_id' => $sharedPostId > 0 ? $sharedPostId : null,
            ]);

            $messageId = (int) $pdo->lastInsertId();
            $message = $this->loadMessageById($pdo, $messageId);

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
            $this->ensureMessagesMediaColumns($pdo);
            $otherUser = $this->loadUserById($pdo, $otherUserId);
            if (!$otherUser) {
                $this->jsonResponse(['ok' => false, 'error' => 'Utilisateur introuvable'], 404);
                return;
            }

            $stmt = $pdo->prepare(
                     $this->conversationBaseSelectSql() .
                     ' WHERE (
                          (m.sender_id = :me AND m.receiver_id = :other)
                          OR
                          (m.sender_id = :other AND m.receiver_id = :me)
                      )
                      AND m.id > :since_id
                      ORDER BY m.id ASC'
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

    public function sharePost(): void {
        $this->requireAuth();

        $basePath = $this->getBasePath();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . $basePath . '/feed');
            exit;
        }

        $currentUserId = (int) $_SESSION['user_id'];
        $receiverId = (int) ($_POST['receiver_id'] ?? 0);
        $sharedPostId = (int) ($_POST['post_id'] ?? 0);

        if ($receiverId <= 0 || $receiverId === $currentUserId || $sharedPostId <= 0) {
            header('Location: ' . $basePath . '/feed');
            exit;
        }

        try {
            $pdo = $this->getPdo();
            $this->ensureMessagesMediaColumns($pdo);

            $receiver = $this->loadUserById($pdo, $receiverId);
            $sharedPost = $this->loadPostById($pdo, $sharedPostId);
            if (!$receiver || !$sharedPost) {
                header('Location: ' . $basePath . '/feed');
                exit;
            }

            $stmt = $pdo->prepare(
                'INSERT INTO messages (sender_id, receiver_id, content, image_path, image_paths, shared_post_id)
                 VALUES (:sender_id, :receiver_id, :content, :image_path, :image_paths, :shared_post_id)'
            );
            $stmt->execute([
                'sender_id' => $currentUserId,
                'receiver_id' => $receiverId,
                'content' => '',
                'image_path' => null,
                'image_paths' => null,
                'shared_post_id' => $sharedPostId,
            ]);
        } catch (\PDOException $exception) {
            // keep UX simple: silent fallback on feed
        }

        header('Location: ' . $basePath . '/feed');
        exit;
    }

    private function loadContacts(\PDO $pdo, int $currentUserId): array {
        $stmt = $pdo->prepare(
                'SELECT u.id, u.username, u.prenom, u.nom, u.profile_picture,
                    lm.content AS last_message,
                    lm.image_path AS last_message_image,
                    lm.image_paths AS last_message_images,
                    lm.shared_post_id AS last_shared_post_id,
                    lm.created_at AS last_message_at
             FROM users u
             LEFT JOIN (
               SELECT m1.sender_id, m1.receiver_id, m1.content, m1.image_path, m1.image_paths, m1.shared_post_id, m1.created_at, m1.id
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
        $stmt = $pdo->prepare('SELECT id, username, prenom, nom, profile_picture FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch();

        return $user ?: null;
    }

    private function loadConversationMessages(\PDO $pdo, int $currentUserId, int $otherUserId): array {
        $stmt = $pdo->prepare(
            $this->conversationBaseSelectSql() .
            ' WHERE (
                (m.sender_id = :me AND m.receiver_id = :other)
                OR
                (m.sender_id = :other AND m.receiver_id = :me)
             )
             ORDER BY m.id ASC'
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

    private function ensureMessagesMediaColumns(\PDO $pdo): void {
        $stmt = $pdo->query("SHOW COLUMNS FROM messages LIKE 'image_path'");
        $imagePathExists = $stmt->fetch();

        if (!$imagePathExists) {
            $pdo->exec('ALTER TABLE messages ADD COLUMN image_path VARCHAR(255) NULL AFTER content');
        }

        $stmt = $pdo->query("SHOW COLUMNS FROM messages LIKE 'image_paths'");
        $imagePathsExists = $stmt->fetch();

        if (!$imagePathsExists) {
            $pdo->exec('ALTER TABLE messages ADD COLUMN image_paths TEXT NULL AFTER image_path');
        }

        $stmt = $pdo->query("SHOW COLUMNS FROM messages LIKE 'shared_post_id'");
        $sharedPostIdExists = $stmt->fetch();
        if (!$sharedPostIdExists) {
            $pdo->exec('ALTER TABLE messages ADD COLUMN shared_post_id BIGINT UNSIGNED NULL AFTER image_paths');
        }
    }

    private function conversationBaseSelectSql(): string {
        return 'SELECT m.id, m.sender_id, m.receiver_id, m.content, m.image_path, m.image_paths, m.shared_post_id, m.created_at,
                       sp.content AS shared_post_content,
                       sp.image_path AS shared_post_image_path,
                       sp.created_at AS shared_post_created_at,
                       spu.id AS shared_post_author_id,
                       spu.username AS shared_post_author_username,
                       spu.prenom AS shared_post_author_prenom,
                       spu.nom AS shared_post_author_nom,
                       spu.profile_picture AS shared_post_author_profile_picture
                FROM messages m
                LEFT JOIN posts sp ON sp.id = m.shared_post_id
                LEFT JOIN users spu ON spu.id = sp.user_id';
    }

    private function loadMessageById(\PDO $pdo, int $messageId): ?array {
        $stmt = $pdo->prepare(
            $this->conversationBaseSelectSql() .
            ' WHERE m.id = :id LIMIT 1'
        );
        $stmt->execute(['id' => $messageId]);
        $message = $stmt->fetch();

        return $message ?: null;
    }

    private function loadPostById(\PDO $pdo, int $postId): ?array {
        $stmt = $pdo->prepare('SELECT id FROM posts WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $postId]);
        $post = $stmt->fetch();

        return $post ?: null;
    }

    private function storeMessageImagesUpload(array $files): array|false {
        $normalizedFiles = $this->normalizeUploadedFiles($files);
        if (empty($normalizedFiles)) {
            return [];
        }

        $storedPaths = [];
        foreach ($normalizedFiles as $file) {
            $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
            if ($uploadError === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($uploadError !== UPLOAD_ERR_OK) {
                return false;
            }

            $storedPath = $this->storeSingleMessageImageUpload($file);
            if ($storedPath === null) {
                return false;
            }

            $storedPaths[] = $storedPath;
        }

        return $storedPaths;
    }

    private function normalizeUploadedFiles(array $files): array {
        $names = $files['name'] ?? null;
        $tmpNames = $files['tmp_name'] ?? null;
        $errors = $files['error'] ?? null;
        $sizes = $files['size'] ?? null;

        if (is_array($names)) {
            $normalized = [];
            foreach ($names as $index => $name) {
                $normalized[] = [
                    'name' => (string) $name,
                    'tmp_name' => (string) ($tmpNames[$index] ?? ''),
                    'error' => (int) ($errors[$index] ?? UPLOAD_ERR_NO_FILE),
                    'size' => (int) ($sizes[$index] ?? 0),
                ];
            }

            return $normalized;
        }

        return [$files];
    }

    private function storeSingleMessageImageUpload(array $file): ?string {
        $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($uploadError !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return null;
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 8 * 1024 * 1024) {
            return null;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = $finfo ? finfo_file($finfo, $tmpName) : '';
        if ($finfo) {
            finfo_close($finfo);
        }

        $allowed = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
        ];

        if (!isset($allowed[$mimeType])) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../public/uploads/messages';
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            return null;
        }

        $filename = 'message_' . bin2hex(random_bytes(10)) . '.' . $allowed[$mimeType];
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            return null;
        }

        return '/uploads/messages/' . $filename;
    }
}