@extends('layouts.master')
@push('styles')
@endpush

@section('konten')
<div class="page-content">
    <div class="container-fluid">

        <!-- Judul halaman -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Profil</a></li>
                        <li class="breadcrumb-item active">
                            {{ ($data) ? ucwords($data->info_name) : '' }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- End judul -->

        @include('layouts.alert')

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-start gap-3 flex">
                            <h4 class="card-title">
                                <span class="text-muted">
                                    {{ ($data) ? ucwords($data->info_name) : '' }}
                                </span>
                            </h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <form action="{{ ($data) ? route('update_info_public.update', encrypt($data->id)) : '#' }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="info_code" value="{{ ($data) ? $data->info_code : '' }}">

                            <div class="row">

                                <!-- Nama -->
                                <div class="col-lg-12">
                                    <div class="mb-3">
                                        <label class="form-label" for="info_name">Judul</label><label style="color: darkred">*</label>
                                        <input type="text" class="form-control" id="info_name" name="info_name" value="{{ ($data) ? $data->info_name : '' }}" placeholder="Masukkan judul..." readonly required>
                                    </div>
                                </div>

                                @if($data && ($data->info_code === 'hymne' || $data->info_code === 'mars'))
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label" for="video_file">Upload Video</label>
                                    <input type="file" class="form-control" id="video_file" name="video_file" accept="video/mp4,video/webm">
                                    <video id="previewVideo" controls width="400" class="mt-2 rounded" style="display: none;"></video>

                                    @if($data->video !== null)
                                        <label class="form-label mt-4">Video Sekarang</label> <br>
                                        <video id="previewVideoOld" controls class="rounded" width="400" style="max-height: 200px;">
                                            <source src="{{ asset($data->video) }}" type="video/mp4">
                                            <source src="{{ asset($data->video) }}" type="video/webm">
                                            Browser Anda tidak mendukung video.
                                        </video>
                                    @endif

                                </div>

                                <div class="col-lg-12 mb-3">
                                    <label class="form-label" for="audio_file">Upload Audio</label>
                                    <input type="file" class="form-control" id="audio_file" name="audio_file" accept="audio/mpeg,audio/mp3,audio/wav">
                                    <audio id="previewAudio" controls class="mt-2" style="width: 100%; display: none;"></audio>
                                    @if($data->audio !== null)
                                        <label class="form-label mt-4">Audio Sekarang</label>
                                        <audio controls style="width: 100%;" class="mt-2">
                                            <source src="{{ asset($data->audio) }}" type="audio/mpeg">
                                            <source src="{{ asset($data->audio) }}" type="audio/mp3">
                                            <source src="{{ asset($data->audio) }}" type="audio/wav">
                                        </audio>
                                    @endif

                                </div>

                                <script>
                                    function toggleJenis(jenis) {
                                        document.getElementById("interaktif").style.display = jenis === "interaktif" ? "block" : "none";
                                        document.getElementById("video").style.display = jenis === "video" ? "block" : "none";
                                        document.getElementById("audio").style.display = jenis === "audio" ? "block" : "none";
                                    }
                                    // Preview video baru
                                    document.getElementById("video_file").addEventListener("change", function (event) {
                                        const file = event.target.files[0];
                                        const videoPreview = document.getElementById("previewVideo");

                                        if (file) {
                                            const url = URL.createObjectURL(file);
                                            videoPreview.src = url;
                                            videoPreview.style.display = "block";
                                        }
                                    });

                                    // Preview audio baru
                                    document.getElementById("audio_file").addEventListener("change", function (event) {
                                        const file = event.target.files[0];
                                        const audioPreview = document.getElementById("previewAudio");

                                        if (file) {
                                            const url = URL.createObjectURL(file);
                                            audioPreview.src = url;
                                            audioPreview.style.display = "block";
                                        }
                                    });
                                </script>

                                @endif


                                <!-- Konten -->
                                <div class="col-lg-12 mb-3">
                                    <label class="form-label">Konten</label><label style="color: darkred">*</label>
                                    <textarea id="info_value" name="info_value">{{ ($data) ? $data->info_value : '' }}</textarea>
                                </div>

                                <!-- CKEditor -->
                                <script>
                                    CKEDITOR.replace('info_value', {
                                        versionCheck: false,
                                        extraPlugins: 'autogrow',
                                        autoGrow_onStartup: true,
                                        autoGrow_minHeight: 500,
                                        autoGrow_maxHeight: 1000,
                                        autoGrow_bottomSpace: 10,
                                        toolbar: [
                                            { name: 'clipboard', groups: ['clipboard', 'undo'], items: ['Cut', 'Copy', 'Paste', '-', 'Undo', 'Redo'] },
                                            { name: 'editing', groups: ['find', 'selection', 'spellchecker'], items: ['Find', 'Replace'] },
                                            { name: 'basicstyles', groups: ['basicstyles', 'cleanup'], items: ['Bold', 'Italic', 'Underline', '-', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'] },
                                            { name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi'], items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
                                            { name: 'links', items: ['Link', 'Unlink'] },
                                            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar'] },
                                            { name: 'styles', items: ['Styles', 'Format', 'Font', 'FontSize'] },
                                            { name: 'colors', items: ['TextColor', 'BGColor'] },
                                            { name: 'others', items: ['-'] },
                                        ]
                                    });
                                </script>
                            </div>

                            <!-- Tombol Simpan -->
                            <button class="btn btn-primary float-end me-4 mt-2" type="submit">Perbarui</button>
                        </form>
                    </div>
                </div>
                <!-- Akhir card -->
            </div> <!-- End col -->
        </div><!-- End row -->
    </div><!-- End container-fluid -->
</div>
@endsection

@push('scripts')
@endpush
