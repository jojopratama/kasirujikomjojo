@extends('admin.template.master')

@section('css')
 <!-- DataTables -->
 <link rel="stylesheet" href="{{asset ('')}}plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
 <link rel="stylesheet" href="{{asset ('')}}plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
 <link rel="stylesheet" href="{{asset ('')}}plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Modal -->
<div class="modal fade" id="modalTambahStok" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="staticBackdropLabel">Tambah Stok</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="form-tambah-stok" method="post">
        @csrf
      <div class="modal-body">
        <input type="hidden" name="id_produk" id="id_produk">
        <label for="">Jumlah Stok</label>
        <input type="number" name="stok" id="nilaiTambahStok" class="form-control" required>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Submit</button>
      </div>
    </form>
    </div>
  </div>
</div>
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
                <a href="{{ route('produk.create') }}" class="btn btn-sm btn-primary float-right">Tambah</a>
                @if (session()->has('success'))
                <div class="alert alert-success">
                  {{ session('success') }}
                </div>
                  
                @endif
            </div>
            <div class="card-body">
                <table id="example1" class="table table-bordered table-striped">
<thead>
    <tr>
        <th>No</th>
        <th>Produk</th>
        <th>Harga</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
</thead>
<tbody>
    @foreach ($produks as $produk)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $produk->NamaProduk }}</td>
            <td>{{ rupiah($produk->Harga) }}</td>
            <td>{{ $produk->Stok }}</td>
            <td>
                <form id="form-delete-produk" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <a href="{{ route('produk.edit',$produk->id) }}"
                    class="btn btn-sm btn-primary">Edit</a>
                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                   <!-- Button trigger modal -->
<button type="button" class="btn btn-sm btn-warning" id="btnTambahStok" data-toggle="modal" data-target="#modalTambahStok" data-id_produk="{{ $produk->id }}">
 Tambah Stok
</button>


            
                </form>
        </tr>
    @endforeach
</tbody>
</table>
            </div>
        </div> 
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  @endsection

@section('js')

<!-- DataTables  & Plugins -->
<script src="{{asset ('')}}plugins/datatables/jquery.dataTables.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="{{asset ('')}}plugins/jszip/jszip.min.js"></script>
<script src="{{asset ('')}}plugins/pdfmake/pdfmake.min.js"></script>
<script src="{{asset ('')}}plugins/pdfmake/vfs_fonts.js"></script>
<script src="{{asset ('')}}plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="{{asset ('')}}plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script>
    $(function () {
      $("#example1").DataTable({
        "responsive": true, "lengthChange": false, "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  </script>

<script>
  // Mengisi ID produk ke dalam modal tambah stok
  $(document).on('click', '#btnTambahStok', function () {
      let id_produk = $(this).data('id_produk');
      $('#id_produk').val(id_produk);
  });

  // Handle form tambah stok
  $(document).on('submit', '#form-tambah-stok', function (e) {
      e.preventDefault();

      let dataForm = $(this).serialize();
      let id_produk = $('#id_produk').val();

      $.ajax({
          type: "PUT",
          url: "{{ route('produk.tambahStok', ':id') }}".replace(':id', id_produk),
          data: dataForm,
          dataType: "json",
          success: function (data) {
              Swal.fire({
                  icon: 'success',
                  title: 'Sukses',
                  text: data.message,
                  confirmButtonText: 'Ok'
              }).then((result) => {
                  if (result.isConfirmed) {
                      window.location.href = "{{ route('produk.index') }}";
                  }
              });
              $('#modalTambahStok').modal('hide');
              $('#form-tambah-stok')[0].reset();
          },
          error: function (xhr) {
              Swal.fire({
                  icon: 'error',
                  title: 'Error',
                  text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan',
                  confirmButtonText: 'Ok'
              });
          }
      });
  });

  // Konfirmasi sebelum hapus produk
  $(document).on('submit', '#form-delete-produk', function (e) {
      e.preventDefault();
      let form = this;

      Swal.fire({
          title: 'Apakah Anda Yakin?',
          text: "Data tidak akan bisa kembali",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Hapus Data Ini!'
      }).then((result) => {
          if (result.isConfirmed) {
              form.submit();
          }
      });
  });
</script>


  </script>
@endsection
  