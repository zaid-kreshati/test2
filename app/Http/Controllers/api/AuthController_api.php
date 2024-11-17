<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth;
use App\Traits\JsonResponseTrait;
use Spatie\Permission\Models\Role;
use App\Http\Requests\Auth\registerRequest;
use App\Http\Requests\Auth\loginRequest;
use Exception;
use Illuminate\Validation\ValidationFailedException;

class AuthController_api extends Controller
{
    use JsonResponseTrait; // Use the trait for JSON responses

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }


    public function showRegisterForm()
    {
        return view('register');
    }

    public function showLoginForm()
    {
        return view('login');
    }





  public function register_user(registerRequest $request)
{

    try {
        // Prepare the data for registration
        $data = $request->only(['name', 'email', 'password']);

        // Call the AuthService to register the user
        $result = $this->authService->register($data);
        $user = $result['user']; // Assuming the registered user is returned in 'user' key
        $user->assignRole('user'); // For 'register_user'

        // Check if registration was successful
        if ($result['status'] === 'success') {
            return $this->successResponse(null, 'Registration successful. Welcome!');
        } else {
            return $this->errorResponse('User registration failed. Please try again.');
        }
    } catch (ValidationException $e) {
        // Catch validation errors and return them as a structured response
        $errors = $e->errors();

        return $this->errorResponse('Validation failed', 422, $errors);
    } catch (Exception $e) {
        return $this->errorResponse('An error occurred during registration.', 500, $e->getMessage());
    }
}


    public function register_admin(registerRequest $request)
    {


        try {
            // Prepare the data for registration
            $data = $request->only(['name', 'email', 'password']);

            // Call the AuthService to register the user
            $result = $this->authService->register($data);
            $user = $result['user']; // For 'register_admin'
            $user->assignRole('admin');

            // Check if registration was successful
            if ($result['status'] === 'success') {
                return $this->successResponse(null, 'admin registration successful. Welcome!');
            } else {
                return $this->errorResponse('admin registration failed. Please try again.');
            }
        } catch (ValidationFailedException $e) {
            $errors = $e->errors();

              // Check if the error is related to the unique email validation
        if (isset($errors['email'])) {
            return $this->errorResponse('Email already exists. Please use a different email.', 422, $errors);
        }
            // Catch validation errors and return them as a structured response
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred during registration.', 500, $e->getMessage());
        }
    }

    public function login(loginRequest $request)
    {
        try {
            // Prepare the data for login
            $data = $request->only(['email', 'password']);

            // Call the AuthService to log in the user
            $result = $this->authService->login($data);

            // Check if login was successful
            if ($result['status'] === 'success') {
                return $this->successResponse($result, 'Login successful. Welcome!');
            } else {
                return $this->errorResponse('Login failed. Please check your credentials and try again.');
            }
        } catch (ValidationException $e) {
            // Catch validation errors and return them as a structured response
            return $this->errorResponse('Validation failed', 422, $e->errors());
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred during login.', 500, $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            // Check if the user is authenticated (token is valid)
            if (!$user = Auth::user()) {
                return $this->errorResponse('Token is invalid or expired.', 401);
            }

            // Revoke the user's token (logout)
            $user->token()->revoke();
            return $this->successResponse(null, 'Logout successful.');
        } catch (Exception $e) {
            return $this->errorResponse('An error occurred during logout.', 500, $e->getMessage());
        }
    }

}
