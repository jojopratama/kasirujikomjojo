@extends('admin.template.master')

@section('title', $subtitle . ' ' . $title)

@section('content')
  <div class="modal fade" id="modalTambahStok" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="staticBackdropLabel">Tambah Stok</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form id="form-tambah-stok" method="POST">
          @csrf
          <div class="modal-body">
            <input type="hidden" name="id_produk" id="id_produk">
            <label for="nilaiTambahStok">Jumlah Stok</label>
            <input type="text" name="stok" id="nilaiTambahStok" class="form-control" placeholder="Masukan Stok" required>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </div>
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
            @if (session()->has('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
            <h3 class="card-title">{{ $title }}</h3>
            <a href="{{ route('produk.create') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> Tambah</a>
          </div>
          <div class="card-body">
            <button type="button" class="btn btn-primary mb-1" id="btnCetakLabel">Cetak Label</button>
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>#</th>
                  <th>No</th>
                  <th>Produk</th>
                  <th>Harga</th>
                  <th>Stok</th>
                  <th>createdAt</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($produks as $produk)
                  <tr>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" name="id_produk[]" type="checkbox" value="{{ $produk->id }}" id="id_produk_label">
                      </div>
                    </td>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->NamaProduk }}</td>
                    <td>{{ rupiah($produk->Harga) }}</td>
                    <td>{{ number($produk->Stok) }}</td>
                    <td>{{ date_formater($produk->created_at) }}</td>
                    <td>
                      <form id="form-delete-produk" action="{{ route('produk.destroy', $produk->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <a href="{{ route('produk.edit', $produk->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
      </div>
    </section>
  </div>
@endsection

@section('js')
  <script>
    $(() => {
      let dt_buttons = [{
          extend: 'excelHtml5',
          text: '<i class="far fa-file-excel"></i> Export Ke Excel',
          titleAttr: 'Excel',
          type: 'button',
        },
        {
          extend: 'print',
          text: '<i class="fas fa-print"></i>',
          titleAttr: 'Print',
          type: 'button',
        },
        {
          extend: 'colvis',
          text: 'Column',
          titleAttr: 'Column',
          type: 'button',
        },
      ]

      $("#example1").DataTable({
        language: {
          search: `<i class="fas fa-search"></i>`
        },
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: dt_buttons
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $(document).on('click', '#btnTambahStok', function(e) {
        let id_produk = $(this).data('id_produk');
        $('#id_produk').val(id_produk);
      });

      $(document).on('submit', '#form-tambah-stok', function(e) {
        e.preventDefault();

        let dataForm = $(this).serialize();
        let id_produk = $('#id_produk').val();

        $.ajax({
          type: "PUT",
          url: "{{ route('produk.tambahStok', ':id') }}".replace(':id', id_produk),
          data: dataForm,
          dataType: "json",
          success: function(data) {
            Swal.fire({
              icon: 'success',
              title: 'Sukses',
              text: data.message,
              confirmButtonText: 'OK'
            }).then((result) => {
              if (result.isConfirmed) {
                window.location.href = "{{ route('produk.index') }}";
              }
            });
            $('#modalTambahStok').modal('hide');
            $('#form-tambah-stok')[0].reset();
          },
          error: function(xhr) {
            Swal.fire({
              icon: 'error',
              title: 'Whoops!',
              text: xhr.responseJSON ? xhr.responseJSON.message : 'Terjadi kesalahan',
              confirmButtonText: 'OK'
            });
          },
        });
      });

      $(document).on('submit', '#form-delete-produk', function(e) {
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

      $(document).on('click', '#btnCetakLabel', function() {
        let id_produk = [];
        $('input[name="id_produk[]"]:checked').each(function() {
            id_produk.push($(this).val());
        });

        if (id_produk.length == 0) {
            Swal.fire({
              icon: 'warning',
              title: 'Whooops!',
              text: 'Silakan pilih produk yang ingin dicetak labelnya dengan mencentang checkbox terlebih dahulu.',
              confirmButtonText: 'Ok'
            })
            return;
        }

        $.ajax({
            type: "POST",
            url: "{{ route('produk.cetakLabel') }}",
            data: {
                _token: "{{ csrf_token() }}",
                id_produk: id_produk
            },
            dataType: "json",
            success: function(data) {
                window.open(data.url, '_blank');
            },
            error: function(data) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message,
                    confirmButtonText: 'Ok'
                })
            }
        })
    })

      Mola.inputNumber('[name="stok"]');
    });
  </script>

  <script>
    script>
@endsection
