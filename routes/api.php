<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\ConfigController;
use App\Http\Controllers\Api\V1\MemberController;
use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\PaymentController;
use App\Http\Controllers\Api\V1\AcademyController;
use App\Http\Controllers\Api\V1\OpportunityController;
use App\Http\Controllers\Api\V1\ComplaintController;
use App\Http\Controllers\Api\V1\NotificationController;

/*
|--------------------------------------------------------------------------
| TEVDA Mobile API Routes - Version 1 (v1)
|--------------------------------------------------------------------------
| Official REST API routes for TEVDA Mobile Application & Member Portal.
*/

Route::prefix('v1')->group(function () {

    // 1. Authentication & Onboarding
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/register-account', [AuthController::class, 'registerAccount']);
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });

    // 2. System Configurations, Fees & Geography
    Route::prefix('config')->group(function () {
        Route::get('/fees', [ConfigController::class, 'fees']);
        Route::get('/categories', [ConfigController::class, 'categories']);
        Route::get('/regions', [ConfigController::class, 'regions']);
        Route::get('/districts/{regionId}', [ConfigController::class, 'districts']);
        Route::get('/wards/{districtId}', [ConfigController::class, 'wards']);
    });

    // 3. Member, Application Onboarding & Smart ID
    Route::prefix('member')->group(function () {
        Route::get('/profile', [MemberController::class, 'profile']);
        Route::put('/profile', [MemberController::class, 'updateProfile']);
        Route::get('/application/status', [MemberController::class, 'applicationStatus']);
        Route::get('/application/draft', [MemberController::class, 'getDraft']);
        Route::post('/application/save-draft', [MemberController::class, 'saveDraft']);
        Route::post('/application/submit', [MemberController::class, 'submitApplication']);
        Route::get('/documents', [MemberController::class, 'getDocuments']);
        Route::delete('/documents/{id}', [MemberController::class, 'deleteDocument']);
        Route::post('/upload-document', [MemberController::class, 'uploadDocument']);
        Route::get('/smart-id', [MemberController::class, 'smartId']);
        Route::get('/certificate', [MemberController::class, 'certificate']);
    });

    // 4. Vehicles Management
    Route::prefix('vehicles')->group(function () {
        Route::get('/', [VehicleController::class, 'index']);
        Route::post('/', [VehicleController::class, 'store']);
        Route::get('/{id}', [VehicleController::class, 'show']);
        Route::put('/{id}', [VehicleController::class, 'update']);
        Route::delete('/{id}', [VehicleController::class, 'destroy']);
    });

    // 5. Payment Management
    Route::prefix('payments')->group(function () {
        Route::get('/history', [PaymentController::class, 'history']);
        Route::post('/generate-control-number', [PaymentController::class, 'generateControlNumber']);
        Route::post('/submit-reference', [PaymentController::class, 'submitReference']);
        Route::get('/verify/{reference}', [PaymentController::class, 'verifyStatus']);
        Route::get('/receipt/{id}', [PaymentController::class, 'receipt']);
    });

    // 6. TEVDA Academy (LMS)
    Route::prefix('academy')->group(function () {
        Route::get('/courses', [AcademyController::class, 'courses']);
        Route::get('/courses/{id}', [AcademyController::class, 'show']);
        Route::post('/enroll', [AcademyController::class, 'enroll']);
        Route::get('/my-progress', [AcademyController::class, 'myProgress']);
        Route::post('/submit-assessment', [AcademyController::class, 'submitAssessment']);
    });

    // 7. Opportunities & Directory
    Route::prefix('opportunities')->group(function () {
        Route::get('/', [OpportunityController::class, 'index']);
        Route::get('/{id}', [OpportunityController::class, 'show']);
        Route::post('/{id}/apply', [OpportunityController::class, 'apply']);
        Route::get('/my/applications', [OpportunityController::class, 'myApplications']);
    });

    // 8. Complaints & Whistleblower Safety (Zero PII leak for anonymous)
    Route::prefix('complaints')->group(function () {
        Route::post('/', [ComplaintController::class, 'store']);
        Route::get('/track/{ticketNumber}', [ComplaintController::class, 'track']);
        Route::get('/my', [ComplaintController::class, 'myComplaints']);
    });

    // 9. Notifications Center
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('/{id}/read', [NotificationController::class, 'markRead']);
        Route::put('/read-all', [NotificationController::class, 'markAllRead']);
    });

});
