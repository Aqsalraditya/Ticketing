@extends('layouts.app')

@section('title', 'Implementasi RBAC')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4"><i class="bi bi-code-slash"></i> Implementasi Middleware & Gates</h2>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Route Middleware (web.php)</div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded small"><code>// Hanya Admin yang bisa masuk
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/users', [AdminController::class, 'users']);
});

// Admin ATAU Staff yang bisa masuk
Route::get('/reports', [AdminController::class, 'reports'])
    ->middleware(['auth', 'role:staff,admin']);</code></pre>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white">Blade Directives (sidebar.blade.php)</div>
                <div class="card-body">
                    <pre class="bg-light p-3 rounded small"><code>{{ '@' }}canany(['access-admin', 'view-reports'])
    &lt;li class="nav-section"&gt;Management&lt;/li&gt;
    
    {{ '@' }}can('access-admin')
        &lt;li&gt;&lt;a href="..."&gt;Kelola User&lt;/a&gt;&lt;/li&gt;
    {{ '@' }}endcan
    
{{ '@' }}endcanany</code></pre>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection