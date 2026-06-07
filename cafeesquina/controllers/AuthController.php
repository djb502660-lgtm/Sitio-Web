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
            if (is_admin()) {
                ce_redirect('admin');
            }
            ce_redirect($this->clientRedirectAfterPurchaseIntent());
        }
        if (! empty($_GET['compra'])) {
            flash('error', 'Debes iniciar sesión para comprar por WhatsApp.');
        }
        ce_view('auth/login', ['title' => 'Iniciar sesión']);
    }

    public function login(): void
    {
        if (ce_request_method() !== 'POST') {
            ce_redirect('login');
        }
        if (! ce_csrf_verify()) {
            flash('error', 'Token inválido.');
            ce_redirect('login');
        }
        if (! ce_rate_limit('login:' . ce_client_ip(), 5, 900)) {
            flash('error', 'Demasiados intentos. Espera 15 minutos e inténtalo de nuevo.');
            ce_redirect('login');
        }
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $password = (string) ($_POST['password'] ?? '');
        $user = $email ? $this->users->findByEmail((string) $email) : null;
        if (!$user || !password_verify($password, $user['password'])) {
            flash('error', 'Credenciales incorrectas.');
            ce_redirect('login');
        }
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role'],
            'full_name' => $user['full_name'] ?? '',
        ];
        flash('success', '¡Bienvenido a CAFEESQUINA!');
        if ($user['role'] === 'admin') {
            ce_redirect('admin');
        }
        if (! empty($_POST['compra'])) {
            $_SESSION['post_auth_redirect'] = 'menu';
            $full = $this->users->find((int) $user['id']);
            $phone = trim((string) ($full['phone'] ?? ''));
            if ($phone === '') {
                ce_redirect('perfil?compra=1');
            }
            unset($_SESSION['post_auth_redirect']);
            ce_redirect('menu');
        }
        ce_redirect('perfil');
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
        if (ce_request_method() !== 'POST') {
            ce_redirect('register');
        }
        if (! ce_csrf_verify()) {
            flash('error', 'Token inválido.');
            ce_redirect('register');
        }
        if (! ce_rate_limit('register:' . ce_client_ip(), 5, 3600)) {
            flash('error', 'Demasiados registros desde esta red. Inténtalo más tarde.');
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
        flash('success', 'Cuenta creada correctamente.');
        ce_redirect('');
    }

    public function logout(): void
    {
        if (ce_request_method() !== 'POST' || ! ce_csrf_verify()) {
            flash('error', 'Solicitud inválida.');
            ce_redirect(is_logged_in() ? (is_admin() ? 'admin' : 'perfil') : '');
        }
        unset($_SESSION['user']);
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
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
        $_SESSION['user']['full_name'] = sanitize_string((string) ($_POST['full_name'] ?? ''), 100);
        flash('success', 'Perfil actualizado.');
        unset($_SESSION['post_auth_redirect']);
        ce_redirect('menu');
    }

    private function clientRedirectAfterPurchaseIntent(): string
    {
        $user = $this->users->find((int) auth_user()['id']);
        $phone = trim((string) ($user['phone'] ?? ''));
        if ($phone === '') {
            return 'perfil?compra=1';
        }

        return 'menu';
    }

    public function orders(): void
    {
        require_auth();
        $orders = (new Order())->byUser((int) auth_user()['id']);
        ce_view('auth/orders', ['title' => 'Mis pedidos', 'orders' => $orders]);
    }
}
