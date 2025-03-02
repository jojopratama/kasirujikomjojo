@extends('admin.template.master')

@section('title', $subtitle . ' ' . $title)

@section('content')
  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
              <li class="breadcrumb-item active">{{ $subtitle }}</li>
            </ol>
          </div>
        </div>
      </div>
    </div>
    <section class="content">
      <div class="container-fluid">
        <div class="card">
          <div class="card-header">
            @if ($errors->any())
              @foreach ($errors->all() as $error)
                <div class="alert alert-danger" role="alert">
                  {{ $error }}
                </div>
              @endforeach
            @endif
            <h3 class="card-title">{{ $subtitle }} {{ $title }}</h3>
            <a href="{{ route('user.index') }}" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
            <div id="error-container" style="display:none">
              <div class="alert alert-danger">
                <p id="error-message"></p>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form id="form-update-user" method="POST">
              <div class="form-group">
                <label for="" class="">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" class="form-control" placeholder="Masukan Nama Lengkap" required>
              </div>
              <div class="form-group">
                <label for="" class="">Email</label>
                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" placeholder="Masukan Email" required>
              </div>
              <div class="form-group">
                <label for="" class="">Password <span class="font-weight-light">(Kosongkan jika tidak ingin diubah)</span></label>
                <input type="password" name="password" class="form-control" placeholder="Masukan Password">
              </div>
              <div class="form-group">
                <label for="" class="">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="form-control" placeholder="Masukan Konfirmasi Password">
              </div>
              <div class="form-group">
                <label for="" class="">Role</label>
                <select class="form-control" name="role" id="select2-role" >
                  <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                  <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
              </div>
              <button class="btn btn-warning" type="submit"><i class="fas fa-save"></i> Update</button>
            </form>
          </div>
        </div>
      </div>
    </section>
  </div>
@endsection

@section('js')
  <script>
    $(() => {
      $('#select2-role').select2({
        theme: 'bootstrap4'
      });
      
      $(document).on('submit', '#form-update-user', function(e) {
        e.preventDefault();

        var dataForm = $(this).serialize() + "&_token={{ csrf_token() }}" + "&id={{ $user->id }}";

        $.ajax({
          method: "PUT",
          url: "{{ route('user.update', ':id') }}".replace(':id', {{ $user->id }}),
          data: dataForm,
          dataType: "json",
          success: function(data) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || "Produk berhasil disimpan!",
              confirmButtonText: 'Ok'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "{{ route('user.index') }}";
              }
            })
          },
          error: function(xhr) {
            console.error(xhr.responseJSON);
            Swal.fire({
              icon: 'error',
              title: 'Whoops!',
              text: xhr.responseJSON.message || "Terjadi kesalahan saat menyimpan user.",
              confirmButtonText: 'Ok'
            });
            if (xhr.status === 500) {
              Swal.fire({
                icon: 'error',
                title: 'Server Error',
                text: xhr.responseJSON.message || "Internal Server Error.",
                confirmButtonText: 'Ok'
              });
            }
          }
        });
      });

      Mola.inputNominalRupiah('[name="Harga"]')
      Mola.inputNumber('[name="Stok"]')
    });
  </script>
@endsection
