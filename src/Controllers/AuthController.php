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
		$payload = [
			'sub' => (string)($user['id'] ?? $username),
			'username' => $user['username'] ?? $username,
			'iat' => $now,
			'exp' => $exp,
		];

		// JWT secret from env
		$secret = defined('JWT_SECRET') ? JWT_SECRET : 'change-me';

		$token = jwt_encode($payload, $secret, 'HS256');
		return [[
			'access_token' => $token,
			// 'token_type' => 'Bearer',
			'expires_in' => 3600,
		], 200];
	}

	public function logout(): array {
		return [["message" => "logout successful (client-side token discard)"], 200];
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
			'id' => $created['id'],
			'email' => $created['email'] ?? $email,
			'first_name' => $created['first_name'] ?? $firstName,
			'last_name' => $created['last_name'] ?? $lastName,
			'display_name' => $created['display_name'] ?? $displayName,
			'role' => $created['role'] ?? $role,
		], 201];
	}

	// (removed duplicate logout definition)
}