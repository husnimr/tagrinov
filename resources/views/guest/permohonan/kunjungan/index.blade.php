@extends('layouts.main')
@section('content')
<style>
    body {
        background-color: #f8f9fa;
    }
    form {
        background: #fff;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 3rem; 
    }

    label {
        font-weight: bold;
    }

    textarea {
        resize: none;
    }

    .file-input-container {
        margin-top: 1rem;
    }

    .file-input-container:last-of-type {
        margin-bottom: 2rem; 
    }

    .submit-btn-container {
        text-align: center;
        margin-top: 2rem;
    }

    /* Styling tambahan untuk input jumlah orang */
    #jumlahOrangContainer {
        display: none;
        margin-top: 1rem;
    }

    /* Optional: Styling untuk tombol aktif */
    .btn-outline-success.active {
        background-color: #198754;
        color: white;
    }

    /* Membuat tombol lebih lebar */
    .btn-group-custom {
        display: flex;
        flex-wrap: wrap; /* Memastikan elemen membungkus jika tidak cukup lebar */
        gap: 0.5rem;
    }

    .btn-group-custom .btn {
        flex: 1; /* Membuat tombol mengambil ruang yang tersedia */
        min-width: 150px;
        padding: 8px;
    }

    /* Styling untuk Pilihan Pertanian */
    #pilihanPertanianContainer {
        display: none;
        margin-top: 1rem;
    }

    #pilihanPertanianContainer .form-check {
        margin-bottom: 0.5rem;
    }
    @media (max-width: 768px) {
        .btn-group-custom label {
            flex: 1 1 100%; /* Membuat tombol memenuhi lebar layar di mobile */
            text-align: center;
        }
    }
</style>

<div class="container">
    <h3>Rencanakan Kunjunganmu</h3>

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3085d6',
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif

    @if($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    html: `{!! implode('<br>', $errors->all()) !!}`,
                    confirmButtonColor: '#d33',
                });
            });
        </script>
    @endif
    <form action="{{ route('kunjungan.store') }}" method="POST" id="form-kunjungan" enctype="multipart/form-data">
        @csrf
        <!-- Nama Lengkap -->
        <div class="mb-3">
            <label for="namaLengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" value="{{ old('nama_lengkap') }}" required>
        </div>

        <!-- No HP -->
        <div class="mb-3">
            <label for="no_hp" class="form-label">No HP/WhatsApp</label>
            <input 
                type="text" 
                class="form-control" 
                id="no_hp" 
                name="no_hp" 
                value="{{ old('no_hp') }}" 
                placeholder="08XX XXXX XXXX" 
                maxlength="13"
                required
            >
            <div id="no_hp_error" class="invalid-feedback" style="display: none;"></div>
        </div>

        <!-- Usia -->
        <fieldset class="mb-3">
            <legend class="col-form-label" style="font-weight: bold;">Usia (tahun)</legend>
            @foreach($usia as $item)
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="usia{{ $item->id }}" name="usia_id" 
                        value="{{ $item->id }}" {{ old('usia_id') == $item->id ? 'checked' : '' }} required>
                    <label class="form-check-label" for="usia{{ $item->id }}">{{ $item->nama }}</label>
                </div>
            @endforeach
        </fieldset>

        <!-- Jenis Kelamin -->
        <fieldset class="mb-3">
            <legend class="col-form-label" style="font-weight: bold;">Jenis Kelamin</legend>
            @foreach($jenis_kelamin as $item)
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="jenis_kelamin{{ $item->id }}" name="jenis_kelamin_id" 
                        value="{{ $item->id }}" {{ old('jenis_kelamin_id') == $item->id ? 'checked' : '' }} required>
                    <label class="form-check-label" for="jenis_kelamin{{ $item->id }}">{{ $item->nama }}</label>
                </div>
            @endforeach
        </fieldset>

        <!-- Asal Instansi -->
        <div class="mb-3">
            <label for="asal_instansi" class="form-label">Asal Instansi</label>
            <input type="text" class="form-control" id="asal_instansi" name="asal_instansi" 
                value="{{ old('asal_instansi') }}" maxlength="100" 
                placeholder="Masukkan asal instansi" required>
            <div id="asal_instansi_error" class="invalid-feedback" style="display:none;"></div>
        </div>

        <!-- Pekerjaan -->
        <div class="mb-3">
            <label for="pekerjaan" class="form-label">Pekerjaan</label>
            <select class="form-select" id="pekerjaan" name="pekerjaan_id" required>
                <option value="" disabled {{ old('pekerjaan_id') ? '' : 'selected' }}>Pilih Pekerjaan</option>
                @foreach($pekerjaan as $item)
                    <option value="{{ $item->id }}" {{ old('pekerjaan_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Kategori Informasi Publik -->
        <div class="mb-3">
            <label for="kategori_informasi" class="form-label">Kategori Informasi Publik</label>
            <select class="form-select" id="kategori_informasi" name="kategori_informasi_id" required>
                <option value="" disabled {{ old('kategori_informasi_id') ? '' : 'selected' }}>Pilih Kategori Informasi Publik</option>
                @foreach($kategori_informasi as $item)
                    <option value="{{ $item->id }}" {{ old('kategori_informasi_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Pilihan Tambahan untuk Pertanian -->
        <div class="mb-3" id="pilihanPertanianContainer" style="display:none;">
            <label class="form-label">Pilihan Pertanian</label>
            @foreach($pilihan_pertanian as $item)
                <div class="form-check">
                    <input class="form-check-input" type="radio" id="pilihan_pertanian{{ $item->id }}" name="pilihan_pertanian_id" 
                        value="{{ $item->id }}" {{ old('pilihan_pertanian_id') == $item->id ? 'checked' : '' }}>
                    <label class="form-check-label" for="pilihan_pertanian{{ $item->id }}">{{ $item->nama }}</label>
                </div>
            @endforeach
        </div>

        <!-- Pendidikan Terakhir -->
        <div class="mb-3">
            <label for="pendidikan" class="form-label">Pendidikan Terakhir</label>
            <select class="form-select" id="pendidikan" name="pendidikan_id" required>
                <option value="" disabled {{ old('pendidikan_id') ? '' : 'selected' }}>Pilih Pendidikan Terakhir</option>
                @foreach($pendidikan as $item)
                    <option value="{{ $item->id }}" {{ old('pendidikan_id') == $item->id ? 'selected' : '' }}>
                        {{ $item->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Jenis Pengunjung -->
        <div class="mb-3">
            <label class="form-label">Jenis Pengunjung</label>
            <div class="btn-group-custom mb-3 col-12" role="group" aria-label="Jenis Pengunjung">
                @foreach($jenis_pengunjung as $item)
                    <input type="radio" class="btn-check" id="jenis_pengunjung{{ $item->id }}" name="jenis_pengunjung_id" 
                        value="{{ $item->id }}" {{ old('jenis_pengunjung_id') == $item->id ? 'checked' : '' }} required>
                    <label class="btn btn-outline-success" for="jenis_pengunjung{{ $item->id }}">{{ $item->nama }}</label>
                @endforeach
            </div>

            <!-- Input untuk jumlah orang, muncul hanya jika Perkelompok dipilih -->
            <div id="jumlah_orang" class="mb-3" style="display:block;">
                <label for="jumlah" class="form-label">Jumlah Orang</label>
                <input type="number" class="form-control" id="jumlah_orang_input" name="jumlah_orang" min="1" maxlength="2" max="50"
                    placeholder="Masukkan Jumlah Orang" value="{{ old('jumlah_orang') }}">
                <div id="jumlah_orang_error" class="invalid-feedback" style="display:none;"></div>
            </div>
            
        </div>



        <!-- Tanggal & Jam Kunjungan (Berdampingan) -->
        <div class="mb-3">
            <label class="form-label">Tanggal & Jam Kunjungan</label>
            <div class="row">
                <!-- Tanggal -->
                <div class="col-md-6">
                    <input type="date" class="form-control @error('tanggal_kunjungan_date') is-invalid @enderror"
                        id="tanggal_kunjungan_date" name="tanggal_kunjungan_date"
                        value="{{ old('tanggal_kunjungan_date') }}" min="{{ \Carbon\Carbon::tomorrow()->format('Y-m-d') }}" required>
                    @error('tanggal_kunjungan_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Jam -->
                <div class="col-md-6">
                    <select class="form-select @error('tanggal_kunjungan_time') is-invalid @enderror"
                            id="tanggal_kunjungan_time" name="tanggal_kunjungan_time" required>
                        <option value="">-- Pilih Jam --</option>
                        @for ($hour = 8; $hour <= 15; $hour++)
                            @php
                                $time = sprintf('%02d:00', $hour);
                            @endphp
                            <option value="{{ $time }}" {{ old('tanggal_kunjungan_time') == $time ? 'selected' : '' }}>
                                {{ $time }}
                            </option>
                        @endfor
                    </select>
                    @error('tanggal_kunjungan_time')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>




        <!-- Tujuan Kunjungan -->
        <div class="mb-3">
            <label for="tujuan_kunjungan" class="form-label">Tujuan Kunjungan</label>
            <textarea class="form-control" id="tujuan_kunjungan" name="tujuan_kunjungan" rows="4" 
                placeholder="Tulis Tujuan Kunjungan" maxlength="250" oninput="updateCharCount()" required>{{ old('tujuan_kunjungan') }}</textarea>
            <small id="charCount" class="text-muted">Sisa karakter: 250</small>

            @error('tujuan_kunjungan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Unggah Foto KTP -->
        <div class="file-input-container">
            <label for="fotoKTP" class="form-label">Unggah Foto KTP </label>
            <input type="file" class="form-control @error('url_foto_ktp') is-invalid @enderror" id="fotoKTP" name="url_foto_ktp" accept=".jpg,.jpeg,.png" capture="environment" required>
            <small class="text-muted">Format: JPG, JPEG, PNG. Maks: 10MB.</small>
            @error('url_foto_ktp')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="error_ktp" style="display:none;"></div>
        </div>
        
        <!-- Unggah Foto Selfie -->
        <div class="file-input-container">
            <label for="fotoSelfie" class="form-label">Unggah Foto Selfie</label>
            <input type="file" class="form-control @error('url_foto_selfie') is-invalid @enderror" id="fotoSelfie" name="url_foto_selfie" accept="image/*" capture="user" required>
            <small class="text-muted">Format: JPG, JPEG, PNG. Maks: 10MB.</small>
            @error('url_foto_selfie')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <div class="invalid-feedback" id="error_selfie" style="display:none;"></div>
        </div>

        <!-- Tombol Submit -->
        <div class="submit-btn-container">
            <button type="submit" class="btn btn-success">Kirim Permohonan</button>
        </div>
    </form>
</div>

    {{-- Nomor HP --}}
    <script>
        const form = document.getElementById('form-kunjungan');
        const inputNoHp = document.getElementById('no_hp');
        const errorDiv = document.getElementById('no_hp_error');
    
        function showError(message) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
            inputNoHp.classList.add('is-invalid');
        }
    
        function clearError() {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
            inputNoHp.classList.remove('is-invalid');
        }
    
        inputNoHp.addEventListener('input', function () {
            // Hapus karakter non-digit
            this.value = this.value.replace(/\D/g, '');
    
            // Maksimal 13 digit
            if (this.value.length > 13) {
                this.value = this.value.slice(0, 13);
            }
    
            const val = this.value;
    
            if (val === '') {
                clearError();
                return;
            }
    
            // Jika baru 1 digit
            if (val.length === 1) {
                if (val !== '0') {
                    showError('Nomor HP harus diawali 08');
                } else {
                    clearError(); // 0 masih valid, jangan error
                }
                return;
            }
    
            // Jika lebih dari 1 digit tapi bukan 08
            if (!val.startsWith('08')) {
                showError('Nomor HP harus diawali 08');
            } else {
                clearError();
            }
        });
    
        inputNoHp.addEventListener('blur', function () {
            const val = this.value;
    
            if (val === '') {
                clearError();
                return;
            }
    
            if (!val.startsWith('08')) {
                showError('Nomor HP harus diawali 08');
                return;
            }
    
            if (val.length < 11) {
                showError('Nomor HP tidak sesuai format');
            } else {
                clearError();
            }
        });
        form.addEventListener('submit', function (e) {
            const val = inputNoHp.value;

            // Cek ulang logika error sebelum kirim
            if (
                val === '' ||
                !val.startsWith('08') ||
                val.length < 11 ||
                val.length > 13
            ) {
                e.preventDefault(); // blok submit
                inputNoHp.focus();  // arahkan fokus
                // Tampilkan error jika belum tampil
                if (val === '') {
                    clearError(); // biarkan HTML5 required jalan
                } else if (!val.startsWith('08')) {
                    showError('Nomor HP harus diawali 08');
                } else if (val.length < 11) {
                    showError('Nomor HP tidak sesuai format');
                }
            }
        });
    </script>

    {{-- Asal instansi --}}
    <script>
        const inputInstansi = document.getElementById('asal_instansi');
        const errorInstansi = document.getElementById('asal_instansi_error');
    
        function showInstansiError(message) {
            errorInstansi.textContent = message;
            errorInstansi.style.display = 'block';
            inputInstansi.classList.add('is-invalid');
        }
    
        function clearInstansiError() {
            errorInstansi.textContent = '';
            errorInstansi.style.display = 'none';
            inputInstansi.classList.remove('is-invalid');
        }
    
        inputInstansi.addEventListener('input', function () {
            // Hanya huruf, angka, dan spasi
            this.value = this.value.replace(/[^a-zA-Z0-9\s]/g, '');
    
            // Jika mentok 100 karakter
            if (this.value.length >= 100) {
                showInstansiError('Nama Instansi maksimal 100 karakter.');
            } else {
                clearInstansiError();
            }
        });
    
        inputInstansi.addEventListener('blur', function () {
            // Bersihkan error saat input tidak fokus
            if (this.value.length < 100) {
                clearInstansiError();
            }
        });
    </script>
    
    {{-- Jumlah orang --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
        const jumlahInput = document.getElementById('jumlah_orang_input');
        const jumlahError = document.getElementById('jumlah_orang_error');
        const form = document.getElementById('form-kunjungan');
        const jenisSelect = document.getElementById('jenis_pengunjung'); // ID select jenis pengunjung

        function showJumlahError(message) {
            jumlahError.textContent = message;
            jumlahError.style.display = 'block';
            jumlahInput.classList.add('is-invalid');
        }

        function clearJumlahError() {
            jumlahError.textContent = '';
            jumlahError.style.display = 'none';
            jumlahInput.classList.remove('is-invalid');
        }

        jumlahInput.addEventListener('input', function () {
            // Batasi hanya 2 digit angka
            if (this.value.length > 2) {
                this.value = this.value.slice(0, 2);
            }

            const val = parseInt(this.value);

            if (val > 50) {
                showJumlahError('Jumlah orang maksimal 50.');
            } else {
                clearJumlahError();
            }
        });

        form.addEventListener('submit', function (e) {
            const jenis = jenisSelect.value;
            const val = parseInt(jumlahInput.value);

            // Hanya validasi jumlah orang jika pengunjung perkelompok
            if (jenis.toLowerCase() === 'Perkelompok') {
                if (isNaN(val) || val < 1 || val > 50) {
                    e.preventDefault();
                    showJumlahError('Jumlah orang harus antara 1 hingga 50.');
                    jumlahInput.focus();
                }
            } else {
                // Perorangan, clear error
                clearJumlahError();
            }
        });

        // Optional: Sembunyikan field jumlah jika perorangan
        jenisSelect.addEventListener('change', function () {
            if (this.value.toLowerCase() === 'Perorangan') {
                jumlahInput.value = '';
                clearJumlahError();
                document.getElementById('jumlah_orang_container').style.display = 'none';
            } else {
                document.getElementById('jumlah_orang_container').style.display = 'block';
            }
        });

        // Trigger on load
        jenisSelect.dispatchEvent(new Event('change'));
    });
    </script>
    
    {{-- Upload KTP Selfie --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-kunjungan');
            const fotoKTP = document.getElementById('fotoKTP');
            const fotoSelfie = document.getElementById('fotoSelfie');
        
            const errorKTP = document.getElementById('error_ktp');
            const errorSelfie = document.getElementById('error_selfie');
        
            function showError(input, errorDiv, message) {
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
                input.classList.add('is-invalid');
            }
        
            function clearError(input, errorDiv) {
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
                input.classList.remove('is-invalid');
            }
        
            function validateFile(input, errorDiv, label) {
                const file = input.files[0];
                if (!file) return true; // tidak dicek jika belum upload
        
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                const maxSize = 10 * 1024 * 1024; // 10MB
        
                if (!allowedTypes.includes(file.type)) {
                    showError(input, errorDiv, label + ' harus berupa JPG, JPEG, atau PNG.');
                    return false;
                }
        
                if (file.size > maxSize) {
                    showError(input, errorDiv, label + ' maksimal berukuran 10MB.');
                    return false;
                }
        
                clearError(input, errorDiv);
                return true;
            }
        
            fotoKTP.addEventListener('change', () => {
                validateFile(fotoKTP, errorKTP, 'Foto KTP');
            });
        
            fotoSelfie.addEventListener('change', () => {
                validateFile(fotoSelfie, errorSelfie, 'Foto Selfie');
            });
        
            form.addEventListener('submit', function (e) {
                const isKtpValid = validateFile(fotoKTP, errorKTP, 'Foto KTP');
                const isSelfieValid = validateFile(fotoSelfie, errorSelfie, 'Foto Selfie');
        
                if (!isKtpValid) {
                    e.preventDefault();
                    fotoKTP.focus();
                } else if (!isSelfieValid) {
                    e.preventDefault();
                    fotoSelfie.focus();
                }
            });
        });
    </script>

    {{-- Tujuan kunjungan --}}
    <script>
        function updateCharCount() {
            const textarea = document.getElementById('tujuan_kunjungan');
            const counter = document.getElementById('charCount');
            const max = 250;
            const currentLength = textarea.value.length;
    
            counter.textContent = 'Sisa karakter: ' + (max - currentLength);
        }
    
        document.addEventListener('DOMContentLoaded', function () {
            updateCharCount();
    
            // Tambahkan trim sebelum submit
            const form = document.getElementById('form-kunjungan');
            form.addEventListener('submit', function () {
                const textarea = document.getElementById('tujuan_kunjungan');
                textarea.value = textarea.value.trim();
            });
        });
    </script>
    
    

<script>
document.addEventListener('DOMContentLoaded', function () {
    const kategoriSelect = document.getElementById('kategori_informasi');
    const pilihanPertanianContainer = document.getElementById('pilihanPertanianContainer');
    const pilihanPertanianRadios = document.querySelectorAll('input[name="pilihan_pertanian"]');
    const jenisRadios = document.querySelectorAll('input[name="jenis_pengunjung_id"]');
    const jumlahOrangContainer = document.getElementById('jumlah_orang');
    const jumlahOrangInput = jumlahOrangContainer ? jumlahOrangContainer.querySelector('input') : null;

    // Fungsi untuk handle tampilan pilihan pertanian
    function updatePilihanPertanian() {
        if (kategoriSelect && kategoriSelect.value === '1') {
            pilihanPertanianContainer.style.display = 'block';
            pilihanPertanianRadios.forEach(r => r.required = true);
        } else {
            pilihanPertanianContainer.style.display = 'none';
            pilihanPertanianRadios.forEach(r => {
                r.required = false;
                r.checked = false;
            });
        }
    }

    // Fungsi untuk handle tampilan input jumlah orang
    function updateJumlahOrang() {
        const selected = [...jenisRadios].find(r => r.checked);
        if (selected && selected.value === '2') {
            jumlahOrangContainer.style.display = 'block';
            if (jumlahOrangInput) jumlahOrangInput.required = true;
        } else {
            jumlahOrangContainer.style.display = 'none';
            if (jumlahOrangInput) {
                jumlahOrangInput.required = false;
                jumlahOrangInput.value = '';
            }
        }
    }

    // Inisialisasi saat halaman load
    updatePilihanPertanian();
    updateJumlahOrang();

    // Event listener
    if (kategoriSelect) {
        kategoriSelect.addEventListener('change', updatePilihanPertanian);
    }

    jenisRadios.forEach(r => {
        r.addEventListener('change', updateJumlahOrang);
    });
});
</script>

<script>
document.getElementById('tanggal_kunjungan_date').addEventListener('change', function() {
    const selectedDate = new Date(this.value);
    const day = selectedDate.getDay(); // 0 = Minggu, 6 = Sabtu

    if(day === 0 || day === 6){
        alert('Kunjungan tidak tersedia pada hari Sabtu dan Minggu. Silakan pilih hari lain.');
        this.value = ''; // reset tanggal
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInputs = document.querySelectorAll('input[type="file"]');

    fileInputs.forEach(input => {
        input.addEventListener('change', function () {
            const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
            const maxSize = 10 * 1024 * 1024; // 10 MB

            for (let file of this.files) {
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Tidak Valid',
                        text: 'File hanya mendukung format: jpg, jpeg, png.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = ''; // Clear the invalid file
                    break;
                }

                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Ukuran File Terlalu Besar',
                        text: 'Ukuran file maksimal 10MB.',
                        confirmButtonColor: '#dc3545'
                    });
                    this.value = ''; // Clear the file
                    break;
                }
            }
        });
    });
});
</script>


@endsection
