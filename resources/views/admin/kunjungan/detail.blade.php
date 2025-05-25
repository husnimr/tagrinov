@extends('layouts.admin') 
@section('content') 
<script>
	const title = document.getElementsByTagName('title')[0];
	title.innerHTML += ' | Detail Permohonan Kunjungan';
</script>
<div class="container">
	<h2>Detail Permohonan Kunjungan</h2>
	<nav class="mb-4" style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
		<ol class="breadcrumb">
			<li class="breadcrumb-item">
				<a href="{{ route('admin.dashboard') }}">Dashboard</a>
			</li>
			<li class="breadcrumb-item">
				<a href="{{ route('kunjungan.getAll') }}">Permohonan Kunjungan</a>
			</li>
			<li class="breadcrumb-item" aria-current="page">Detail Permohonan Kunjungan</li>
		</ol>
	</nav>

	@if($kunjungan->status_verifikasi === 'Terverifikasi' && $kunjungan->verified_at)
		<div class="alert alert-primary mt-3 d-flex align-items-start gap-2">
			<i class="fa-solid fa-check-circle fs-3 text-primary align-self-center"></i>
			<div>
				<strong>Terverifikasi oleh {{ $kunjungan->verifiedBy->name }}</strong><br>
				<small class="text-muted">
					{{ \Carbon\Carbon::parse($kunjungan->verified_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i:s') }}
				</small>
			</div>
		</div>
	@elseif($kunjungan->status_verifikasi === 'Ditolak' && $kunjungan->rejectverify_at)
		<div class="alert alert-danger mt-3 d-flex align-items-start gap-2">
			<i class="fa-solid fa-circle-xmark fs-3 text-danger align-self-center"></i>
			<div>
				<strong>Verifikasi ditolak oleh {{ $kunjungan->rejectVerifyBy->name }}</strong><br>
				<small class="text-muted">
					{{ \Carbon\Carbon::parse($kunjungan->rejectverify_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i:s') }}
				</small>
			</div>
		</div>
	@endif


	<div class="d-flex flex-wrap gap-2 mb-3">
		<a href="https://wa.me/{{ preg_replace('/\D/', '', $kunjungan->no_hp) }}" target="_blank" class="btn btn-success">
			<i class="fab fa-whatsapp"></i> Hubungi
		</a>
	
		@if($kunjungan->status_verifikasi === 'Terverifikasi')
			<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#cancelVerificationModal">
				<i class="fa-solid fa-times"></i> Batalkan Verifikasi
			</button>
		@elseif($kunjungan->status_verifikasi === 'Belum Diverifikasi')
			<button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confirmVerificationModal">
				<i class="fa-solid fa-check"></i> Verifikasi
			</button>
			<button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmRejectModal">
				<i class="fa-solid fa-times"></i> Tolak
			</button>
		@elseif($kunjungan->status_verifikasi === 'Ditolak')
			<button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#cancelRejectionModal">
				<i class="fa-solid fa-undo"></i> Batalkan Penolakan
			</button>
		@endif
	</div>
	
	<!-- Modal Konfirmasi Verifikasi -->
	<div class="modal fade" id="confirmVerificationModal" tabindex="-1" aria-labelledby="confirmVerificationModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h5 class="modal-title" id="confirmVerificationModalLabel">Konfirmasi Verifikasi</h5>
					<button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Apakah Anda yakin ingin memverifikasi permohonan kunjungan ini?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<form action="{{ route('kunjungan.verify', $kunjungan->id) }}" method="POST">
						@csrf
						<button type="submit" class="btn btn-primary">Verifikasi</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Konfirmasi Penolakan -->
	<div class="modal fade" id="confirmRejectModal" tabindex="-1" aria-labelledby="confirmRejectModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title" id="confirmRejectModalLabel">Konfirmasi Penolakan</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Apakah Anda yakin ingin menolak permohonan kunjungan ini?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<form action="{{ route('kunjungan.rejectVerification', $kunjungan->id) }}" method="POST">
						@csrf
						@method('PUT')
						<button type="submit" class="btn btn-danger">Tolak</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Konfirmasi Pembatalan Verifikasi -->
	<div class="modal fade" id="cancelVerificationModal" tabindex="-1" aria-labelledby="cancelVerificationModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-danger text-white">
					<h5 class="modal-title" id="cancelVerificationModalLabel">Batalkan Verifikasi</h5>
					<button type="button" class="btn-close bg-white" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Apakah Anda yakin ingin membatalkan verifikasi kunjungan ini?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<a href="{{ route('kunjungan.cancelVerification', $kunjungan->id) }}" class="btn btn-danger">Batalkan Verifikasi</a>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Modal Konfirmasi Pembatalan Penolakan -->
	<div class="modal fade" id="cancelRejectionModal" tabindex="-1" aria-labelledby="cancelRejectionModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-success text-white">
					<h5 class="modal-title" id="cancelRejectionModalLabel">Batalkan Penolakan</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					Apakah Anda yakin ingin membatalkan penolakan permohonan kunjungan ini?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
					<form action="{{ route('kunjungan.cancelRejection', $kunjungan->id) }}" method="POST">
						@csrf
						@method('PUT')
						<button type="submit" class="btn btn-success">Batalkan Penolakan</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<style>
		.table th {
			width: 30%;
		}
	</style>	

	<div class="card rounded-2 p-4 mb-4">
		<div class="row">
			{{-- Info Kiri --}}
			<div class="col-md-6">
				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-person-fill me-3 text-primary fs-4"></i>
					<div>
						<div class="text-muted small">Nama Lengkap</div>
						<div class="fw-semibold">{{ $kunjungan->nama_lengkap }}</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-calendar-check-fill me-3 text-success fs-4"></i>
					<div>
						<div class="text-muted small">Tanggal Kunjungan</div>
						<div class="fw-semibold">{{ $kunjungan->tanggal_kunjungan }}</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-telephone-fill me-3 text-info fs-4"></i>
					<div>
						<div class="text-muted small">Nomor HP</div>
						<div class="fw-semibold">{{ $kunjungan->no_hp }}</div>
					</div>
				</div>
				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-telephone-fill me-3 text-info fs-4"></i>
					<div>
						<div class="text-muted small">Usia</div>
						<div class="fw-semibold">{{ $kunjungan->usia->nama }}</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-gender-ambiguous me-3 text-warning fs-4"></i>
					<div>
						<div class="text-muted small">Jenis Kelamin</div>
						<div class="fw-semibold">{{ $kunjungan->jenis_kelamin->nama }}</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-mortarboard-fill me-3 text-secondary fs-4"></i>
					<div>
						<div class="text-muted small">Pendidikan</div>
						<div class="fw-semibold">{{ $kunjungan->pendidikan->nama }}</div>
					</div>
				</div>
				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-card-image me-3 text-dark fs-4"></i>
					<div>
						<div class="text-muted small">Foto KTP</div>
						<div class="fw-semibold">
							<a href="#" data-bs-toggle="modal" data-bs-target="#ktpModal" class="text-decoration-none">Lihat foto</a>
						</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-card-image me-3 text-dark fs-4"></i>
					<div>
						<div class="text-muted small">Foto Selfie</div>
						<div class="fw-semibold">
							<a href="#" data-bs-toggle="modal" data-bs-target="#selfieModal" class="text-decoration-none">Lihat foto</a>
						</div>
					</div>
				</div>
				<!-- Modal untuk Foto KTP -->
				<div class="modal fade" id="ktpModal" tabindex="-1" aria-labelledby="ktpModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content rounded-3">
							<div class="modal-header">
								<h5 class="modal-title" id="ktpModalLabel">Foto KTP</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
							</div>
							<div class="modal-body text-center">
								<img src="{{ asset('storage/' . $kunjungan->url_foto_ktp) }}" alt="Foto KTP" class="img-fluid rounded">
							</div>
						</div>
					</div>
				</div>

				<!-- Modal untuk Foto Selfie -->
				<div class="modal fade" id="selfieModal" tabindex="-1" aria-labelledby="selfieModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-dialog-centered">
						<div class="modal-content rounded-3">
							<div class="modal-header">
								<h5 class="modal-title" id="selfieModalLabel">Foto Selfie</h5>
								<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
							</div>
							<div class="modal-body text-center">
								<img src="{{ asset('storage/' . $kunjungan->url_foto_selfie) }}" alt="Foto Selfie" class="img-fluid rounded">
							</div>
						</div>
					</div>
				</div>

			</div>

			{{-- Info Kanan --}}
			<div class="col-md-6">
				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-building me-3 text-info fs-4"></i>
					<div>
						<div class="text-muted small">Asal Instansi</div>
						<div class="fw-semibold">{{ $kunjungan->asal_instansi }}</div>
					</div>
				</div>

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-person-badge-fill me-3 text-danger fs-4"></i>
					<div>
						<div class="text-muted small">Pekerjaan</div>
						<div class="fw-semibold">{{ $kunjungan->pekerjaan->nama }}</div>
					</div>
				</div>
				
				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-geo-alt-fill me-3 text-primary fs-4"></i>
					<div>
						<div class="text-muted small">Jenis Pengunjung</div>
						<div class="fw-semibold">{{ $kunjungan->jenis_pengunjung->nama }}</div>
					</div>
				</div>
				@if($kunjungan->jumlah_orang)
					<div class="mb-3 d-flex align-items-start">
						<i class="bi bi-people-fill me-3 text-secondary fs-4"></i>
						<div>
							<div class="text-muted small">Jumlah</div>
							<div class="fw-semibold">{{ $kunjungan->jumlah_orang }} Orang</div>
						</div>
					</div>
				@endif

				@if($kunjungan->kategori_informasi)
					<div class="mb-3 d-flex align-items-start">
						<i class="bi bi-folder-fill me-3 text-primary fs-4"></i>
						<div>
							<div class="text-muted small">Kategori Informasi</div>
							<div class="fw-semibold">{{ $kunjungan->kategori_informasi->nama }}</div>
						</div>
					</div>
				@endif

				@if($kunjungan->pilihan_pertanian)
					<div class="mb-3 d-flex align-items-start">
						<i class="bi bi-collection-fill me-3 text-success fs-4"></i>
						<div>
							<div class="text-muted small">Pilihan Pertanian</div>
							<div class="fw-semibold">{{ $kunjungan->pilihan_pertanian->nama }}</div>
						</div>
					</div>
				@endif

				<div class="mb-3 d-flex align-items-start">
					<i class="bi bi-chat-left-text-fill me-3 text-dark fs-4"></i>
					<div>
						<div class="text-muted small">Tujuan Kunjungan</div>
						<div class="fw-semibold">{{ $kunjungan->tujuan_kunjungan }}</div>
					</div>
				</div>
				@if($kunjungan->status_verifikasi === 'Terverifikasi')
					<div class="mb-3 d-flex align-items-start">
						<i class="bi bi-chat-left-text-fill me-3 text-dark fs-4"></i>
						<div>
							<div class="text-muted small">Status Persetujuan</div>
							@if($kunjungan->status_setujui === 'pending')
								<div class="fw-semibold">
									<span class="badge bg-warning text-white text-capitalize px-3 py-2">
										Menunggu Persetujuan
									</span>
								</div>
							@elseif($kunjungan->status_setujui === 'Disetujui' && $kunjungan->approvedBy && $kunjungan->approved_at)
								<div class="fw-semibold mb-1">
									<span class="badge bg-success text-capitalize px-3 py-2">
										Disetujui
									</span>
								</div>
								<div class="text small">
									oleh <strong>{{ $kunjungan->approvedBy->name }}</strong><br>
									{{ \Carbon\Carbon::parse($kunjungan->approved_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i') }}
								</div>
						    @elseif($kunjungan->status_setujui === 'Ditolak' && $kunjungan->rejectapproveBy && $kunjungan->rejectapprove_at)
								<div class="fw-semibold mb-1">
									<span class="badge bg-danger text-capitalize px-3 py-2">
										Ditolak
									</span>
								</div>
								<div class="text small">
									oleh <strong>{{ $kunjungan->rejectapproveBy->name }}</strong><br>
									{{ \Carbon\Carbon::parse($kunjungan->rejectapprove_at)->locale('id')->setTimezone('Asia/Jakarta')->translatedFormat('j F Y H:i') }}
								</div>
							@endif
						</div>
					</div>
				@endif

			</div>

		</div>
	</div>



				
</div> 
@endsection