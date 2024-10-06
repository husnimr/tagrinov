@extends('layouts.main')
@section('content')
<style>
    .image-detail {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 20px;
    }
    .image-detail img {
        width: 50%;
        height: 450px;
        object-fit: cover;
        border-radius: 8px;
    }
    .detail-text {
        max-width: 50%;
    }
    .detail-text h2 {
        margin-top: 0;
    }
    .qr-code-container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .qr-code {
        margin-bottom: 20px;
        width: 250px;
        height: 250px;
    }
    .copy-url {
        display: flex;
        width: 100%;
        max-width: 400px;
        margin-top: 10px;
    }
</style>
    <div class="container">
        <h3 class="mb-4"> {{ $tanaman->nama }}

            <button type="button" class="btn btn-outline-light mr-2" data-bs-toggle="modal" data-bs-target="#qrCodeModal">
                Lihat QR <i class="fa-solid fa-qrcode"></i>
            </button>
        </h3>
        <div class="image-detail">
            {{-- <img src="{{ asset('images/tumis.jpg') }}" alt="Kangkung"> --}}
            @if ($tanaman->url_gambar)
                <img src="{{ $tanaman->url_gambar }}" alt="{{ $tanaman->url_gambar }}">
            @else
                <img src="{{ asset('assets/image/no_image.png') }}" alt="No image available">
            @endif
            <div class="detail-text">
                <p><strong>Nama Tanaman:</strong> {{ $tanaman->nama }}</p>
                <p><strong>Nama Ilmiah:</strong> {{ $tanaman->nama_latin }}</p>
                <p><strong>Nama Daerah:</strong> {{ $tanaman->nama_daerah }}</p>
                @if ($tanaman->entitas_detail->varietas)
                    <p><strong>Varietas:</strong> {{ $tanaman->entitas_detail->varietas }}</p>
                @endif
                <p><strong>Deskripsi:</strong> {{ $tanaman->entitas_detail->deskripsi }}</p>
                @if ($tanaman->entitas_detail->manfaat)
                    <p><strong>Manfaat:</strong> {{ $tanaman->entitas_detail->manfaat }}</p>
                @endif
                @if ($tanaman->entitas_detail->kandungan)
                    <p><strong>Kandungan:</strong>{{ $tanaman->entitas_detail->kandungan }}</p>
                @endif
                @if ($tanaman->entitas_detail->keunggulan)
                    <p><strong>Keunggulan:</strong> {{ $tanaman->entitas_detail->keunggulan }}</p>
                @endif
                @if ($tanaman->entitas_detail->potensi_hasil)
                    <p><strong>Potensi Hasil:</strong> {{ $tanaman->entitas_detail->potensi_hasil }}</p>
                @endif
                @if ($tanaman->entitas_detail->agroekosistem)
                    <p><strong>Agroekosistem:</strong> {{ $tanaman->entitas_detail->agroekosistem }}</p>
                @endif
                @if ($tanaman->entitas_detail->syarat_tumbuh)
                    <p><strong>Syarat Tumbuh:</strong> {{ $tanaman->entitas_detail->syarat_tumbuh }}</p>
                @endif
                
                {{-- <ul>
                    <li>Mengandung banyak vitamin dan mineral</li>
                    <li>Bagus untuk kesehatan mata</li>
                    <li>Membantu menjaga kesehatan pencernaan</li>
                </ul> --}}
                {{-- <p><strong>Cara Menanam:</strong> Kangkung bisa ditanam dengan cara menyemai benih langsung di tanah yang subur dan cukup air. Perawatan yang baik akan menghasilkan tanaman kangkung yang subur dan siap panen dalam beberapa minggu.</p> --}}
            </div>
        </div>
        <!-- Modal -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code untuk {{ $tanaman->nama }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="qr-code-container">
                        <div class="qr-code text-center mb-3">
                            {!! $qr !!}
                        </div>
                        
                        <div class="input-group copy-url">
                            <input type="text" class="form-control" id="plantUrl" value="{{ $url }}" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="copyButton" onclick="copyToClipboard()" data-bs-toggle="tooltip" data-bs-placement="top" title="Salin ke clipboard"><i class="fa-regular fa-copy"></i></button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (tooltipTriggerEl) {
            new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    function copyToClipboard() {
        var copyText = document.getElementById("plantUrl");
        copyText.select();
        document.execCommand("copy");
        
        var copyButton = document.getElementById("copyButton");
        
        // Hide the tooltip for "Salin ke clipboard"
        var originalTooltip = bootstrap.Tooltip.getInstance(copyButton);
        if (originalTooltip) {
            originalTooltip.hide();
        }

        var tooltip = new bootstrap.Tooltip(copyButton, {
            trigger: 'manual',
            title: "Disalin!"
        });
        tooltip.show();
        
        setTimeout(function() {
            tooltip.hide();
        }, 2000);
    }
</script>
@endsection
