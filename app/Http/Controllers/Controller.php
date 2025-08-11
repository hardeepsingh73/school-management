<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * Base Controller Class
 *
 * All application controllers should extend this abstract class
 * to share common functionality across the entire application.
 *
 * This controller:
 *  - Uses Laravel's `AuthorizesRequests` trait to provide authorization
 *    helper methods like `authorize()` for policy checks.
 *  - Serves as a parent class for all your other controllers,
 *    so shared middleware, helper traits, or common methods can be added here.
 *
 * Example:
 *   class UserController extends Controller {
 *       // Inherits 'authorize()' and other shared behavior
 *   }
 */
abstract class Controller
{
    use AuthorizesRequests;

    // Add any shared helper methods, middleware definitions,
    // or common controller logic here in future.
}
