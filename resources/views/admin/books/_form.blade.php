{{-- Judul --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Judul <span class="text-danger">*</span></label>
    <input type="text" name="judul" class="form-control @error('judul') is-invalid @enderror"
        value="{{ old('judul', $book->judul ?? '') }}" required>
    @error('judul') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Pengarang --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Pengarang <span class="text-danger">*</span></label>
    <input type="text" name="pengarang" class="form-control @error('pengarang') is-invalid @enderror"
        value="{{ old('pengarang', $book->pengarang ?? '') }}" required>
    @error('pengarang') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Penerbit & Tahun --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Penerbit</label>
        <input type="text" name="penerbit" class="form-control @error('penerbit') is-invalid @enderror"
            value="{{ old('penerbit', $book->penerbit ?? '') }}">
        @error('penerbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Tahun Terbit</label>
        <input type="number" name="tahun_terbit" class="form-control @error('tahun_terbit') is-invalid @enderror"
            value="{{ old('tahun_terbit', $book->tahun_terbit ?? '') }}" max="{{ date('Y') }}">
        @error('tahun_terbit') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- ISBN & Stok --}}
<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">ISBN</label>
        <input type="text" name="isbn" class="form-control @error('isbn') is-invalid @enderror"
            value="{{ old('isbn', $book->isbn ?? '') }}">
        @error('isbn') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label fw-semibold">Stok <span class="text-danger">*</span></label>
        <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
            value="{{ old('stok', $book->stok ?? 0) }}" min="0" required>
        @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>
</div>

{{-- Kategori --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Kategori</label>
    <select name="kategori_ids[]" class="form-select @error('kategori_ids') is-invalid @enderror" multiple>
        @foreach ($kategoriList as $kategori)
            <option value="{{ $kategori->id }}"
                {{ in_array($kategori->id, old('kategori_ids', $selectedKategori ?? [])) ? 'selected' : '' }}>
                {{ $kategori->nama_kategori }}
            </option>
        @endforeach
    </select>
    <div class="form-text">Tahan Ctrl / Cmd untuk memilih lebih dari satu kategori.</div>
    @error('kategori_ids') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Deskripsi --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Deskripsi</label>
    <textarea name="deskripsi" rows="3"
        class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $book->deskripsi ?? '') }}</textarea>
    @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
</div>

{{-- Cover --}}
<div class="mb-3">
    <label class="form-label fw-semibold">Cover Buku</label>

    {{-- Toggle --}}
    <div class="btn-group w-100 mb-3" role="group">
        <input type="radio" class="btn-check" name="cover_source" id="cover_file" value="file"
            {{ old('cover_source', 'file') === 'file' ? 'checked' : '' }}>
        <label class="btn btn-outline-secondary" for="cover_file">
            <i class="fas fa-upload me-1"></i> Upload File
        </label>

        <input type="radio" class="btn-check" name="cover_source" id="cover_url" value="url"
            {{ old('cover_source') === 'url' ? 'checked' : '' }}>
        <label class="btn btn-outline-secondary" for="cover_url">
            <i class="fas fa-link me-1"></i> Link URL
        </label>
    </div>

    {{-- Panel: Upload File --}}
    <div id="panel-file">
        <input type="file" name="cover" id="cover_file_input" accept="image/jpg,image/jpeg,image/png,image/webp"
            class="form-control @error('cover') is-invalid @enderror">
        <div class="form-text">Format: JPG, JPEG, PNG, WEBP. Maks 2 MB.</div>
        @error('cover') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
    </div>

    {{-- Panel: URL --}}
    <div id="panel-url" class="d-none">
        <input type="url" name="cover_url" id="cover_url_input"
            class="form-control @error('cover_url') is-invalid @enderror"
            placeholder="https://contoh.com/gambar-cover.jpg"
            value="{{ old('cover_url', isset($book) && str_starts_with($book->cover ?? '', 'http') ? $book->cover : '') }}">
        <div class="form-text">Masukkan URL gambar yang dapat diakses publik.</div>
        @error('cover_url') <div class="invalid-feedback">{{ $message }}</div> @enderror
    </div>

    {{-- Preview --}}
    <div id="cover-preview-wrap" class="mt-3 d-none">
        <p class="text-muted small mb-1">Preview:</p>
        <img id="cover-preview" src="#" alt="Preview Cover"
            class="rounded border shadow-sm"
            style="max-height: 200px; max-width: 150px; object-fit: cover;">
    </div>

    {{-- Existing cover (edit mode) --}}
    @if (isset($book) && $book->cover && !str_starts_with($book->cover, 'http'))
        <div class="mt-3" id="existing-cover">
            <p class="text-muted small mb-1">Cover saat ini:</p>
            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover"
                class="rounded border shadow-sm"
                style="max-height: 200px; max-width: 150px; object-fit: cover;">
            <div class="form-check mt-2">
                <input class="form-check-input" type="checkbox" name="remove_cover" id="remove_cover" value="1"
                    {{ old('remove_cover') ? 'checked' : '' }}>
                <label class="form-check-label text-danger small" for="remove_cover">
                    Hapus cover saat ini
                </label>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    (function () {
        const radioFile   = document.getElementById('cover_file');
        const radioUrl    = document.getElementById('cover_url');
        const panelFile   = document.getElementById('panel-file');
        const panelUrl    = document.getElementById('panel-url');
        const fileInput   = document.getElementById('cover_file_input');
        const urlInput    = document.getElementById('cover_url_input');
        const previewWrap = document.getElementById('cover-preview-wrap');
        const previewImg  = document.getElementById('cover-preview');

        function switchPanel() {
            const isFile = radioFile.checked;
            panelFile.classList.toggle('d-none', !isFile);
            panelUrl.classList.toggle('d-none', isFile);
            hidePreview();

            // Reset inactive input so it doesn't get submitted
            if (isFile) {
                urlInput.value = '';
            } else {
                fileInput.value = '';
            }
        }

        function showPreview(src) {
            previewImg.src = src;
            previewWrap.classList.remove('d-none');
        }

        function hidePreview() {
            previewWrap.classList.add('d-none');
            previewImg.src = '#';
        }

        // File preview
        fileInput.addEventListener('change', function () {
            const file = this.files[0];
            if (!file) { hidePreview(); return; }
            const reader = new FileReader();
            reader.onload = e => showPreview(e.target.result);
            reader.readAsDataURL(file);
        });

        // URL preview (debounced)
        let urlTimer;
        urlInput.addEventListener('input', function () {
            clearTimeout(urlTimer);
            const val = this.value.trim();
            if (!val) { hidePreview(); return; }
            urlTimer = setTimeout(() => {
                const img = new Image();
                img.onload  = () => showPreview(val);
                img.onerror = () => hidePreview();
                img.src = val;
            }, 600);
        });

        // Trigger preview for existing URL value on page load
        if (urlInput.value.trim()) {
            const img = new Image();
            img.onload  = () => showPreview(urlInput.value.trim());
            img.src = urlInput.value.trim();
        }

        radioFile.addEventListener('change', switchPanel);
        radioUrl.addEventListener('change', switchPanel);

        // Init on load
        switchPanel();
    })();
</script>
@endpush