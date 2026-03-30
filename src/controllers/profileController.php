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

    public function update(): void {
        $this->requireAuth();

        $basePath = $this->getBasePath();
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: ' . $basePath . '/profile');
            exit;
        }

        $currentUserId = (int) $_SESSION['user_id'];
        $bio = trim((string) ($_POST['bio'] ?? ''));
        if (strlen($bio) > 1000) {
            $_SESSION['profile_update_error'] = 'La biographie est trop longue (1000 caracteres max).';
            header('Location: ' . $basePath . '/profile');
            exit;
        }

        try {
            $pdo = $this->getPdo();
            $this->ensureUsersBannerColumn($pdo);

            $stmt = $pdo->prepare('SELECT profile_picture, banner_picture FROM users WHERE id = :id LIMIT 1');
            $stmt->execute(['id' => $currentUserId]);
            $user = $stmt->fetch();
            if (!$user) {
                $_SESSION['profile_update_error'] = 'Compte introuvable.';
                header('Location: ' . $basePath . '/profile');
                exit;
            }

            $profilePicture = (string) ($user['profile_picture'] ?? '');
            $bannerPicture = (string) ($user['banner_picture'] ?? '');

            if (!empty($_FILES['profile_picture']) && is_array($_FILES['profile_picture'])) {
                $uploadedProfile = $this->storeImageUpload($_FILES['profile_picture'], 'profiles');
                if ($uploadedProfile === null && (int) ($_FILES['profile_picture']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    $_SESSION['profile_update_error'] = 'Photo de profil invalide (jpg, png, gif, webp, 5MB max).';
                    header('Location: ' . $basePath . '/profile');
                    exit;
                }

                if ($uploadedProfile !== null) {
                    $this->removeUploadedFile($profilePicture, '/uploads/profiles/');
                    $profilePicture = $uploadedProfile;
                }
            }

            if (!empty($_FILES['banner_picture']) && is_array($_FILES['banner_picture'])) {
                $uploadedBanner = $this->storeImageUpload($_FILES['banner_picture'], 'banners');
                if ($uploadedBanner === null && (int) ($_FILES['banner_picture']['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_NO_FILE) {
                    $_SESSION['profile_update_error'] = 'Banniere invalide (jpg, png, gif, webp, 5MB max).';
                    header('Location: ' . $basePath . '/profile');
                    exit;
                }

                if ($uploadedBanner !== null) {
                    $this->removeUploadedFile($bannerPicture, '/uploads/banners/');
                    $bannerPicture = $uploadedBanner;
                }
            }

            $updateStmt = $pdo->prepare(
                'UPDATE users
                 SET bio = :bio,
                     profile_picture = :profile_picture,
                     banner_picture = :banner_picture
                 WHERE id = :id'
            );
            $updateStmt->execute([
                'bio' => $bio !== '' ? $bio : null,
                'profile_picture' => $profilePicture !== '' ? $profilePicture : null,
                'banner_picture' => $bannerPicture !== '' ? $bannerPicture : null,
                'id' => $currentUserId,
            ]);

            $_SESSION['profile_picture'] = $profilePicture;
        } catch (\PDOException $exception) {
            $_SESSION['profile_update_error'] = 'Mise a jour du profil impossible pour le moment.';
        }

        header('Location: ' . $basePath . '/profile');
        exit;
    }

    private function renderProfileByUserId(int $userId): void {
        $basePath = $this->getBasePath();

        $activeTab = 'profile';
        $activeNav = 'profile';
        $mockUrl = 'localhost:3000/user?id=' . $userId;

        $profileFullName = (string) ($_SESSION['username'] ?? 'Utilisateur');
        $profileFormation = 'Profil Ynov';
        $profileBio = '';
        $profilePicture = '';
        $profileBanner = '';
        $isOwnProfile = ($userId === (int) ($_SESSION['user_id'] ?? 0));
        $profileUpdateError = $_SESSION['profile_update_error'] ?? null;
        unset($_SESSION['profile_update_error']);
        $profilePosts = [];
        $profileStats = [
            'posts' => 0,
            'likes' => 0,
            'comments' => 0,
        ];

        try {
            $pdo = $this->getPdo();
            $this->ensurePostsImageColumn($pdo);
            $this->ensureUsersBannerColumn($pdo);

            $stmt = $pdo->prepare('SELECT nom, prenom, formation, bio, profile_picture, banner_picture FROM users WHERE id = :id LIMIT 1');
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

            $profileBio = trim((string) ($user['bio'] ?? ''));
            $profilePicture = (string) ($user['profile_picture'] ?? '');
            $profileBanner = (string) ($user['banner_picture'] ?? '');

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

    private function ensureUsersBannerColumn(\PDO $pdo): void {
        $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'banner_picture'");
        $exists = $stmt->fetch();

        if (!$exists) {
            $pdo->exec('ALTER TABLE users ADD COLUMN banner_picture VARCHAR(255) NULL AFTER profile_picture');
        }
    }

    private function storeImageUpload(array $file, string $subFolder): ?string {
        $uploadError = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($uploadError !== UPLOAD_ERR_OK) {
            return null;
        }

        $tmpName = (string) ($file['tmp_name'] ?? '');
        if ($tmpName === '' || !is_uploaded_file($tmpName)) {
            return null;
        }

        $size = (int) ($file['size'] ?? 0);
        if ($size <= 0 || $size > 5 * 1024 * 1024) {
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

        $uploadDir = __DIR__ . '/../../public/uploads/' . $subFolder;
        if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
            return null;
        }

        $filename = $subFolder . '_' . bin2hex(random_bytes(10)) . '.' . $allowed[$mimeType];
        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

        if (!move_uploaded_file($tmpName, $targetPath)) {
            return null;
        }

        return '/uploads/' . $subFolder . '/' . $filename;
    }

    private function removeUploadedFile(string $relativePath, string $expectedPrefix): void {
        if ($relativePath === '' || !str_starts_with($relativePath, $expectedPrefix)) {
            return;
        }

        $absolutePath = __DIR__ . '/../../public' . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        if (is_file($absolutePath)) {
            @unlink($absolutePath);
        }
    }
}