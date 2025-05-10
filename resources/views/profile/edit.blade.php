@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning">
            <h5 class="mb-0">Edit Profil</h5>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ url('profile/update') }}" method="POST" id="form-edit" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
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
                    
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $user->name) }}" 
                                   required>
                            <small id="error-name" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="foto" class="form-label">Foto Profil</label>
                            <input type="file" 
                                   class="form-control @error('foto') is-invalid @enderror" 
                                   id="foto" 
                                   name="foto" 
                                   accept="image/*">
                            <small class="text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB</small>
                            <small id="error-foto" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" disabled>
                            <small class="text-muted">Email tidak dapat diubah</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>
                        </div>

                        <hr>

                        <h6>Ubah Password</h6>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Password Saat Ini</label>
                            <input type="password" 
                                   class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" 
                                   name="current_password">
                            <small id="error-current_password" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">Password Baru</label>
                            <input type="password" 
                                   class="form-control @error('new_password') is-invalid @enderror" 
                                   id="new_password" 
                                   name="new_password">
                            <small id="error-new_password" class="error-text form-text text-danger"></small>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" 
                                   class="form-control" 
                                   id="new_password_confirmation" 
                                   name="new_password_confirmation">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            <a href="{{ url('profile/') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $("#form-edit").validate({
        rules: {
            name: {
                required: true,
                minlength: 3,
                maxlength: 100
            },
            foto: {
                extension: "jpg|jpeg|png",
                filesize: 2048 // 2MB in KB
            },
            current_password: {
                required: function() {
                    return $("#new_password").val().length > 0;
                }
            },
            new_password: {
                minlength: 8,
                required: function() {
                    return $("#current_password").val().length > 0;
                }
            },
            new_password_confirmation: {
                equalTo: "#new_password"
            }
        },
        messages: {
            foto: {
                extension: "Format file harus jpg, jpeg, atau png",
                filesize: "Ukuran file maksimal 2MB"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);
            
            $.ajax({
                url: form.action,
                type: form.method,
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        }).then(function() {
                            window.location.href = "{{ route('profile.index') }}";
                        });
                    } else {
                        $('.error-text').text('');
                        $.each(response.msgField, function(prefix, val) {
                            $('#error-' + prefix).text(val[0]);
                        });
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: response.message
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Terjadi kesalahan pada server'
                    });
                }
            });
            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
@endsection
