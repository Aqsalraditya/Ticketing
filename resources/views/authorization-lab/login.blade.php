@extends('layouts.app')

@section('title', 'Test Login - Lab Otorisasi')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-dark text-white fw-bold">
                    <i class="bi bi-box-arrow-in-right"></i> Kredensial Uji Coba
                </div>
                <div class="card-body">
                    <p>Gunakan akun-akun di bawah ini untuk mencoba login dan melihat perbedaan akses menu di Sidebar berdasarkan Role mereka.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Role</th>
                                    <th>Email</th>
                                    <th>Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-danger">Admin</span></td>
                                    <td><code>admin@wikrama.sch.id</code></td>
                                    <td><code>password</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Staff</span></td>
                                    <td><code>staff@wikrama.sch.id</code></td>
                                    <td><code>password</code></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-secondary">User</span></td>
                                    <td><code>budi@student.wikrama.sch.id</code></td>
                                    <td><code>password</code></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" class="btn btn-success btn-lg px-5">
                            <i class="bi bi-shield-lock"></i> Menuju Halaman Login Utama
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection