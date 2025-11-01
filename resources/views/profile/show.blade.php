@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="content-wrapper" style="margin-left: 0; padding: 0;">
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0">Edit Profile</h3>
                        </div>
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="card-body py-2">
                                
                                @if(session('success'))
                                    <div class="alert alert-success alert-dismissible mb-2 py-1">
                                        <button type="button" class="close py-1" data-dismiss="alert" aria-hidden="true">×</button>
                                        <i class="icon fas fa-check mr-1"></i>{{ session('success') }}
                                    </div>
                                @endif

                                <div class="form-group mb-2">
                                    <label for="name" class="mb-0"><strong>Nama</strong></label>
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-group mb-2">
                                    <label for="email" class="mb-0"><strong>Email</strong></label>
                                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <hr class="my-2">

                                <h6 class="mb-1"><strong>Ubah Password</strong></h6>
                                <p class="text-muted small mb-2">Kosongkan jika tidak ingin mengubah password</p>

                                <div class="form-group mb-2">
                                    <label for="current_password" class="mb-0">Password Saat Ini</label>
                                    <input type="password" class="form-control form-control-sm @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" 
                                           placeholder="Masukkan password saat ini">
                                    @error('current_password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="password" class="mb-0">Password Baru</label>
                                            <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" 
                                                   id="password" name="password" placeholder="Masukkan password baru">
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-2">
                                            <label for="password_confirmation" class="mb-0">Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control form-control-sm" 
                                                   id="password_confirmation" name="password_confirmation" 
                                                   placeholder="Ulangi password baru">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer py-2">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save mr-1"></i> Update Profile
                                </button>
                                <a href="{{ url('/') }}" class="btn btn-default btn-sm">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header py-2">
                            <h3 class="card-title mb-0">Info Akun</h3>
                        </div>
                        <div class="card-body py-2">
                            <div class="text-center mb-1">
                                <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            </div>
                            
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td style="padding: 0.2rem;"><strong>Nama:</strong></td>
                                    <td style="padding: 0.2rem;">{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0.2rem;"><strong>Email:</strong></td>
                                    <td style="padding: 0.2rem;">{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0.2rem;"><strong>Bergabung:</strong></td>
                                    <td style="padding: 0.2rem;">{{ $user->created_at->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0.2rem;"><strong>Terakhir Update:</strong></td>
                                    <td style="padding: 0.2rem;">{{ $user->updated_at->format('d M Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style>
.content-wrapper {
    margin-left: 0 !important;
    padding: 0 !important;
}
.card {
    margin-bottom: 0.5rem;
}
.form-control-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}
</style>
@endsection