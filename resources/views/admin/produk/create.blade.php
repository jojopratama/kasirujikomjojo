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
          <a href="{{ route('produk.index') }}" class="btn btn-sm btn-warning float-right"><i class="fas fa-arrow-left"></i> Kembali</a>
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
          <form id="form-create-produk" method="POST">
            @csrf
            <div class="form-group">
              <label for="" class="">Nama Produk</label>
              <input type="text" name="NamaProduk" id="NamaProduk" class="form-control" placeholder="Masukan Nama" required>
            </div>
            <div class="form-group">
              <label for="" class="">Harga</label>
              <input type="text" name="Harga" class="form-control" placeholder="Masukan Harga" required>
            </div>
            <div class="form-group">
              <label for="" class="">Stok</label>
              <input type="text" name="Stok" class="form-control" placeholder="Masukan Stok" required>
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
    $(document).on('submit', '#form-create-produk', function (e) {
        e.preventDefault();

        var dataForm = $(this).serialize();

        $.ajax({
          method: "POST",
          url: "{{ route('produk.store') }}",
          data: dataForm,
          dataType: "json",
          success: function(data) {
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || "Produk berhasil disimpan!",
              confirmButtonText: 'Ok'
            }).then((result) => {
              $('#form-create-produk')[0].reset();

              if (result.isConfirmed) {
                window.location.href = "{{ route('produk.index') }}";
              }
            });
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Whoops!',
              text: xhr.responseJSON?.message || "Terjadi kesalahan saat menyimpan produk.",
              confirmButtonText: 'Ok'
            });
          }
      });
    });

    Mola.inputNominalRupiah('[name="Harga"]')
    Mola.inputNumber('[name="Stok"]')
  });
</script>
@endsection
