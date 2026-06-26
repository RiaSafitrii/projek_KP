@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Sosial Media</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Konfigurasi</a></li>
                            <li class="breadcrumb-item active">Sosial Media</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        @if(Auth::user()->role == 'admin')
                        {{-- Tambah tombol jika dibutuhkan --}}
                        @endif
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">URL</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($sosialmedia as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td><i class="{{ $data->icon_code }} me-1"></i> {{ $data->name }}</td>
                                    <td>{{ $data->url }}</td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-option{{ $data->id }}">
                                                        <span class="mdi mdi-file-edit"></span> | Ubah
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                <!-- MODAL UBAH -->
                                <div>
                                    <div class="modal fade" id="edit-option{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-top">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Ubah Sosial Media</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                                </div>
                                                <form action="{{ route('mstsosialmedia.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Kode Ikon <span class="text-danger">*</span></label>
                                                                    <div class="row">
                                                                        <div class="col-10">
                                                                            <input type="text" class="form-control" name="icon_code" value="{{ $data->icon_code }}" placeholder="Masukkan kode ikon..." required>
                                                                        </div>
                                                                        <div class="col-2">
                                                                            <a href="{{ route('msticon.index') }}" target="_blank" class="btn btn-primary" data-bs-toggle="tooltip" title="Referensi Ikon">
                                                                                <i class="fas fa-icons"></i>
                                                                            </a>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">Nama</label>
                                                                <input type="text" class="form-control" name="name" value="{{ $data->name }}" readonly>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label">URL</label>
                                                                <input type="url" class="form-control" name="url" value="{{ $data->url }}" placeholder="Masukkan URL...">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                                                        <button type="submit" class="btn btn-primary" id="sb-edit{{ $data->id }}">
                                                            <i class="mdi mdi-update label-icon"></i> Perbarui
                                                        </button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let optionId = "{{ $data->id }}";
                                                        $('#formedit' + optionId).submit(function(e) {
                                                            if (!$(this).valid()) {
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + optionId).prop("disabled", true).html('<i class="mdi mdi-reload label-icon"></i> Mohon tunggu...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
