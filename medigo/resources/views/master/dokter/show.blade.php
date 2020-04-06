@extends('layouts.app')

@section('header')
	<section class="content-header">
		<h1>Detail Dokter</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-folder"></i> Administrator</a></li>
			<li>Master</li>
			<li>Dokter</li>
			<li class="active">Detail</li>
		</ol>
	</section>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<a href="{{ route('administrator.master.dokter.index') }}" class="pull-left btn btn-warning"><i class="fa fa-reply"></i> Kembali</a>
                </div>
                <div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<strong>
								<i class="fa fa-vcard margin-r-5"></i>
								Nomor Induk
							</strong>
							<p class="text-muted">
								@if( $data->nip != null )
									{{ $data->nip }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-user-md margin-r-5"></i>
								Nama Dokter
							</strong>
							<p class="text-muted">
								@if( $data->nama != null )
									{{ $data->nama }}
								@else
									-
								@endif
							</p>

							<strong>
								<i class="fa fa-calendar margin-r-5"></i>
								Tanggal Lahir
							</strong>

							<p class="text-muted">
								@if( $data->tanggal_lahir != null )
									{{ $data->tanggal_lahir }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-map margin-r-5"></i>
								Tempat Lahir
							</strong>

							<p class="text-muted">
								@if( $data->tempat_lahir != null )
									{{ $data->tempat_lahir }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-venus-mars margin-r-5"></i>
								Jenis Kelamin
							</strong>

							<p class="text-muted">
								@if( $data->jenis_kelamin != null )
									{{ $data->jenis_kelamin }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-user margin-r-5"></i>
								Agama
							</strong>

							<p class="text-muted">
								@if( $data->agama != null )
									{{ $data->agama }}
								@else
									-
								@endif
							</p>
                        </div>

						<div class="col-md-4">
							<strong>
								<i class="fa fa-phone margin-r-5"></i>
								Nomor Telepom
							</strong>
							<p class="text-muted">
								@if( $data->phone != null )
									{{ $data->phone }}
								@else
									-
								@endif
							</p>

							<strong>
								<i class="fa fa-map-marker margin-r-5"></i>
								Alamat
							</strong>

							<p class="text-muted">
								@if( $data->alamat != null )
									{{ $data->alamat }}
								@else
									-
								@endif
							</p>
                        </div>

						<div class="col-md-4">
							<strong>
								<i class="fa fa-users margin-r-5"></i>
								Status Posisi
							</strong>
							<p class="text-muted">
								@if( $data->posisi != null )
									{{ $data->posisi }}
								@else
									-
								@endif
							</p>

							<strong>
								<i class="fa fa-stethoscope margin-r-5"></i>
								Spesialis
							</strong>

							<p class="text-muted">
								@if( $data->spesialis_id != null )
									{{ $data->spesialis_id->nama }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-hospital-o margin-r-5"></i>
								Poliklinik
							</strong>

							<p class="text-muted">
								@if( $data->poliklinik_id != null )
									{{ $data->poliklinik_id->nama }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-circle margin-r-5"></i>
								STR
							</strong>

							<p class="text-muted">
								@if( property_exists($data, 'str') )
									{{ $data->str }}
								@else
									-
								@endif
                            </p>

							<strong>
								<i class="fa fa-circle margin-r-5"></i>
								SIP
							</strong>

							<p class="text-muted">
								@if( property_exists($data, 'sip') )
									{{ $data->sip }}
								@else
									-
								@endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
