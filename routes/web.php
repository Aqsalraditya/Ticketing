<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DemoBladeController;
use App\Http\Controllers\XSSLabController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\RegisterController;
use App\Http\Controllers\VulnerableAuth\VulnerableLoginController;
use App\Http\Controllers\VulnerableAuth\VulnerableRegisterController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SecurityTestController;
use App\Http\Controllers\ValidationLabController;
use App\Http\Controllers\CsrfLabController;
use App\Http\Controllers\SqliLabController;
use App\Http\Controllers\AdminController;

// Controller Lab BAC / IDOR
use App\Http\Controllers\Lab\SecureController;
use App\Http\Controllers\Lab\VulnerableController;

// Controller File Upload Lab
use App\Http\Controllers\FileUpload\SecureUploadController;
use App\Http\Controllers\FileUpload\VulnerableUploadController;

// ============================================
// BASIC ROUTES
// ============================================
Route::get('/', function () {
    return view('auth.login');
});

Route::get('/api/status', function () {
    return response()->json([
        'status' => 'OK',
        'message' => 'Server berjalan dengan baik',
        'time' => now()->toDateTimeString(),
    ]);
});

// ============================================
// LABS GROUP (XSS, SQLi, CSRF, VALIDATION)
// ============================================

// Demo Blade
Route::prefix('demo-blade')->name('demo-blade.')->group(function () {
    Route::get('/', [DemoBladeController::class, 'index'])->name('index');
    Route::get('/directives', [DemoBladeController::class, 'directives'])->name('directives');
    Route::get('/components', [DemoBladeController::class, 'components'])->name('components');
    Route::get('/includes', [DemoBladeController::class, 'includes'])->name('includes');
    Route::get('/stacks', [DemoBladeController::class, 'stacks'])->name('stacks');
});

// XSS Lab
Route::prefix('xss-lab')->name('xss-lab.')->group(function () {
    Route::get('/', [XSSLabController::class, 'index'])->name('index');
    Route::post('/reset-comments', [XSSLabController::class, 'resetComments'])->name('reset-comments');
    Route::get('/reflected/vulnerable', [XSSLabController::class, 'reflectedVulnerable'])->name('reflected.vulnerable');
    Route::get('/reflected/secure', [XSSLabController::class, 'reflectedSecure'])->name('reflected.secure');
    Route::get('/stored/vulnerable', [XSSLabController::class, 'storedVulnerable'])->name('stored.vulnerable');
    Route::post('/stored/vulnerable', [XSSLabController::class, 'storedVulnerableStore'])->name('stored.vulnerable.store');
    Route::get('/stored/secure', [XSSLabController::class, 'storedSecure'])->name('stored.secure');
    Route::post('/stored/secure', [XSSLabController::class, 'storedSecureStore'])->name('stored.secure.store');
    Route::get('/dom/vulnerable', [XSSLabController::class, 'domVulnerable'])->name('dom.vulnerable');
    Route::get('/dom/secure', [XSSLabController::class, 'domSecure'])->name('dom.secure');
});

// Validation Lab
Route::prefix('validation-lab')->name('validation-lab.')->group(function () {
    Route::get('/', [ValidationLabController::class, 'index'])->name('index');
    Route::get('/vulnerable', [ValidationLabController::class, 'vulnerableForm'])->name('vulnerable');
    Route::post('/vulnerable', [ValidationLabController::class, 'vulnerableSubmit'])->name('vulnerable.submit');
    Route::post('/vulnerable/clear', [ValidationLabController::class, 'vulnerableClear'])->name('vulnerable.clear');
    Route::get('/secure', [ValidationLabController::class, 'secureForm'])->name('secure');
    Route::post('/secure', [ValidationLabController::class, 'secureSubmit'])->name('secure.submit');
    Route::post('/secure/clear', [ValidationLabController::class, 'secureClear'])->name('secure.clear');
});

// CSRF Lab
Route::prefix('csrf-lab')->name('csrf-lab.')->group(function () {
    Route::get('/', [CsrfLabController::class, 'index'])->name('index');
    Route::get('/how-it-works', [CsrfLabController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/attack-demo', [CsrfLabController::class, 'attackDemo'])->name('attack-demo');
    Route::get('/protection-demo', [CsrfLabController::class, 'protectionDemo'])->name('protection-demo');
    Route::get('/ajax-demo', [CsrfLabController::class, 'ajaxDemo'])->name('ajax-demo');
    Route::post('/secure-transfer', [CsrfLabController::class, 'secureTransfer'])->name('secure-transfer');
    Route::post('/protected-action', [CsrfLabController::class, 'protectedAction'])->name('protected-action');
    Route::post('/ajax-action', [CsrfLabController::class, 'ajaxAction'])->name('ajax-action');
    Route::post('/reset', [CsrfLabController::class, 'resetDemo'])->name('reset');
    Route::post('/vulnerable-transfer', [CsrfLabController::class, 'vulnerableTransfer'])->name('vulnerable-transfer')->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
    Route::post('/protected-transfer', [CsrfLabController::class, 'protectedTransfer'])->name('protected-transfer');
});

// SQL Injection Lab
Route::prefix('sqli-lab')->name('sqli-lab.')->group(function () {
    Route::get('/', [SqliLabController::class, 'index'])->name('index');
    Route::get('/how-it-works', [SqliLabController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/cheatsheet', [SqliLabController::class, 'cheatsheet'])->name('cheatsheet');
    Route::get('/vulnerable-search', [SqliLabController::class, 'vulnerableSearch'])->name('vulnerable-search');
    Route::get('/vulnerable-login', [SqliLabController::class, 'vulnerableLogin'])->name('vulnerable-login');
    Route::post('/vulnerable-login', [SqliLabController::class, 'vulnerableLoginSubmit'])->name('vulnerable-login-submit');
    Route::get('/blind-sqli', [SqliLabController::class, 'blindSqli'])->name('blind-sqli');
    Route::post('/blind-sqli/boolean', [SqliLabController::class, 'blindSqliBooleanCheck'])->name('blind-sqli-boolean');
    Route::post('/blind-sqli/time', [SqliLabController::class, 'blindSqliTimeCheck'])->name('blind-sqli-time');
    Route::get('/secure-search', [SqliLabController::class, 'secureSearch'])->name('secure-search');
    Route::get('/seed-data', [SqliLabController::class, 'seedData'])->name('seed');
    Route::get('/reset-data', [SqliLabController::class, 'resetData'])->name('reset');
});

// ============================================================================
// AUTH LAB & AUTHORIZATION PAGES
// ============================================================================
Route::prefix('auth-lab')->name('auth-lab.')->group(function () {
    Route::get('/', function () { return view('auth-lab.index'); })->name('index');
    Route::get('/comparison', function () { return view('auth-lab.comparison'); })->name('comparison');
});

Route::prefix('authorization-lab')->name('authorization-lab.')->group(function () {
    Route::get('/', function () { return view('authorization-lab.index'); })->name('index');
    Route::get('/login', function () { return view('authorization-lab.login'); })->name('login');
    Route::get('/implementation', function () { return view('authorization-lab.implementation'); })->name('implementation');
});

// ============================================================================
// BAC/IDOR LAB (Broken Access Control)
// ============================================================================
Route::prefix('bac-lab')->name('bac-lab.')->group(function () {
    Route::get('/', function () { return view('bac-lab.index'); })->name('home');
    Route::get('/comparison', function () { return view('bac-lab.comparison'); })->name('comparison');
    Route::get('/vulnerable/login', function () { return view('bac-lab.vulnerable.login'); })->name('vulnerable.login');
    Route::get('/secure/login', function () { return view('bac-lab.secure.login'); })->name('secure.login');
});

Route::middleware('auth')->prefix('bac-lab')->name('bac-lab.')->group(function () {
    Route::prefix('vulnerable')->name('vulnerable.')->group(function () {
        Route::get('/tickets', [VulnerableController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{id}', [VulnerableController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{id}/edit', [VulnerableController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{id}', [VulnerableController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{id}', [VulnerableController::class, 'destroy'])->name('tickets.destroy');
    });
    Route::prefix('secure')->name('secure.')->group(function () {
        Route::resource('tickets', SecureController::class)->parameters(['tickets' => 'ticket']);
    });
});

// ============================================================================
// FILE UPLOAD LAB (Minggu 5 Hari 3 & 4) - NEWLY ADDED
// ============================================================================
Route::prefix('file-upload-lab')->name('file-upload-lab.')->group(function () {
    Route::get('/', function () { return view('file-upload-lab.index'); })->name('index');
    
    Route::get('/overview/{section?}', function ($section = 'logging') {
        $validSections = ['logging', 'upload-basics'];
        if (!in_array($section, $validSections)) $section = 'logging';
        return view('file-upload-lab.overview', compact('section'));
    })->name('overview');

    // Vulnerable Version
    Route::prefix('vulnerable')->name('vulnerable.')->group(function () {
        Route::get('/', [VulnerableUploadController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/level1', [VulnerableUploadController::class, 'level1'])->name('level1');
        Route::match(['get', 'post'], '/level2', [VulnerableUploadController::class, 'level2'])->name('level2');
        Route::match(['get', 'post'], '/level3', [VulnerableUploadController::class, 'level3'])->name('level3');
        Route::match(['get', 'post'], '/level4', [VulnerableUploadController::class, 'level4'])->name('level4');
        Route::match(['get', 'post'], '/level5', [VulnerableUploadController::class, 'level5'])->name('level5');
        Route::get('/files', [VulnerableUploadController::class, 'listFiles'])->name('files');
        Route::delete('/clear', [VulnerableUploadController::class, 'clearUploads'])->name('clear');
    });

    // Secure Version
    Route::prefix('secure')->name('secure.')->group(function () {
        Route::get('/', [SecureUploadController::class, 'index'])->name('index');
        Route::post('/upload', [SecureUploadController::class, 'upload'])->name('upload');
        Route::get('/file/{filename}', [SecureUploadController::class, 'serve'])->name('serve');
        Route::get('/download/{filename}', [SecureUploadController::class, 'download'])->name('download');
        Route::delete('/file/{filename}', [SecureUploadController::class, 'delete'])->name('delete');
        Route::delete('/clear', [SecureUploadController::class, 'clearAll'])->name('clear');
    });
});

// ============================================================================
// TOOLS & TESTING
// ============================================================================
// Security Testing
Route::prefix('security-testing')->name('security-testing.')->group(function () {
    Route::get('/', [SecurityTestController::class, 'index'])->name('index');
    Route::get('/xss', [SecurityTestController::class, 'xssTest'])->name('xss');
    Route::get('/csrf', [SecurityTestController::class, 'csrfTest'])->name('csrf');
    Route::post('/csrf', [SecurityTestController::class, 'csrfTestPost'])->name('csrf.post');
    Route::get('/headers', [SecurityTestController::class, 'headersTest'])->name('headers');
    
    // UBAH BAGIAN INI: Pastikan ->name('audit') agar menjadi 'security-testing.audit'
    Route::get('/audit-checklist', [SecurityTestController::class, 'auditChecklist'])->name('audit');
});

// ============================================================================
// SECURE APP CORE
// ============================================================================
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', function () { return view('auth.dashboard'); })->name('dashboard');
    Route::resource('tickets', TicketController::class);
    Route::patch('/tickets/{ticket}/status', [TicketController::class, 'updateStatus'])->name('tickets.update-status');
    Route::patch('/tickets/{ticket}/assign', [TicketController::class, 'assign'])->name('tickets.assign');
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::put('/comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
});

// ============================================================================
// ADMIN & STAFF AREA
// ============================================================================
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/tickets', [AdminController::class, 'allTickets'])->name('tickets');
    Route::post('/tickets/{ticket}/assign', [AdminController::class, 'assignTicket'])->name('assign-ticket-action');
});

Route::get('/reports', [AdminController::class, 'reports'])->middleware(['auth', 'role:staff,admin'])->name('admin.reports');

// ============================================================================
// VULNERABLE AUTH LAB (DEMO ONLY)
// ============================================================================
Route::prefix('vulnerable')->name('vulnerable.')->group(function () {
    Route::get('/login', [VulnerableLoginController::class, 'create'])->name('login');
    Route::post('/login', [VulnerableLoginController::class, 'store'])->name('login.submit');
    Route::get('/register', [VulnerableRegisterController::class, 'create'])->name('register');
    Route::post('/register', [VulnerableRegisterController::class, 'store'])->name('register.submit');
    Route::get('/dashboard', function () {
        if (!session()->has('vulnerable_user')) return redirect()->route('vulnerable.login');
        return view('vulnerable-auth.dashboard', ['user' => session('vulnerable_user')]);
    })->name('dashboard');
    Route::post('/logout', [VulnerableLoginController::class, 'destroy'])->name('logout');
    Route::get('/show-users', [VulnerableRegisterController::class, 'showUsers'])->name('show-users');
    Route::get('/brute-force-stats', [VulnerableLoginController::class, 'bruteForceStats'])->name('brute-force-stats');
});

Route::get('/error-handling-demo', function () {
    return view('error-handling-demo.index');
})->name('error-handling-demo');