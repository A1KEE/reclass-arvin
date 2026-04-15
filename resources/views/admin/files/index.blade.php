@extends('layouts.admin')

@section('content')

<style>
.folder-card {
    border-radius: 10px;
    transition: 0.25s;
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

/* TOP BAR */
.top-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
    margin-bottom: 15px;
}

.search-box {
    width: 250px;
}

/* 🔥 MAS MAHABANG DROPDOWN */
#positionDropdown {
    min-width: 260px;
}

/* animation */
.position-block {
    transition: all 0.3s ease;
}
</style>

<div class="container-fluid mt-3">

 <h3 class="mb-4">@section('page-title')
📁 Application Files
@endsection</h3>

@php
    $rawGrouped = collect($folders)->groupBy('position');

    $order = [
        'Teacher II',
        'Teacher III',
        'Teacher IV',
        'Teacher V',
        'Teacher VI',
        'Teacher VII',
        'Master Teacher I',
        'Master Teacher II',
        'Master Teacher III',
        'Teacher I'
    ];

    $grouped = collect($order)
        ->mapWithKeys(fn($pos) => $rawGrouped->has($pos) ? [$pos => $rawGrouped[$pos]] : []);

    $defaultPosition = 'all';
@endphp

{{-- TOP CONTROLS --}}
<div class="top-controls">

    {{-- DROPDOWN ONLY (TINANGGAL NA LABEL) --}}
    <div>
        <select id="positionDropdown" class="form-control form-control-sm">
            <option value="all" selected>All Positions</option>
            @foreach($grouped->keys() as $position)
                <option value="{{ $position }}">
                    {{ $position }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- SEARCH + BUTTON --}}
    <div class="d-flex gap-2">

        <input type="text"
               id="searchInput"
               class="form-control form-control-sm search-box"
               placeholder="🔍 Search files...">

        <button id="toggleAllBtn" class="btn btn-secondary btn-sm">
            Show All
        </button>

    </div>

</div>

{{-- POSITION BLOCKS --}}
@foreach($grouped as $position => $items)

<div class="position-block"
     data-position="{{ $position }}"
     style="display:none;">

   <div class="p-2 rounded mb-2 border shadow-sm bg-white text-dark font-weight-bold position-header"
     style="cursor:pointer;">
    🎓 {{ $position }} ({{ count($items) }})
</div>

    <div class="position-content">
    <div class="row">

        @foreach($items as $folder)

        <div class="col-lg-3 col-md-6 col-sm-12 mb-3 folder-item">

            <div class="card folder-card h-100 shadow-sm">

                <div class="card-header bg-dark text-white folder-header">
                    📂 {{ $folder['folder'] }}
                </div>

                <div class="card-body folder-body">

                    <div class="text-muted mb-2">
                        {{ count($folder['files']) }} files
                    </div>

                    @foreach(array_slice($folder['files'], 0, 4) as $file)
                        <div class="file-line">📄 {{ $file }}</div>
                    @endforeach

                </div>

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

@endforeach

</div>
</div>

{{-- SCRIPT --}}
<script>
const dropdown = document.getElementById('positionDropdown');
const searchInput = document.getElementById('searchInput');
const showAllBtn = document.getElementById('toggleAllBtn');
const blocks = document.querySelectorAll('.position-block');

/* =========================
   DROPDOWN
========================= */
dropdown.addEventListener('change', function () {

    const selected = this.value;

    blocks.forEach(block => {

        if (selected === 'all') {
            block.style.display = 'block';
            block.querySelector('.position-content').style.display = 'none';
        } else if (block.dataset.position === selected) {
            block.style.display = 'block';
            block.querySelector('.position-content').style.display = 'block';
        } else {
            block.style.display = 'none';
        }

    });

    searchInput.value = '';
});

/* =========================
   SHOW ALL BUTTON
========================= */
showAllBtn.addEventListener('click', function () {

    dropdown.value = 'all';

    blocks.forEach(block => block.style.display = 'block');

    searchInput.value = '';
});

/* =========================
   SEARCH (SMART)
========================= */
searchInput.addEventListener('keyup', function () {

    const keyword = this.value.toLowerCase();

    if (dropdown.value === 'all') {

        blocks.forEach(block => {

            let hasMatch = false;
            const items = block.querySelectorAll('.folder-item');

            items.forEach(item => {
                const text = item.innerText.toLowerCase();

                if (text.includes(keyword)) {
                    item.style.display = 'block';
                    hasMatch = true;
                } else {
                    item.style.display = 'none';
                }
            });

            block.style.display = hasMatch ? 'block' : 'none';
        });

        return;
    }

    const activeBlock = Array.from(blocks)
        .find(b => b.dataset.position === dropdown.value);

    if (!activeBlock) return;

    const items = activeBlock.querySelectorAll('.folder-item');

    items.forEach(item => {
        const text = item.innerText.toLowerCase();
        item.style.display = text.includes(keyword) ? 'block' : 'none';
    });
});

window.addEventListener('DOMContentLoaded', () => {
    dropdown.value = 'all';

    blocks.forEach(block => block.style.display = 'block');
});

const headers = document.querySelectorAll('.position-header');

headers.forEach(header => {
    header.addEventListener('click', function () {

        const content = this.nextElementSibling;

        // CLOSE ALL
        document.querySelectorAll('.position-content').forEach(c => {
            if (c !== content) c.style.display = 'none';
        });

        document.querySelectorAll('.position-header').forEach(h => {
            if (h !== this) h.classList.remove('active');
        });

        // TOGGLE CURRENT
        if (content.style.display === 'block') {
            content.style.display = 'none';
            this.classList.remove('active');
        } else {
            content.style.display = 'block';
            this.classList.add('active');
        }
    });
});
</script>

@endsection