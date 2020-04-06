@extends('dashboard')
@section('css')
    <style type="text/css">
    #load2x, #load3x {
        float: right;
        margin-right: 10px;
        margin-top: -40px;
        position: relative;
        z-index: 1;
        display: none;
    }
    </style>
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset('lte/dist/img/user4-128x128.jpg') }}" alt="User profile picture">
                </div>
                <h3 class="profile-username text-center" id="nama_dokter">{{ $dokter['data']['nama'] }}</h3>

                <p class="text-muted text-center">{{ $dokter['data']['jenkel'] }}</p>

                <a href="#" class="btn btn-success btn-block"><b>BUAT JANJI</b></a>
            </div>
            <!-- /.card-body -->
        </div>
    </div>

    <!-- lokasi prraktik -->
    <div class="col-md-12" >
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    Lokasi prakti
                </h3>
            </div>
            <div class="card-body">
                @foreach ($dokter['data']['lokasi'] as $item)
                <div class="callout callout-success">
                    <h5>{{ $item['nama'] }}</h5>
                    <p> <i class="fas fa-map-marker-alt"></i>  {{$item['alamat'] }}</p>
                    <a type="button" href="javascript:void(0);" class="btn btn-sm btn-primary float-right"><i class="fas fa-arrow-circle-right"></i></a>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- spesialis -->
    <div class="col-md-12" >
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    keahlian
                </h3>
            </div>
            <div class="card-body">
                @foreach ($dokter['data']['spesialis'] as $item)
                <div class="callout callout-success">
                    <h5>{{ $item['nama'] }}</h5>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- spesialis -->
    <div class="col-md-12" >
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    penyakit terkait
                </h3>
            </div>
            <div class="card-body">
                @foreach ($dokter['data']['penyakit'] as $item)
                <div class="callout callout-success">
                    <h5>{{ $item['nama'] }}</h5>
                </div>
                @endforeach
            </div>
        </div>
    </div>



@endsection
<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){
    get_dokter_detail("{{ $dokter['data']['_id'] }}")
    function get_dokter_detail(id)
    {
        $.ajax({
            url : "/dokter/detail/{{ $dokter['data']['_id'] }}",
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
                let result = JSON.parse(data)
                console.log('dotke detail', result);

                $('#nama_dokter').innerHTML(result.data.nama).text

                // let html = ''
                // if (result.status) {
                //     for(let i=0; i < result.data.length; i++){
                //         html += '<div class="callout callout-success">'+
                //         '<h5 id="nama_spesialis">'+result.data[i].nama+'</h5>'+
                //         '<p >'+result.data[i].keterangan+'</p>'+
                //         '<a type=button href="javascript:void(0);" onclick="get_by_spesialis(\''+result.data[i]._id+'\')" class="btn btn-sm btn-primary float-right">'+
                //         '<i class="fas fa-arrow-circle-right"></i>'+
                //         '</a></div>';
                //     }
                // }else {
                //     html += '<h1>'+result.message+'<h1>'
                // }
                // $('#spesialisList').fadeIn();
                // $('#spesialisList').html(html);
            }
        });
    }
});
</script>
