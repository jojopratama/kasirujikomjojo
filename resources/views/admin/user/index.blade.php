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
            @if (session()->has('success'))
              <div class="alert alert-success">
                {{ session('success') }}
              </div>
            @endif
            @if (session()->has('error'))
              <div class="alert alert-danger">
                {{ session('error') }}
              </div>
            @endif
            <h3 class="card-title">{{ $title }}</h3>
            <a href="{{ route('user.create') }}" class="btn btn-sm btn-primary float-right"><i class="fas fa-plus"></i> Tambah</a>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th>Nama</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>createdAt</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($users as $user)
                  <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{!! badge_role($user->role) !!}</td>
                    <td>{{ date_formater($user->created_at) }}</td>
                    <td>
                      <form id="form-delete-user" action="{{ route('user.destroy', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <a href="{{ route('user.edit', $user->id) }}" class="btn btn-sm btn-primary">Edit</a>
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
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
        order: [[3, 'asc']],
        responsive: true,
        lengthChange: false,
        autoWidth: false,
        buttons: dt_buttons
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

      $(document).on('submit', '#form-delete-user', function(e) {
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
    });
  </script>
@endsection
