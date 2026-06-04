<?php

declare(strict_types=1);

class AuthController
{
    private User $users;

    public function __construct()
    {
        $this->users = new User();
    }

    public function loginForm(): void
    {
        if (is_logged_in()) {
            ce_redirect(is_admin() ? 'admin' : 'perfil');
        }
        ce_view('auth/login', ['title' => 'Iniciar sesión']);
    }

    public function login(): void
    {
        if (!ce_csrf_verify()) {
            flash('error', 'Token inválido.');
            ce_redirect('login');
        }
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');
        $user = $email ? $this->users->findByEmail((string) $email) : null;
        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Credenciales incorrectas.');
            ce_redirect('login');
        }
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'full_name' => $user['full_name'] ?? '',
        ];
        flash('success', '¡Bienvenido a CAFEESQUINA!');
        ce_redirect($user['role'] === 'admin' ? 'admin' : 'perfil');
    }

    public function registerForm(): void
    {
        if (is_logged_in()) {
            ce_redirect('perfil');
        }
        ce_view('auth/register', ['title' => 'Crear cuenta']);
    }

    public function register(): void
    {
        if (!ce_csrf_verify()) {
            flash('error', 'Token inválido.');
            ce_redirect('register');
        }
        $fullName = sanitize_string((string) ($_POST['full_name'] ?? ''), 100);
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');
        $confirm = (string) ($_POST['password_confirm'] ?? '');
        $username = preg_replace('/[^a-zA-Z0-9_]/', '', strtolower(explode('@', (string) $email)[0] ?? 'user'));
        if (strlen($username) < 3) {
            $username = 'user' . substr((string) time(), -6);
        }
        if ($fullName === '' || !$email || strlen($password) < 8 || $password !== $confirm) {
            flash('error', 'Revisa los datos del formulario.');
            ce_redirect('register');
        }
        if ($this->users->findByEmail((string) $email) || $this->users->findByUsername($username)) {
            flash('error', 'Correo ya registrado.');
            ce_redirect('register');
        }
        $this->users->create($username, (string) $email, $password, 'client', $fullName);
        flash('success', 'Cuenta creada. Inicia sesión.');
        ce_redirect('login');
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        session_regenerate_id(true);
        flash('success', 'Sesión cerrada.');
        ce_redirect('');
    }

    public function profile(): void
    {
        require_auth();
        if (is_admin()) {
            ce_redirect('admin');
        }
        $user = $this->users->find((int) auth_user()['id']);
        ce_view('auth/profile', ['title' => 'Mi perfil', 'user' => $user]);
    }

    public function updateProfile(): void
    {
        require_auth();
        if (!ce_csrf_verify()) {
            flash('error', 'Token inválido.');
            ce_redirect('perfil');
        }
        $id = (int) auth_user()['id'];
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$email) {
            flash('error', 'Correo inválido.');
            ce_redirect('perfil');
        }
        $this->users->updateProfile(
            $id,
            sanitize_string((string) ($_POST['full_name'] ?? ''), 100),
            sanitize_string((string) ($_POST['phone'] ?? ''), 20),
            (string) $email
        );
        $_SESSION['user']['email'] = (string) $email;
        flash('success', 'Perfil actualizado.');
        ce_redirect('perfil');
    }

    public function orders(): void
    {
        require_auth();
        $orders = (new Order())->byUser((int) auth_user()['id']);
        ce_view('auth/orders', ['title' => 'Mis pedidos', 'orders' => $orders]);
    }
}
