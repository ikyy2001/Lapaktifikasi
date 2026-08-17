@extends('layout')

@section('title', 'Tulis Berita Baru')

@section('content')
<!-- Quill.js Theme Stylesheet -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

<style>
    /* Modern Editor Layout Styling */
    .editor-container-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 12px !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02) !important;
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }

    .editor-container-card:focus-within {
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.06) !important;
    }

    /* 1. Title Input Styling */
    .news-title-input {
        width: 100%;
        border: none !important;
        outline: none !important;
        padding: 16px 20px !important;
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        color: #0f172a !important;
        background: transparent !important;
    }

    .news-title-input::placeholder {
        color: #94a3b8 !important;
        font-weight: 600 !important;
    }

    /* 2. Quill Rich Text Editor Custom Styling */
    .ql-toolbar.ql-snow {
        border: none !important;
        border-bottom: 1px solid #f1f5f9 !important;
        background: #fafbfc !important;
        padding: 10px 16px !important;
        font-family: inherit !important;
    }

    .ql-container.ql-snow {
        border: none !important;
        font-family: inherit !important;
        font-size: 1rem !important;
        color: #334155 !important;
    }

    .ql-editor {
        min-height: 380px !important;
        padding: 20px 24px !important;
        line-height: 1.75 !important;
    }

    .ql-editor.ql-blank::before {
        color: #94a3b8 !important;
        font-style: normal !important;
        font-size: 0.95rem !important;
        left: 24px !important;
    }

    .editor-footer-bar {
        border-top: 1px solid #f1f5f9;
        padding: 8px 18px;
        background: #ffffff;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 600;
    }

    /* 3. Teaser Box Styling */
    .teaser-textarea {
        width: 100%;
        border: none !important;
        outline: none !important;
        padding: 16px 20px !important;
        font-size: 0.95rem !important;
        color: #334155 !important;
        resize: vertical;
        min-height: 100px;
        background: transparent !important;
        line-height: 1.6;
    }

    .teaser-textarea::placeholder {
        color: #94a3b8 !important;
    }

    .teaser-footer-bar {
        padding: 6px 18px;
        display: flex;
        justify-content: flex-end;
        font-size: 0.8rem;
        color: #94a3b8;
        font-weight: 600;
    }

    /* Sidebar Settings Card */
    .sidebar-settings-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        margin-bottom: 20px;
    }

    .upload-zone {
        border: 2px dashed #cbd5e1;
        border-radius: 10px;
        padding: 24px 16px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .upload-zone:hover {
        border-color: #94a3b8;
        background: #f1f5f9;
    }

    .cover-preview-img {
        width: 100%;
        max-height: 200px;
        object-fit: cover;
        border-radius: 8px;
    }
</style>

<div class="container-fluid py-2">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary btn-sm mb-2 font-weight-bold">
                <i class="bi bi-arrow-left mr-1"></i> Kembali ke Daftar
            </a>
            <h1 class="h3 font-weight-bold text-dark mb-0">Tulis Berita Baru</h1>
        </div>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
        <div class="font-weight-bold mb-1"><i class="bi bi-exclamation-triangle-fill mr-1"></i> Mohon periksa inputan berikut:</div>
        <ul class="mb-0 pl-3">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data" id="newsForm">
        @csrf
        <input type="hidden" name="konten" id="kontenHiddenInput" value="{{ old('konten') }}">

        <div class="row">
            <!-- LEFT MAIN COLUMN: Editor Area -->
            <div class="col-lg-8 col-xl-9">
                <!-- 1. Judul Input Box -->
                <div class="editor-container-card mb-3">
                    <input type="text" 
                           name="judul" 
                           id="judulInput" 
                           class="news-title-input" 
                           placeholder="Judul" 
                           value="{{ old('judul') }}" 
                           required 
                           autocomplete="off">
                </div>

                <!-- 2. Rich Text Editor Box -->
                <div class="editor-container-card mb-3">
                    <!-- Custom Toolbar Container -->
                    <div id="quillToolbar">
                        <span class="ql-formats">
                            <button class="ql-undo" type="button" title="Undo"><i class="bi bi-arrow-counterclockwise"></i></button>
                            <button class="ql-redo" type="button" title="Redo"><i class="bi bi-arrow-clockwise"></i></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-bold" title="Bold"></button>
                            <button class="ql-italic" title="Italic"></button>
                            <button class="ql-underline" title="Underline"></button>
                            <button class="ql-strike" title="Strikethrough"></button>
                            <select class="ql-color" title="Warna Teks"></select>
                        </span>
                        <span class="ql-formats">
                            <select class="ql-header" title="Format Paragraf">
                                <option value="" selected>Normal</option>
                                <option value="1">Heading 1</option>
                                <option value="2">Heading 2</option>
                                <option value="3">Heading 3</option>
                            </select>
                        </span>
                        <span class="ql-formats">
                            <select class="ql-align" title="Rata Teks"></select>
                            <button class="ql-list" value="ordered" title="Numbered List"></button>
                            <button class="ql-list" value="bullet" title="Bullet List"></button>
                            <button class="ql-indent" value="-1" title="Outdent"></button>
                            <button class="ql-indent" value="+1" title="Indent"></button>
                        </span>
                        <span class="ql-formats">
                            <button class="ql-blockquote" title="Kutipan (Blockquote)"></button>
                            <button class="ql-link" title="Sisipkan Link"></button>
                            <button class="ql-image" title="Sisipkan Gambar"></button>
                            <button class="ql-video" title="Sisipkan Video/YouTube"></button>
                            <button class="ql-clean" title="Hapus Format"></button>
                        </span>
                    </div>

                    <!-- Quill Editor Container -->
                    <div id="quillEditor">{!! old('konten') !!}</div>

                    <!-- Live Word Counter -->
                    <div class="editor-footer-bar">
                        <span id="wordCountDisplay">0 kata</span>
                    </div>
                </div>

                <!-- 3. Teaser / Subjudul Box -->
                <div class="editor-container-card mb-4">
                    <textarea name="subjudul" 
                              id="subjudulInput" 
                              class="teaser-textarea" 
                              placeholder="Tulis teaser untuk menarik pembaca:" 
                              maxlength="255">{{ old('subjudul') }}</textarea>
                    <div class="teaser-footer-bar">
                        <span id="teaserCharCount">0/255</span>
                    </div>
                </div>
            </div>

            <!-- RIGHT SIDEBAR COLUMN: Publish Settings & Media -->
            <div class="col-lg-4 col-xl-3">
                <!-- Action Buttons Card -->
                <div class="sidebar-settings-card">
                    <h6 class="font-weight-bold text-dark mb-3">Publikasi</h6>
                    
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted d-block">Status</label>
                        <select name="status" id="statusSelect" class="form-control font-weight-bold">
                            <option value="draft" {{ old('status', 'draft') === 'draft' ? 'selected' : '' }}>📝 Draft</option>
                            <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>🚀 Published (Terbit)</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted d-block">Jadwal Tanggal Publikasi</label>
                        <input type="datetime-local" 
                               name="published_at" 
                               class="form-control form-control-sm" 
                               value="{{ old('published_at') }}">
                        <small class="text-muted d-block mt-1">Kosongkan untuk otomatis menggunakan waktu saat ini saat diterbitkan.</small>
                    </div>

                    <hr class="my-3">

                    <button type="submit" class="btn btn-primary btn-block font-weight-bold py-2 shadow-sm mb-2" id="submitBtn">
                        <i class="bi bi-cloud-arrow-up-fill mr-1"></i> Simpan Berita
                    </button>
                    <a href="{{ route('admin.news.index') }}" class="btn btn-light btn-block text-secondary btn-sm">
                        Batal
                    </a>
                </div>

                <!-- Cover Image Card -->
                <div class="sidebar-settings-card">
                    <h6 class="font-weight-bold text-dark mb-2">Cover Gambar</h6>
                    <p class="small text-muted mb-3">Upload gambar thumbnail untuk tampilan card & header artikel.</p>

                    <div class="upload-zone" id="uploadZone" onclick="document.getElementById('coverImageInput').click();">
                        <div id="uploadPlaceholder">
                            <i class="bi bi-cloud-arrow-up display-4 text-muted d-block mb-2" style="font-size: 2rem;"></i>
                            <div class="font-weight-bold small text-dark">Pilih atau Drag Gambar</div>
                            <small class="text-muted">JPG, PNG, WEBP (Maks 2MB)</small>
                        </div>
                        <div id="imagePreviewWrapper" style="display: none;">
                            <img id="coverPreview" src="#" alt="Cover Preview" class="cover-preview-img mb-2 shadow-sm">
                            <div class="small text-primary font-weight-bold"><i class="bi bi-arrow-repeat mr-1"></i> Ganti Gambar</div>
                        </div>
                    </div>
                    <input type="file" 
                           name="gambar" 
                           id="coverImageInput" 
                           accept="image/jpeg,image/png,image/jpg,image/webp" 
                           style="display: none;" 
                           onchange="handleImageSelect(this)">
                </div>
            </div>
        </div>
    </form>
</div>

<!-- Quill.js Core Scripts -->
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Inisialisasi Quill.js dengan Toolbar Kostum
        const quill = new Quill('#quillEditor', {
            modules: {
                toolbar: '#quillToolbar'
            },
            placeholder: 'Type something...',
            theme: 'snow'
        });

        // Event Custom Undo & Redo
        const undoBtn = document.querySelector('.ql-undo');
        const redoBtn = document.querySelector('.ql-redo');
        if (undoBtn) undoBtn.addEventListener('click', () => quill.history.undo());
        if (redoBtn) redoBtn.addEventListener('click', () => quill.history.redo());

        // 2. Real-Time Live Word Counter
        const wordCountDisplay = document.getElementById('wordCountDisplay');
        const hiddenKonten = document.getElementById('kontenHiddenInput');

        function calculateWordCount() {
            const text = quill.getText().trim();
            const words = text ? text.split(/\s+/).filter(Boolean).length : 0;
            wordCountDisplay.innerText = words + ' kata';
            
            // Sinkronisasi HTML konten ke input hidden
            const htmlContent = quill.root.innerHTML;
            hiddenKonten.value = (htmlContent === '<p><br></p>') ? '' : htmlContent;
        }

        quill.on('text-change', calculateWordCount);
        calculateWordCount(); // Initial count

        // 3. Subjudul / Teaser Live Character Counter
        const teaserInput = document.getElementById('subjudulInput');
        const teaserCount = document.getElementById('teaserCharCount');

        function updateTeaserCount() {
            teaserCount.innerText = teaserInput.value.length + '/255';
        }
        teaserInput.addEventListener('input', updateTeaserCount);
        updateTeaserCount();

        // 4. Form Submit Handler (Ensure hidden input has content)
        const newsForm = document.getElementById('newsForm');
        newsForm.addEventListener('submit', function(e) {
            const htmlContent = quill.root.innerHTML;
            hiddenKonten.value = (htmlContent === '<p><br></p>') ? '' : htmlContent;

            if (!hiddenKonten.value.trim()) {
                e.preventDefault();
                alert('Konten berita tidak boleh kosong. Silakan tulis isi berita terlebih dahulu.');
                quill.focus();
                return false;
            }
        });
    });

    // 5. Image Preview & Drop Handler
    function handleImageSelect(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('coverPreview').src = e.target.result;
                document.getElementById('uploadPlaceholder').style.display = 'none';
                document.getElementById('imagePreviewWrapper').style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
