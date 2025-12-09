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
	public function signin(): array {
		$username = trim(htmlspecialchars($_POST['username'], ENT_QUOTES, "UTF-8") ?? '');
		$password = htmlspecialchars($_POST['password'] ?? '', ENT_QUOTES, "UTF-8");
		if ($username === '' || $password === '') {
			return [["error" => "username and password are required"], 400];
		}
		$created = $this->service->createUser($username, $password);
		if ($created === null) {
			return [["error" => "cannot create user (maybe duplicate username or invalid input)"], 400];
		}
		// Do not return password hash
		return [[
			'id' => $created['id'],
			'username' => $created['username'],
		], 201];
	}

	// (removed duplicate logout definition)
}