<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    protected function successResponse($data = null, string $message = 'Success', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function errorResponse(string $message = 'Error', int $status = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }

    /**
     * Resolve the authenticated User from the Authorization: Bearer token header.
     */
    protected function getAuthenticatedUser(Request $request): ?User
    {
        $header = $request->header('Authorization');
        if (!$header || !str_starts_with($header, 'Bearer ')) {
            return null;
        }

        $token = substr($header, 7);
        try {
            $payload = json_decode(base64_decode($token), true);
            if (!$payload || !isset($payload['user_id'], $payload['time'], $payload['sig'])) {
                return null;
            }

            $expectedSig = hash_hmac('sha256', $payload['user_id'] . '|' . $payload['time'], config('app.key') ?: 'tevda_secret_key');
            if (!hash_equals($expectedSig, $payload['sig'])) {
                return null;
            }

            // Token validity: 30 days
            if (time() - $payload['time'] > (30 * 86400)) {
                return null;
            }

            return User::find($payload['user_id']);
        } catch (\Exception $e) {
            return null;
        }
    }
}

