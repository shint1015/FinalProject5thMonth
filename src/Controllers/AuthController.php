<?php

include_once __DIR__ . '/../Helpers/jwt.php';
include_once __DIR__ . '/../Services/UserService.php';
include_once __DIR__ . '/../Repositories/UserRepository.php';
include_once __DIR__ . '/../../config/database.php';
include_once __DIR__ . '/../../config/env.php';

class AuthController {

	private UserService $service;

	public function __construct()
	{
		// Wire repository with PDO and inject into service
		$repo = new UserRepository(db());
		$this->service = new UserService($repo);
	}

	public function login(): array {
		$username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8') ?? '');
		$password = htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') ?? '';
		if ($username === '' || $password === '') {
			return [["error" => "username and password are required"], 400];
		}

		// Authenticate via service (no direct repository use here)
		$user = $this->service->authenticate($username, $password);
		if ($user === null) {
			return [["error" => "invalid credentials"], 401];
		}

		$now = time();
		$exp = $now + 3600; // 1 hour
		$subject = $user['user_id'] ?? ($user['id'] ?? null);
		if ($subject === null || $subject === '') {
			$subject = $username;
		}
		$payload = [
			'sub' => (string)$subject,
			'username' => $user['username'] ?? $username,
			'role' => $user['role'] ?? 'general',
			'iat' => $now,
			'exp' => $exp,
		];

		// JWT secret from env
		$secret = defined('JWT_SECRET') ? JWT_SECRET : 'change-me';

		$token = jwt_encode($payload, $secret, 'HS256');
		// Store minimal profile in session for session-based auth
		if (session_status() === PHP_SESSION_ACTIVE) {
			$_SESSION['user'] = [
				'user_id' => $user['user_id'] ?? null,
				'email' => $user['email'] ?? ($user['username'] ?? $username),
				'role' => $user['role'] ?? 'general',
				'display_name' => $user['display_name'] ?? null,
			];
		}

		return [[
			'access_token' => $token,
			'expires_in' => 3600,
		], 200];
	}

	public function logout(): array {
		if (session_status() === PHP_SESSION_ACTIVE) {
			unset($_SESSION['user']);
			session_unset();
			session_destroy();
		}
		return [["message" => "logout successful"], 200];
	}

	public function refresh_token(): array {
		return [["message" => "not implemented"], 501];
	}

	// Sign up (account creation)
	public function signup(): array {
		// Accept email or username (treated as email)
		$email = trim(htmlspecialchars($_POST['email'] ?? ($_POST['username'] ?? ''), ENT_QUOTES, "UTF-8"));
		$password = htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, "UTF-8");
		$firstName = isset($_POST['first_name']) ? trim(htmlspecialchars($_POST['first_name'], ENT_QUOTES, "UTF-8")) : null;
		$lastName = isset($_POST['last_name']) ? trim(htmlspecialchars($_POST['last_name'], ENT_QUOTES, "UTF-8")) : null;
		$displayName = isset($_POST['display_name']) ? trim(htmlspecialchars($_POST['display_name'], ENT_QUOTES, "UTF-8")) : null;
		$role = isset($_POST['role']) ? trim(strtolower(htmlspecialchars($_POST['role'], ENT_QUOTES, "UTF-8"))) : 'general';

		if ($email === '' || $password === '') {
			return [["error" => "email and password are required"], 400];
		}

		// Only allow 'admin' or 'general'
		if (!in_array($role, ['admin', 'general'], true)) {
			$role = 'general';
		}

		$created = $this->service->createUser($email, $password, $firstName, $lastName, $displayName, $role);
		if ($created === null) {
			return [["error" => "cannot create user (duplicate email or invalid input)"], 400];
		}
		// Do not return password hash
		return [[
			'user_id' => $created['user_id'],
			'email' => $created['email'] ?? $email,
			'first_name' => $created['first_name'] ?? $firstName,
			'last_name' => $created['last_name'] ?? $lastName,
			'display_name' => $created['display_name'] ?? $displayName,
			'role' => $created['role'] ?? $role,
		], 201];
	}

	// Current user from session (fallback to JWT if no session)
	public function me(): array {
		// Prefer session
		if (session_status() === PHP_SESSION_ACTIVE && isset($_SESSION['user'])) {
			return [$_SESSION['user'], 200];
		}
		// Fallback: try Authorization bearer
		$auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
		$user = $this->service->getUserFromToken($auth);
		if ($user !== null) {
			return [$user, 200];
		}
		return [["error" => "unauthorized"], 401];
	}

	// (removed duplicate logout definition)
}