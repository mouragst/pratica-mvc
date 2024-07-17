<?php

namespace App\Controller\Admin;

use App\Utils\View;
use App\Model\Entity\User;
use App\Session\Admin\Login as SessionAdminLogin;

class Login extends Page {

    public static function getLogin($request, $errorMessage = null) {
        $status = !is_null($errorMessage) ? View::render('admin/login/status', [
            'statusMessage' => $errorMessage
        ]) : '';

        $content = View::render('admin/login', [
            'status' => $status
        ]);
        return parent::getPage('Login - Teste', $content);
    }

    public static function setLogin($request) {
        $postVars = $request->getPostVars();
        $email = $postVars['email'] ?? '';
        $password = $postVars['password'] ?? '';

        $user = User::getUserByEmail($email);

        if (!$user instanceof User && empty($user) ||
         !password_verify($password, $user->password)) {
            return self::getLogin($request, 'E-mail ou senha inválida!');
        }

        SessionAdminLogin::login($user);
        $request->getRouter()->redirect('/admin');
    }

    public static function setLogout($request) {
        SessionAdminLogin::logout($user);
        $request->getRouter()->redirect('/admin/login');
    }
}
