<?php

namespace App\Controllers;

use App\Configuration;
use App\Core\SecureController;
use Exception;
use Framework\Http\Request;
use Framework\Http\Responses\Response;
use Framework\Http\Responses\ViewResponse;
use App\Models\User;

/**
 * Class AuthController
 *
 * This controller handles authentication actions such as login, logout, and redirection to the login page. It manages
 * user sessions and interactions with the authentication system.
 *
 * @package App\Controllers
 */
class AuthController extends SecureController
{
    /**
     * Redirects to the login page.
     *
     * This action serves as the default landing point for the authentication section of the application, directing
     * users to the login URL specified in the configuration.
     *
     * @return Response The response object for the redirection to the login page.
     */
    public function index(Request $request): Response
    {
        return $this->redirect(Configuration::LOGIN_URL);
    }

    /**
     * Authenticates a user and processes the login request.
     *
     * This action handles user login attempts. If the login form is submitted, it attempts to authenticate the user
     * with the provided credentials. Upon successful login, the user is redirected to the admin dashboard.
     * If authentication fails, an error message is displayed on the login page.
     *
     * @return Response The response object which can either redirect on success or render the login view with
     *                  an error message on failure.
     * @throws Exception If the parameter for the URL generator is invalid throws an exception.
     */
    public function login(Request $request): Response
    {
        $logged = null;
        if ($request->hasValue('submit')) {
            $logged = $this->app->getAuthenticator()->login($request->value('username'), $request->value('password'));
            if ($logged) {
                return $this->redirect($this->url("admin.index"));
            }
        }

        $message = $logged === false ? 'Bad username or password' : null;
        return $this->html(compact("message"));
    }

    /**
     * Logs out the current user.
     *
     * This action terminates the user's session and redirects them to a view. It effectively clears any authentication
     * tokens or session data associated with the user.
     *
     * @return ViewResponse The response object that renders the logout view.
     */
    public function logout(Request $request): Response
    {
        $this->app->getAuthenticator()->logout();
        return $this->redirect($this->url('home.index'));
    }

    /**
     * Handles user registration.
     *
     * @param Request $request
     * @return Response
     */
    public function register(Request $request): Response
    {
        $message = null;
        if ($request->isPost()) {
            $name = $request->value('name');
            $email = $request->value('email');
            $password = $request->value('password');

            // Basic validation
            if (empty($name) || empty($email) || empty($password)) {
                $message = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $message = "Invalid email format.";
            } elseif (strlen($password) < 6) {
                $message = "Password must be at least 6 characters long.";
            } elseif (strlen($name) < 2 || strlen($name) > 50) {
                $message = "Name must be between 2 and 50 characters.";
            } else {
                // Check if email already exists
                $existingUser = User::getAll('email = ?', [$email]);
                if (!empty($existingUser)) {
                    $message = "Email is already registered.";
                } else {
                    try {
                        $user = new User();
                        $user->name = $name;
                        $user->email = $email;
                        $user->password = password_hash($password, PASSWORD_DEFAULT);
                        $user->role = 'agent'; // Default role
                        $user->save();

                        // Auto-login or redirect to login
                        return $this->redirect($this->url('auth.login', ['message' => 'Registration successful. Please login.']));
                    } catch (Exception $e) {
                        $message = "Registration failed: " . $e->getMessage();
                    }
                }
            }
        }

        return $this->html(compact('message'));
    }
}
