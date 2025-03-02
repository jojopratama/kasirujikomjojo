@extends('admin.template.master')

@section('title', $subtitle)

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
            <h3 class="card-title">{{ $subtitle }}</h3>
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
                  <th>Nama Produk</th>
                  <th>Jumlah Ditambahkan</th>
                  <th>Ditambahkan Oleh</th>
                  <th>Tanggal Penambahan</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($produks as $produk)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $produk->NamaProduk }}</td>
                    <td>{{ number($produk->JumlahProduk) }}</td>
                    <td>{{ $produk->name }}</td>
                    <td>{{ date_formater($produk->created_at) }}</td>
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
    let dt_buttons = [
      {
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
    $(() => {
      $("#example1").DataTable({
        language: { search: `<i class="fas fa-search"></i>`},
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: dt_buttons
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    });
  </script>
@endsection
