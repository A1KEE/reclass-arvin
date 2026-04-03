@extends('layouts.admin')

@section('page-title','Application Files')

@section('content')

<style>
    .file-box {
        border: 1px solid #e5e5e5;
        border-radius: 10px;
        padding: 10px;
        font-size: 12px;
        background: #fff;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .file-name {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        max-width: 70%;
    }

    .btn-xs {
        font-size: 11px;
        padding: 3px 8px;
    }
</style>

<div class="container-fluid">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-3">

        <h5>📂 {{ $folder }}</h5>

        <a href="{{ route('admin.files.index') }}" class="btn btn-secondary btn-sm">
            ← Back
        </a>

    </div>

    <!-- FILE LIST -->
    <div class="card shadow-sm">

        <div class="card-body">

            @if(count($files) > 0)

              @foreach($files as $file)

<div class="file-box">

    <div class="file-name">
        📄 {{ $file['name'] }}
    </div>

    <div class="d-flex gap-1">

        <!-- VIEW FILE -->
        <a href="{{ $file['url'] }}"
           target="_blank"
           class="btn btn-primary btn-xs">
            View
        </a>

        <!-- DOWNLOAD -->
        <a href="{{ $file['url'] }}"
           download
           class="btn btn-success btn-xs">
            Download
        </a>

    </div>

</div>

@endforeach

            @else
                <p class="text-muted">No files found</p>
            @endif

        </div>

    </div>

</div>

@endsection