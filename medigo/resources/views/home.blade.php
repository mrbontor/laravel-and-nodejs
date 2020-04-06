@extends('dashboard')

@section('content')

<div class="row mb-2">
	<div class="col-sm-6">
		<h1 class="m-0 text-dark">Selamat datang di IHC Mobile</h1>
		<p>Kami memberikan pelayanan komprehensif dan terpadu dengan standar pelayanan terakreditasi</p>
	</div><!-- /.col -->
	<div class="col-sm-6">
		<ol class="breadcrumb float-sm-right">
			<li class="breadcrumb-item"><a href="#">Home</a></li>
			<li class="breadcrumb-item active">Starter Page</li>
		</ol>
	</div><!-- /.col -->
</div><!-- /.row -->
<!-- ./col -->
<div class="row">
	<div class="col-lg-6 col-12">
		<div class="info-box">
			<span class="info-box-icon"><i class="fas fa-plus-square"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Booking Cepat</span>
				<span class="info-box-number">Pesan dokter</span>
			</div>
			<a href="#" class="small-box-footer pull-right">
				<i class="fas fa-arrow-circle-right"></i>
			</a>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<div class="col-lg-6 col-12">
		<div class="info-box">
			<span class="info-box-icon"><i class="fas fa-hospital"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Cari Faskes</span>
				<span class="info-box-number">Rumah Sakit dan Klinik</span>
			</div>
			<a href="#" class="small-box-footer pull-right">
				<i class="fas fa-arrow-circle-right"></i>
			</a>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
	<div class="col-lg-6 col-12">
		<div class="info-box">
			<span class="info-box-icon"><i class="fas fa-stethoscope"></i></span>

			<div class="info-box-content">
				<span class="info-box-text">Cari Dokter</span>
				<span class="info-box-number">Umum dan spesialis</span>
			</div>
			<a href="#" class="small-box-footer pull-right">
				<i class="fas fa-arrow-circle-right"></i>
			</a>
			<!-- /.info-box-content -->
		</div>
		<!-- /.info-box -->
	</div>
</div>
@endsection
