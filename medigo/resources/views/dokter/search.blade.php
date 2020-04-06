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
        <!-- Bootstrap Switch -->
        <div class="card card-secondary">
            <div class="card-header">
                <h3 class="card-title">Cari Dokter</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label id="title_">Dokter di sekitar</label>
                    <select class="form-control select2" id="search_by_lokasi" style="width: 100%;">

                    </select>
                    <span class='fa fa-spinner fa-spin fa-2x' id="load2x"></span>
                </div>
                <hr>

                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-search"></i> </span>
                        </div>
                        <input class="form-control" type="search" placeholder="Cari nama dokter" aria-label="Search" name="nama_dokter" id="nama_dokter">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- spesialisi Dokter -->
    <div class="col-md-12" id="result_list_spesialis">
        <div class="card card-default">
            <div class="card-header">
                <h3 class="card-title">
                    spesialisi Dokter
                </h3>
            </div>
            <div class="card-body"  id="spesialisList">
            </div>
        </div>
    </div>


    <!-- dokter list -->
    <div class="col-md-12" id="result_dokter">
        <div class="card card-default">
            <div class="card-body" id="dokterList">
                <!-- <div class="col-8">
                    <strong>Skills</strong>
                    <p class="text-muted"><span class="tag tag-danger"><i class="fas fa-hospital mr-3"></i>UI Design</span></p>
                    <p class="text-muted"><span class="tag tag-danger"><i class="fas fa-clock mr-3"></i>UI Design</span></p>
                    <a href="#" class="btn btn-sm btn-primary text-right">
                        <i class="fas fa-arrow-circle-right"></i> buat Janji
                    </a>
                </div>
                <div class="col-4 text-center">
                    <img src="{{ asset('lte/dist/img/user7-128x128.jpg') }}" alt="" class="img-circle img-fluid img-size-60">
                </div> -->
            </div>
        </div>
    </div>

@endsection
<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('lte/plugins/select2/js/select2.full.min.js') }}"></script>
<script>

    $('#result_dokter').hide()
    $('#result_list_spesialis').hide()
    $('#load2x').hide();

    function getDay() {
        let now = new Date()
        let days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu']
        return days[now.getDay()]
    }

    var resLokasiSelected = []

    function get_by_spesialis(id)
    {
        if (resLokasiSelected.length > 0) {
            console.log('resLokasiSelected ', resLokasiSelected);
            let dataPost = {
                _token: "{{ csrf_token() }}",
                lokasi: resLokasiSelected[0].id,
                spesialis: id
            }
            $.ajax({
                url : '/dokter/by/spesialis_lokasi',
                type: "POST",
                data: dataPost,
                dataType: "JSON",
                cache: false,
                success: function(data)
                {
                    let result = JSON.parse(data)
                    console.log(result);
                    var h1Text = $("#nama_spesialis").text()
                    document.getElementById('title_').innerHTML= h1Text + ' di sekitar'
                    $('#result_list_spesialis').hide()
                    $('#result_dokter').show()
                    // console.log(result);

                    let html = '', tersedia = false, text= '', listJadwal= '';
                    if (result.status) {
                        for(let i=0; i < result.data.length; i++){
                            let tempLokasi = result.data[i].lokasi.reduce((r, c) => Object.assign(r, c), {});
                            // let tempJadwal = result.data[i].jadwal.reduce((r, c) => Object.assign(r, c), {});
                            let tempJadwal = result.data[i].jadwal.map(item => {
                                if (getDay() === item.hari) {
                                    text = 'text-success';
                                    tersedia = true;
                                }
                                listJadwal = '<p class="text-muted '+text+' "><span class="tag tag-danger"><i class="fas fa-clock mr-3"></i>Praktik Hari '+item.hari+' ('+item.buka_jam+' - '+item.tutup_jam+')</span></p>'
                                return [listJadwal, tersedia]
                            })

                            let book  = '';
                            if (tempJadwal[1]){
                                book  = '<a href="{{ url('dokter/detail') }}/'+result.data[i]._id+'" type="button" data-data="'+JSON.stringify(result.data[i])+'" class="btn btn-sm btn-success text-right float-right"><i class="fas fa-arrow-circle-right"></i> bisa buat janji online</a>';
                            }else {book = '<a href="#"></a>';}

                            html += '<div class="row callout callout-success">'+
                                        '<div class="col-8">'+
                                            '<strong>'+result.data[i].nama+'</strong>'+
                                            '<p class="text-muted"><span class="tag tag-danger"><i class="fas fa-hospital mr-3"></i>'+tempLokasi.nama+'</span></p>'+
                                            tempJadwal[0].join('') +
                                            book+
                                        '</div>'+
                                        '<div class="col-4 text-center">'+
                                            '<img src="{{ asset('lte/dist/img/user1-128x128.jpg') }}" alt="" class="img-circle img-fluid img-size-60">'+
                                        '</div>'+
                                '</div>';
                        }
                    }else {
                        html += '<h1>'+result.message+'<h1>';
                    }
                    $('#dokterList').fadeIn();
                    $('#dokterList').html(html);
                }
            });
        } else {
            alert('Pilih KOta /kabupaten dulu');
        }
    }

    $(document).ready(function(){

        $('#search_by_lokasi').on('change', function() {
            let data = {
                id: $(".select2 option:selected").val(),
                value: $(".select2 option:selected").text()
            }
            $('#result_dokter').hide()
            load_spesialis()
            console.log('resLokasiSelected', data.id);
            resLokasiSelected.unshift(data)
        })

        $('#nama_dokter').keyup(function(){
            let query = {
                'q' : $(this).val()
            };
            if(query != '')
            {
                $.ajax({
                    url: "{{ route('dokter.search_autocomplete') }}",
                    method:"GET",
                    data: query,
                    dataType: "json",
                    beforeSend: function() {
                        $('#load3x').show();
                    },
                    success:function(data){
                        $('#result_dokter').show()
                        let result = JSON.parse(data)
                        console.log(result);

                        let html = ''
                        if (result.status) {
                            for(let i=0; i < result.data.length; i++){
                                html += '<div class="row callout callout-success">'+
                                    '<div class="col-8">'+
                                        '<strong>'+result.data[i].nama+'</strong>'+
                                        '<p class="text-muted"><span class="tag tag-danger"><i class="fas fa-hospital mr-3"></i>UI Design</span></p>'+
                                        '<p class="text-muted"><span class="tag tag-danger"><i class="fas fa-clock mr-3"></i>UI Design</span></p>'+
                                        '<a href="#" class="btn btn-sm btn-primary text-right">'+
                                            '<i class="fas fa-arrow-circle-right"></i> buat Janji'+
                                        '</a>'+
                                    '</div>'+
                                    '<div class="col-4 text-center">'+
                                        '<img src="{{ asset('lte/dist/img/user1-128x128.jpg') }}" alt="" class="img-circle img-fluid img-size-60">'+
                                    '</div>'+
                                '</div>';
                            }
                        }else {
                            html += '<h1>'+result.message+'<h1>'
                        }
                        $('#dokterList').fadeIn();
                        $('#dokterList').html(html);
                    }
                });
            }
        });

        $('#search_by_lokasi').select2({
            ajax: {
                url: "{{ route('dokter.rumkit') }}",
                dataType: 'json',
                delay: 250,
                minimumInputLength: 3,
                // async:true,
                beforeSend: function() {
                    $('#load2x').show();
                },
                processResults: function (data) {
                    $('#load2x').hide();
                    $('#result_dokter').show()

                    let res = JSON.parse(data)
                    load_spesialis()
                    return {
                        results:  $.map(res.data, function (item) {
                            return {
                                text: item.kabupaten,
                                id: item._id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        function load_spesialis()
        {
            $.ajax({
                url : "{{ route('dokter.spesialis_list') }}",
                type: "GET",
                dataType: "JSON",
                success: function(data)
                {
                    $('#result_list_spesialis').show()
                    let result = JSON.parse(data)
                    console.log(result);
                    let html = ''
                    if (result.status) {
                        for(let i=0; i < result.data.length; i++){
                            html += '<div class="callout callout-success">'+
                            '<h5 id="nama_spesialis">'+result.data[i].nama+'</h5>'+
                            '<p >'+result.data[i].keterangan+'</p>'+
                            '<a type=button href="javascript:void(0);" onclick="get_by_spesialis(\''+result.data[i]._id+'\')" class="btn btn-sm btn-primary float-right">'+
                            '<i class="fas fa-arrow-circle-right"></i>'+
                            '</a></div>';
                        }
                    }else {
                        html += '<h1>'+result.message+'<h1>'
                    }
                    $('#spesialisList').fadeIn();
                    $('#spesialisList').html(html);
                }
            });
        }

        $('#search_by_skill').select2({
            ajax: {
                url: "{{ route('dokter.keahlian') }}",
                dataType: 'json',
                delay: 250,
                minimumInputLength: 3,
                beforeSend: function() {
                    $('#load2x').show();
                },
                processResults: function (data) {
                    $('#load2x').hide();
                    $('#result_dokter').show()

                    let res = JSON.parse(data)
                    console.log('skill ', res.data);

                    return {
                        results:  $.map(res.data, function (item) {
                            return {
                                text: item.nama,
                                id: item._id
                            }
                        })
                    };
                },
                cache: true
            }
        });

        // $(document).on('click', 'li', function(){
        //     $('#nama_dokter').val($(this).text());
        //     $('#dokterList').fadeOut();
        // });
    });
</script>
