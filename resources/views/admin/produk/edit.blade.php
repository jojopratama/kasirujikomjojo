@extends('admin.template.master')

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
          <h3 class="card-title">{{ $title }}</h3>
          <a href="{{ route('produk.index') }}" class="btn btn-sm btn-warning float-right">Kembali</a>
          <div id="error-container" style="display:none">
            <div class="alert alert-danger">
              <p id="error-message"></p>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form id="form-update-produk" method="POST">
            <label for="" class="mt-1">Nama Produk</label>
            <input type="text" name="NamaProduk" value="{{ $produk->NamaProduk }}" class="form-control" required>
            <label for="" class="mt-1">Harga</label>
            <input type="text" name="Harga" value="{{ $produk->Harga }}" class="form-control" required>
            <label for="" class="mt-1">Stok</label>
            <input type="text" name="Stok" value="{{ $produk->Stok }}" class="form-control" required>
            <button class="btn btn-warning mt-3" type="submit">Update</button>
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
    $(document).on('submit', '#form-update-produk', function (e) {
    e.preventDefault();

    var dataForm = $(this).serialize() + "&_token={{ csrf_token() }}" + "&id={{ $produk->id }}";

    $.ajax({
      method: "PUT",
      url: "{{ route('produk.update', ':id') }}".replace(':id', {{ $produk->id }}),
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
            window.location.href = "{{ route('produk.index') }}";
          }
        })
      },
      error: function(xhr) {
        console.error(xhr.responseJSON);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: xhr.responseJSON.message || "Terjadi kesalahan saat menyimpan produk.",
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
