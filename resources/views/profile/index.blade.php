@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Profil Pengguna</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                {{-- Foto Profil --}}
                <div class="col-md-3 text-center">
                    @if($user->foto)
                        <img src="{{ asset('storage/profile/' . $user->foto) }}" 
                             class="img-fluid rounded-circle mb-2" 
                             alt="Foto Profil"
                             style="max-width: 200px; height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('images/default-user.jpg') }}" 
                             class="img-fluid rounded-circle mb-2" 
                             alt="Default Foto"
                             style="max-width: 200px; height: 200px; object-fit: cover;">
                    @endif
                </div>

                {{-- Data User --}}
                <div class="col-md-9">
                    <table class="table table-borderless">
                        <tr>
                            <th style="width: 200px;">Nama</th>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <th>Role</th>
                            <td>
                                <span class="badge bg-{{ $user->isAdmin() ? 'danger' : 'info' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th>Tanggal Terdaftar</th>
                            <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <th>Terakhir Diperbarui</th>
                            <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>

                    <div class="d-flex gap-2">
                        <a href="{{ url('profile/edit') }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
