<?php

namespace App\Core;

use App\Auth\Csrf;
use Framework\Core\BaseController;
use Framework\Http\Request;
use Framework\Http\Responses\Response;

abstract class SecureController extends BaseController
{
    public function authorize(Request $request, string $action): bool
    {
        // 1. CSRF Check for POST/PUT/DELETE
        if ($request->isPost()) { // Framework Request currently mainly supports isPost() helper
            $token = $request->value('csrf_token');
            if (!Csrf::verifyToken($token)) {
                // For AJAX, might want JSON response, but simple die/exception is safer default
                die("CSRF Validation Failed");
            }
        }
        
        // 2. Default login check (optional, but good practice here if most controllers are protected)
        // Leaving it to subclass to implement strict auth checks via parent::authorize() or override
        
        return true;
    }
}
