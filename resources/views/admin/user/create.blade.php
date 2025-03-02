@extends('admin.template.master')

@section('title', $subtitle . ' ' . $title).

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
          <h3 class="card-title">{{$subtitle}} {{ $title }}</h3>
          <a href="{{ route('user.index') }}" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
          @if ($errors->any())
            @foreach ($errors->all() as $error)
              <div class="alert alert-danger" role="alert">
                {{ $error }}
              </div>
            @endforeach
          @endif
          <div id="error-container" style="display:none">
            <div class="alert alert-danger">
              <p id="error-message"></p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form id="form-create-user" method="POST">
            @csrf
            <div class="form-group">
              <label for="" class="">Nama Lengkap</label>
              <input type="text" name="name" id="name" class="form-control" placeholder="Masukan Nama Lengkap" required>
            </div>
            <div class="form-group">
              <label for="" class="">Email</label>
              <input type="email" name="email" class="form-control" placeholder="Masukan Email" required>
            </div>
            <div class="form-group">
              <label for="" class="">Password</label>
              <input type="password" name="password" class="form-control" placeholder="Masukan Password" required>
            </div>
            <div class="form-group">
              <label for="" class="">Konfirmasi Password</label>
              <input type="password" name="password_confirmation" class="form-control" placeholder="Masukan Konfirmasi Password" required>
            </div>
            <div class="form-group">
              <label for="" class="">Role</label>
              <select class="form-control" name="role" id="select2-role" >
                <option value="petugas">Petugas</option>
                <option value="admin">Admin</option>
              </select>
            </div>
            <button class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Submit</button>
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

    $(document).on('submit', '#form-create-user', function (e) {
        e.preventDefault();

        var dataForm = $(this).serialize();

        $.ajax({
          method: "POST",
          url: "{{ route('user.store') }}",
          data: dataForm,
          dataType: "json",
          success: function(data) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || "Produk berhasil disimpan!",
              confirmButtonText: 'Ok'
            }).then((result) => {
              $('#form-create-user')[0].reset();

              if (result.isConfirmed) {
                window.location.href = "{{ route('user.index') }}";
              }
            });
          },
          error: function(xhr) {
            let errorMessages = "Terjadi kesalahan saat menyimpan user.";

            if (xhr.responseJSON?.errors) {
              errorMessages = Object.values(xhr.responseJSON.errors)
                .flat()
                .join("\n");
            }

            Swal.fire({
              icon: 'error',
              title: 'Whoops!',
              text: errorMessages,
              confirmButtonText: 'OK'
            });
          }
      });
    });
  });
</script>
@endsection
