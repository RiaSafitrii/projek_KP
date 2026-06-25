@extends('layouts.master')

@section('konten')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">List Bidang Pegawai</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Info Pegawai</a></li>
                            <li class="breadcrumb-item active">Manage Bidang</li>
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
                        <button type="button" class="btn btn-primary waves-effect btn-label waves-light" data-bs-toggle="modal" data-bs-target="#add-new"><i class="mdi mdi-tray-plus label-icon"></i> Add New </button>
                        {{-- Modal Add --}}
                        <div class="modal fade" id="add-new" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-top" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Add New </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('mstbidangpegawai.store') }}" id="formadd" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="mb-3">
                                                        <label class="form-label">Name</label><label style="color: darkred">*</label>
                                                        <input class="form-control" name="name" type="text" value="" placeholder="Input Name.." required>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-success waves-effect btn-label waves-light" name="sb"><i class="mdi mdi-tray-plus label-icon"></i>Add</button>
                                        </div>
                                    </form>
                                    <script>
                                        document.getElementById('formadd').addEventListener('submit', function(event) {
                                            if (!this.checkValidity()) {
                                                event.preventDefault(); // Prevent form submission if it's not valid
                                                return false;
                                            }
                                            var submitButton = this.querySelector('button[name="sb"]');
                                            submitButton.disabled = true;
                                            submitButton.innerHTML  = '<i class="mdi mdi-reload label-icon"></i>Please Wait...';
                                            return true; // Allow form submission
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered dt-responsive nowrap w-100" id="datatable">
                            <thead>
                                <tr>
                                    <th class="align-middle text-center">No</th>
                                    <th class="align-middle text-center">Name</th>
                                    <th class="align-middle text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php
                                $no=0;
                            @endphp
                                @foreach($jabatan as $data)
                                @php
                                    $no++;
                                @endphp
                                <tr>
                                    <td>{{ $no }}</td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button id="btnGroupDrop" type="button" class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Aksi <i class="mdi mdi-chevron-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="btnGroupDrop">
                                                <li><a class="dropdown-item drpdwn-wrg" href="#" data-bs-toggle="modal" data-bs-target="#edit-categories{{ $data->id }}"><span class="mdi mdi-file-edit"></span> | Edit</a></li>
                                                <li><a class="dropdown-item drpdwn-dgr" href="#" data-bs-toggle="modal" data-bs-target="#delete-categories{{ $data->id }}"><span class="mdi mdi-delete-alert"></span> | Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>

                                {{-- MODAL --}}
                                <div class="left-align truncate-text">
                                    {{-- Modal Edit Categories --}}
                                    <div class="modal fade" id="edit-categories{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Edit </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstbidangpegawai.update', encrypt($data->id)) }}" id="formedit{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12">
                                                                <div class="mb-3">
                                                                    <label class="form-label">Name</label><label style="color: darkred">*</label>
                                                                    <input class="form-control" name="name" type="text" value="{{$data->name}}" placeholder="Input Name.." required>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary waves-effect btn-label waves-light" id="sb-edit{{ $data->id }}"><i class="mdi mdi-update label-icon"></i>Update</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let panduanId = "{{ $data->id }}";
                                                        $('#formedit' + panduanId).submit(function(e) {
                                                            if (!$('#formedit' + panduanId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-edit' + panduanId).attr("disabled", "disabled");
                                                                $('#sb-edit' + panduanId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
                                                            }
                                                        });
                                                    });
                                                </script>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Delete Categories --}}
                                    <div class="modal fade" id="delete-categories{{ $data->id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">Delete</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('mstbidangpegawai.delete', encrypt($data->id)) }}" id="formdelete{{ $data->id }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('DELETE')
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <p class="text-center">Are You Sure to Delete this?</p>
                                                            <p class="text-center"><b>{{ $data->name }}</b></p>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-danger waves-effect btn-label waves-light" id="sb-delete{{ $data->id }}"><i class="mdi mdi-delete label-icon"></i>Delete</button>
                                                    </div>
                                                </form>
                                                <script>
                                                    $(document).ready(function() {
                                                        let panduanId = "{{ $data->id }}";
                                                        $('#formdelete' + panduanId).submit(function(e) {
                                                            if (!$('#formdelete' + panduanId).valid()){
                                                                e.preventDefault();
                                                            } else {
                                                                $('#sb-delete' + panduanId).attr("disabled", "disabled");
                                                                $('#sb-delete' + panduanId).html('<i class="mdi mdi-reload label-icon"></i>Please Wait...');
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
