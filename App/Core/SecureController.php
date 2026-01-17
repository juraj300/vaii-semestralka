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
                if ($request->isAjax() || $request->wantsJson()) {
                    header('Content-Type: application/json');
                    http_response_code(403);
                    echo json_encode(['status' => 'error', 'message' => 'CSRF Token Invalid']);
                    exit;
                }
                die("CSRF Validation Failed");
            }
        }
        
        // 2. Default login check (optional, but good practice here if most controllers are protected)
        // Leaving it to subclass to implement strict auth checks via parent::authorize() or override
        
        return true;
    }
}
