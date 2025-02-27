@extends('admin.template.master')

@section('css')

    
@endsection
@section('content')
    


 

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">{{ $title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
              <li class="breadcrumb-item active">{{ $subtitle }}</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $title }}</h3>
                <a href="{{ route('produk.index') }}" class="btn btn-sm btn-warning float-right">Kembali</a>
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
              <form id="form-create-produk" method="post">
                @csrf
                <label for="">Nama Produk</label>
                <input type="text" name="NamaProduk" class="form-control" required>
                <label for="">Harga</label>
                <input type="number" name="Harga" class="form-control" required>
                <label for="">Stok</label>
                <input type="number" name="Stok" class="form-control" required>
                <button class="btn btn-primary mt-2 " type="submit">Submit</button>
              </form>


            </div>
        </div> 
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  @endsection

@section('js')
  <script>
    $(document).ready(function(){
      $("#form-create-produk").submit(function(e){
        e.preventDefault();
  
        // Ambil data form
        var dataForm = $(this).serialize();
  
        // Kirim AJAX request
        $.ajax({
          type: "POST",
          url: "{{ route('produk.store') }}",
          data: dataForm,
          dataType: "json",
          success: function(data){
            Swal.fire({
              icon: 'success',
              title: 'Success',
              text: data.message || "Produk berhasil disimpan!",
              confirmButtonText: 'Ok'
            }).then(() => {
              // Reset form setelah submit berhasil
              $('#form-create-produk')[0].reset();
            });
          },
          error: function(xhr){
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: xhr.responseJSON?.message || "Terjadi kesalahan saat menyimpan produk.",
              confirmButtonText: 'Ok'
            });
          }
        });
      });
    });
  </script>
@endsection
