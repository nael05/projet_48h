<?php
namespace App\Controllers;

class PostController {
	public function create(): void {
		$basePath = $this->getBasePath();

		if (!$this->isAuthenticated()) {
			header('Location: ' . $basePath . '/login');
			exit;
		}

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		$content = trim((string) ($_POST['content'] ?? ''));
		$imagePath = null;

		if ($content === '') {
			$_SESSION['publish_error'] = 'Le contenu du post ne peut pas etre vide.';
			$_SESSION['old_post_content'] = $content;
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		if (strlen($content) > 3000) {
			$_SESSION['publish_error'] = 'Le post est trop long (3000 caracteres max).';
			$_SESSION['old_post_content'] = $content;
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		if (!empty($_FILES['image']) && is_array($_FILES['image'])) {
			$uploadError = (int) ($_FILES['image']['error'] ?? UPLOAD_ERR_NO_FILE);
			if ($uploadError !== UPLOAD_ERR_NO_FILE) {
				$imagePath = $this->storeImageUpload($_FILES['image']);
				if ($imagePath === null) {
					$_SESSION['publish_error'] = 'Image invalide. Formats autorises: jpg, png, gif, webp (5MB max).';
					$_SESSION['old_post_content'] = $content;
					header('Location: ' . $basePath . '/feed');
					exit;
				}
			}
		}

		try {
			$pdo = $this->getPdo();
			$this->ensurePostsImageColumn($pdo);
			$stmt = $pdo->prepare('INSERT INTO posts (user_id, content, image_path) VALUES (:user_id, :content, :image_path)');
			$stmt->execute([
				'user_id' => (int) $_SESSION['user_id'],
				'content' => $content,
				'image_path' => $imagePath,
			]);
		} catch (\PDOException $exception) {
			$_SESSION['publish_error'] = 'Publication impossible pour le moment.';
			$_SESSION['old_post_content'] = $content;
		}

		header('Location: ' . $basePath . '/feed');
		exit;
	}

	public function toggleLike(): void {
		$basePath = $this->getBasePath();

		if (!$this->isAuthenticated()) {
			header('Location: ' . $basePath . '/login');
			exit;
		}

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		$postId = (int) ($_POST['post_id'] ?? 0);
		if ($postId <= 0) {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		try {
			$pdo = $this->getPdo();
			$checkStmt = $pdo->prepare('SELECT id FROM likes WHERE user_id = :user_id AND post_id = :post_id LIMIT 1');
			$checkStmt->execute([
				'user_id' => (int) $_SESSION['user_id'],
				'post_id' => $postId,
			]);

			$existingLike = $checkStmt->fetch();
			if ($existingLike) {
				$deleteStmt = $pdo->prepare('DELETE FROM likes WHERE user_id = :user_id AND post_id = :post_id');
				$deleteStmt->execute([
					'user_id' => (int) $_SESSION['user_id'],
					'post_id' => $postId,
				]);
			} else {
				$insertStmt = $pdo->prepare('INSERT INTO likes (user_id, post_id) VALUES (:user_id, :post_id)');
				$insertStmt->execute([
					'user_id' => (int) $_SESSION['user_id'],
					'post_id' => $postId,
				]);
			}
		} catch (\PDOException $exception) {
			// Ignore l'erreur pour conserver la navigation fluide.
		}

		header('Location: ' . $basePath . '/feed');
		exit;
	}

	public function addComment(): void {
		$basePath = $this->getBasePath();

		if (!$this->isAuthenticated()) {
			header('Location: ' . $basePath . '/login');
			exit;
		}

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		$postId = (int) ($_POST['post_id'] ?? 0);
		$content = trim((string) ($_POST['comment_content'] ?? ''));

		if ($postId <= 0 || $content === '') {
			$_SESSION['publish_error'] = 'Commentaire invalide.';
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		if (strlen($content) > 1000) {
			$_SESSION['publish_error'] = 'Le commentaire est trop long (1000 caracteres max).';
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		try {
			$pdo = $this->getPdo();
			$stmt = $pdo->prepare('INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)');
			$stmt->execute([
				'post_id' => $postId,
				'user_id' => (int) $_SESSION['user_id'],
				'content' => $content,
			]);
		} catch (\PDOException $exception) {
			$_SESSION['publish_error'] = 'Impossible d\'ajouter le commentaire pour le moment.';
		}

		header('Location: ' . $basePath . '/feed');
		exit;
	}

	public function delete(): void {
		$basePath = $this->getBasePath();

		if (!$this->isAuthenticated()) {
			header('Location: ' . $basePath . '/login');
			exit;
		}

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		$postId = (int) ($_POST['post_id'] ?? 0);
		if ($postId <= 0) {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		try {
			$pdo = $this->getPdo();
			$this->ensurePostsImageColumn($pdo);

			$postStmt = $pdo->prepare('SELECT user_id, image_path FROM posts WHERE id = :post_id LIMIT 1');
			$postStmt->execute(['post_id' => $postId]);
			$post = $postStmt->fetch();

			if (!$post || (int) $post['user_id'] !== (int) $_SESSION['user_id']) {
				$_SESSION['publish_error'] = 'Vous ne pouvez supprimer que vos propres posts.';
				header('Location: ' . $basePath . '/feed');
				exit;
			}

			$deleteStmt = $pdo->prepare('DELETE FROM posts WHERE id = :post_id AND user_id = :user_id');
			$deleteStmt->execute([
				'post_id' => $postId,
				'user_id' => (int) $_SESSION['user_id'],
			]);

			$imagePath = (string) ($post['image_path'] ?? '');
			if ($imagePath !== '' && str_starts_with($imagePath, '/uploads/posts/')) {
				$absoluteImagePath = __DIR__ . '/../../public' . str_replace('/', DIRECTORY_SEPARATOR, $imagePath);
				if (is_file($absoluteImagePath)) {
					@unlink($absoluteImagePath);
				}
			}
		} catch (\PDOException $exception) {
			$_SESSION['publish_error'] = 'Impossible de supprimer ce post pour le moment.';
		}

		header('Location: ' . $basePath . '/feed');
		exit;
	}

	private function isAuthenticated(): bool {
		return !empty($_SESSION['is_authenticated']) || !empty($_SESSION['user_id']);
	}

	private function getBasePath(): string {
		$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
		return ($basePath === '/' || $basePath === '.') ? '' : $basePath;
	}

	private function storeImageUpload(array $file): ?string {
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

		$uploadDir = __DIR__ . '/../../public/uploads/posts';
		if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
			return null;
		}

		$filename = 'post_' . bin2hex(random_bytes(10)) . '.' . $allowed[$mimeType];
		$targetPath = $uploadDir . DIRECTORY_SEPARATOR . $filename;

		if (!move_uploaded_file($tmpName, $targetPath)) {
			return null;
		}

		return '/uploads/posts/' . $filename;
	}

	private function ensurePostsImageColumn(\PDO $pdo): void {
		$stmt = $pdo->query("SHOW COLUMNS FROM posts LIKE 'image_path'");
		$exists = $stmt->fetch();

		if (!$exists) {
			$pdo->exec('ALTER TABLE posts ADD COLUMN image_path VARCHAR(255) NULL AFTER content');
		}
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
