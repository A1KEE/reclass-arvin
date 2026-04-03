@extends('layouts.admin')

@section('content')

<style>
    .folder-card {
        border-radius: 10px;
        transition: 0.2s;
        font-size: 12px;
    }

    .folder-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(0,0,0,0.10);
    }

    .folder-header {
        font-size: 12px;
        padding: 8px;
    }

    .folder-body {
        padding: 8px;
        font-size: 11px;
    }

    .folder-footer {
        padding: 6px;
    }

    .file-line {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>

<div class="container-fluid">

    <h5 class="mb-3">@section('page-title')
📁 Application Files
@endsection</h5>

    <div class="row">

        @foreach($folders as $folder)

            <!-- ✅ EXACT 4 PER ROW -->
            <div class="col-lg-3 col-md-6 col-sm-12 mb-3">

                <div class="card folder-card h-100 shadow-sm">

                    <!-- HEADER -->
                    <div class="card-header bg-dark text-white folder-header">
                        📂 {{ $folder['folder'] }}
                    </div>

                    <!-- BODY -->
                    <div class="card-body folder-body">

                        <div class="text-muted mb-2">
                            {{ count($folder['files']) }} files
                        </div>

                        @foreach(array_slice($folder['files'], 0, 4) as $file)

                            <div class="file-line">
                                📄 {{ $file }}
                            </div>

                        @endforeach

                        @if(count($folder['files']) > 4)
                            <div class="text-muted">
                                +{{ count($folder['files']) - 4 }} more
                            </div>
                        @endif

                    </div>

                    <!-- FOOTER -->
                    <div class="card-footer bg-white folder-footer d-flex justify-content-between">

                        <a href="{{ route('admin.files.show', $folder['folder']) }}"
                           class="btn btn-primary btn-sm">
                            Open File
                        </a>

                       <a href="{{ route('admin.files.download', $folder['folder']) }}"
                        class="btn btn-success btn-sm">
                            Download ZIP
                        </a>

                    </div>

                </div>

            </div>

        @endforeach

    </div>

</div>

@endsection