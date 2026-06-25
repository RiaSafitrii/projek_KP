@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <!-- Judul Halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Daftar Pengguna</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Konfigurasi</a></li>
                            <li class="breadcrumb-item active">Kelola Pengguna</li>
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
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="text-center">No</th>
                                    <th class="text-center">Nama</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $no = 1; @endphp
                                @foreach($operators as $data)
                                <tr>
                                    <td class="text-center">{{ $no++ }}</td>
                                    <td>{{ $data->name }}</td>

                                    <td class="text-center">
                                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#edit-user{{ $data->id }}"><i class="mdi mdi-table-headers-eye label-icon"></i> Lihat</button>
                                    </td>
                                </tr>


                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@foreach($operators as $ope)
{{-- MODAL --}}
<div class="left-align truncate-text">
    {{-- Modal Edit User --}}
    <div class="modal fade" id="edit-user{{ $ope->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-top modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Data Navigasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">

                        <form action="{{ route('msthaknavigasi.store', encrypt($ope->id)) }}" method="POST" class="mb-4">
                            @csrf
                            <div class="mb-3">
                                <label for="navigasi_id" class="form-label">Navigasi</label><label style="color: darkred">*</label>
                                <select class="form-control" name="navigasi_id" id="navigasi_id" required>
                                    <option value="" disabled selected>-- Pilih Navigasi --</option>
                                    @foreach($navigasi as $nav)
                                        <option value="{{$nav->id}}">{{$nav->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-sm btn-primary float-right">Submit</button>
                            </div>
                        </form>
                        <hr>
                        {{-- tempat untuk navigationnya, pakai tabel biasa aja --}}
                        <h6>Daftar Navigasi:</h6>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Navigasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $navNo = 1; @endphp
                                @forelse ($ope->navigations as $nav)
                                    <tr>
                                        <td>{{ $navNo++ }}</td>
                                        <td>{{ $nav->name }}</td>
                                        <td>
                                            <form action="{{ route('msthaknavigasi.delete', encrypt($nav->pivot->id)) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-sm btn-danger mt-1">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="2" class="text-center">Tidak ada navigasi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>

            </div>
        </div>
    </div>

</div>

@endforeach

@endsection



