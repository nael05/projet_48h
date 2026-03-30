<?php
namespace App\Controllers;

class PostController {
	public function create(): void {
		$basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
		$basePath = ($basePath === '/' || $basePath === '.') ? '' : $basePath;

		if (empty($_SESSION['is_authenticated']) && empty($_SESSION['user_id'])) {
			header('Location: ' . $basePath . '/login');
			exit;
		}

		if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
			header('Location: ' . $basePath . '/feed');
			exit;
		}

		$content = trim((string) ($_POST['content'] ?? ''));

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

		try {
			$pdo = $this->getPdo();
			$stmt = $pdo->prepare('INSERT INTO posts (user_id, content) VALUES (:user_id, :content)');
			$stmt->execute([
				'user_id' => (int) $_SESSION['user_id'],
				'content' => $content,
			]);
		} catch (\PDOException $exception) {
			$_SESSION['publish_error'] = 'Publication impossible pour le moment.';
			$_SESSION['old_post_content'] = $content;
		}

		header('Location: ' . $basePath . '/feed');
		exit;
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
