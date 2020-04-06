@extends('layouts.app')

@section('css')
    <style type="text/css">
        #hasilpencarianspesialis{ 
          padding: 0px; 
          display: none; 
          position: absolute; 
          max-height: 340px;
          width: 420px;
          overflow: auto;
          border:1px solid #ddd;
          z-index: 1;
        }
        #hasilpencarianpoli{ 
          padding: 0px; 
          display: none; 
          position: absolute; 
          max-height: 330px;
          width: 420px;
          overflow: auto;
          border:1px solid #ddd;
          z-index: 1;
        }
        #daftar-autocomplete-spesialis, #daftar-autocomplete-poli{ 
          list-style:none; 
          margin:0; 
          padding:0; 
          width:100%;
        }
        #daftar-autocomplete-spesialis li, #daftar-autocomplete-poli li {
          padding: 5px 10px 5px 10px; 
          background:#FAFAFA; 
          border-bottom:#ddd 1px solid;
        }
        #daftar-autocomplete-spesialis li:hover, #daftar-autocomplete-poli li:hover,
        #daftar-autocomplete-spesialis li.autocomplete_active, #daftar-autocomplete-poli li.autocomplete_active { 
          background:#2a84ae; 
          color:#fff; 
          cursor: pointer;
        } 
    </style>
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
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/bootstrap-select.min.css') }}">
    
@endsection

@section('header')
	<section class="content-header">
		<h1>Data Dokter</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-folder"></i> Administrator</a></li>
			<li>Master</li>
			<li class="active">Dokter</li>
		</ol>
	</section>
@endsection

@section('content')
    <div class="box box-success">
        <div class="box-header with-border">
        	<h3 class="box-title">Informasi Terdata</h3>
            <button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="data_table" class="table table-striped table-bordered table-hover nowrap dataTable" width="100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No. Induk</th>
                            <th>Nama</th>
                            <th>No. Telp</th>
                            <th>Posisi</th>
                            <th>Spesialis</th>
                            <th>Poliklinik</th>
                            <th>Tanggal Di Buat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal Add dan Edit --}}
    <div class="modal" tabindex="-1" role="dialog" id="modalAdd" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formAdd" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <input type="hidden" id="id" name="id">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">NIP</label>
                                <div class="col-sm-9">
                                    <input type="text" id="nip" name="nip" class="form-control" placeholder="Masukkan NIP" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Dokter</label>
                                <div class="col-sm-9">
                                    <input type="text" id="nama" name="nama" class="form-control" placeholder="Masukkan Nama Dokter" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Tanggal Lahir
                                </label>
                                <div class="col-sm-9">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                        <input type="text" class="form-control" name="tanggal_lahir" id="tanggal_lahir" placeholder="Pilih Tanggal Lahir" required>
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tempat Lahir</label>
                                <div class="col-sm-9">
                                    <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" placeholder="Masukkan Tempat Lahir" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nomor Telepon</label>
                                <div class="col-sm-9">
                                    <input type="text" id="phone" name="phone" class="form-control" placeholder="Masukkan Nomor Telepon" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Jenis Kelamin</label>
                                <div class="col-sm-9">
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                        <option value="">-- Pilih Salah Satu --</option>
                                        @foreach ($kelamin as $item)
                                            <option value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat</label>
                                <div class="col-sm-9">
                                    <textarea name="alamat" id="alamat" class="form-control" placeholder="Masukkan Alamat" required></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Agama</label>
                                <div class="col-sm-9">
                                    <select name="agama" id="agama" class="form-control" required>
                                        <option value="">-- Pilih Salah Satu --</option>
                                        @foreach ($agama as $item)
                                            <option value="{{$item->name}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status Posisi</label>
                                <div class="col-sm-9">
                                    <select name="posisi" id="posisi" class="form-control" required>
                                        <option value="">-- Pilih Salah Satu --</option>
                                        <option value="Tetap">Tetap</option>
                                        <option value="Kontrak">Kontrak</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Spesialis</label>
                                <div class="col-sm-9">
                                    <!-- <select class="form-control selectpicker" data-live-search="true" id="spesialis" name="spesialis" required>
                                    </select> -->
                                    <input type="text" name="namaspesialis" id="namaspesialis" placeholder="Enter cari spesialis" class="form-control">
                                    <div id="hasilpencarianspesialis"></div>
                                    <input type="hidden" name="spesialis" id="spesialis" class="form-control">
                                    <span class="help-block"></span>
                                    <span class='fa fa-spinner fa-spin fa-2x' id="load2x"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Poliklinik</label>
                                <div class="col-sm-9">
                                    <!-- <select class="form-control selectpicker" data-live-search="true" id="poliklinik" name="poliklinik" required>
                                    </select> -->
                                    <input type="text" name="namapoli" id="namapoli" placeholder="Enter cari poliklinik" class="form-control">
                                    <div id="hasilpencarianpoli"></div>
                                    <input type="hidden" name="poliklinik" id="poliklinik" class="form-control">
                                    <span class="help-block"></span>
                                    <span class='fa fa-spinner fa-spin fa-2x' id="load3x"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">STR</label>
                                <div class="col-sm-9">
                                    <input type="text" id="str" name="str" class="form-control" placeholder="Masukkan STR" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">SIP</label>
                                <div class="col-sm-9">
                                    <input type="text" id="sip" name="sip" class="form-control" placeholder="Masukkan SIP" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Delete --}}
    <div class="modal" tabindex="-1" role="dialog" id="modalDelete" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('administrator.master.dokter.destroy', ['delete' => '#']) }}" method="post" id="formDelete">
                	{{ method_field('DELETE') }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Hapus Data Dokter</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menghapus Data Dokter ini ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            <i class="fa fa-close"></i> Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                            <i class="fa fa-check"></i> Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ mix('/css/ajax-bootstrap-select.min.css') }}">
@endsection

@section('js')
    <script src="{{ mix('/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('/js/ajax-bootstrap-select.min.js') }}"></script>
    <script>
        jQuery(document).ready(function($){
            var table = $('#data_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('administrator.master.dokter.index') }}",
                    "type": "POST",
                    "data" : {}
                },
                "language": {
                    "emptyTable": "Tidak Ada Data Tersedia",
                },
                "columns": [
                    {
                       data: null,
                       render: function (data, type, row, meta) {
                           return meta.row + meta.settings._iDisplayStart + 1;
                       },
                       "width": "20px",
                       "orderable": false,
                    },
                    {
                        "data": "nip",
                        "orderable": true,
                    },
                    {
                        "data": "nama",
                        "orderable": true,
                    },
                    {
                        "data": "phone",
                        "orderable": true,
                    },
                    {
                        "data": "posisi",
                        "orderable": true,
                    },
                    {
                        "data": "spesialis_id",
                        render: function (data, type, row){
                            return data.nama
                        },
                        "orderable": true,
                    },
                    {
                        "data": "poliklinik_id",
                        render: function (data, type, row){
                            return data.nama
                        },
                        "orderable": true,
                    },
                    {
                        "data": "createdAt",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp' +
                                '<a href="#" class="delete-btn btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</a> &nbsp' +
                                '<a href="{{ route('administrator.master.dokter.index') }}/detail/'+ row._id +'" class="btn btn-xs btn-info"><i class="fa fa-eye"></i> Detail</a>';
                        },
                        "width": "10%",
                        "orderable": false,
                    }
                ],
                "order": [ 7, 'desc' ],
                "fnCreatedRow" : function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data', JSON.stringify(aData));
                }
            });

            var url = null;
            // add
            $('#btnAdd').click(function () {
                $('#formAdd')[0].reset();
                $('#formAdd .modal-title').text("Tambah Data Dokter");
                $('#formAdd div.form-group').removeClass('has-error');
                $('#formAdd .help-block').empty();
                $('#formAdd button[type=submit]').button('reset');

                $('.selectpicker option:selected').remove();
                $('.selectpicker').selectpicker('refresh');

                $('#formAdd input[name="_method"]').remove();
                url = '{{ route("administrator.master.dokter.store") }}';

                $('#modalAdd').modal('show');
            });

            // Edit
            $('#data_table').on('click', '.edit-btn', function(e){
                $('#formAdd div.form-group').removeClass('has-error');
                $('#formAdd .help-block').empty();
                $('#formAdd .modal-title').text("Ubah Data Dokter");
                $('#formAdd')[0].reset();
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formAdd button[type=submit]').button('reset');

                $('#formAdd').append('<input type="hidden" name="_method" value="PUT">');
                url = "{{ route('administrator.master.dokter.index') }}" + '/update/' + aData._id;

                $('#id').val(aData._id);
                $('#nip').val(aData.nip);
                $('#nama').val(aData.nama);
                $('#tanggal_lahir').val(aData.tanggal_lahir);
                $('#tempat_lahir').val(aData.tempat_lahir);
                $('#phone').val(aData.phone);
                $('#jenis_kelamin').val(aData.jenis_kelamin);
                $('#alamat').val(aData.alamat);
                $('#agama').val(aData.agama);
                $('#posisi').val(aData.posisi);

                $("#spesialis").append($("<option selected='selected'></option>").val(aData.spesialis_id._id).text(aData.spesialis_id.nama)).trigger('change');
                $("#poliklinik").append($("<option selected='selected'></option>").val(aData.poliklinik_id._id).text(aData.poliklinik_id.nama)).trigger('change');

                $('#modalAdd').modal('show');
            });

            // formAdd
            $('#formAdd').submit(function (event) {
                event.preventDefault();
                $('#formAdd button[type=submit]').button('loading');
                $('#formAdd div.form-group').removeClass('has-error');
                $('#formAdd .help-block').empty();

                var _data = $("#formAdd").serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: _data,
                    dataType: 'json',
                    cache: false,

                    success: function (response) {
                        if (response.status) {
                            table.draw();
                            $.toast({
                                heading: 'Sukses',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'success',
                                loader : false
                            });
                            $('#modalAdd').modal('hide');
                        } else {
                            $.toast({
                                heading: 'Error',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false
                            });
                        }
                        $('#formAdd button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if( response.status === 422 ){
                            var data = $('#formAdd').serializeArray();
                            var error = response.responseJSON.data.errors;

                            $.each(data, function(key, value){
                                if(error[key] != undefined){
                                    var elem;
                                    if( $("#formAdd input[name='" + error[key].type + "']").length )
                                        elem = $("#formAdd input[name='" + error[key].type + "']");
                                    else if( $("#formAdd select[name='" + error[key].type + "']").length )
                                        elem = $("#formAdd select[name='" + error[key].type + "']");
                                    else if( $("#formAdd textarea[name='" + error[key].type + "']").length )
                                        elem = $("#formAdd textarea[name='" + error[key].type + "']");

                                    elem.parent().find('.help-block').text(error[key].message);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });
                        }
                        $('#formAdd button[type=submit]').button('reset');
                    }
                });
            });

            // Delete
            $('#data_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDelete').attr('action').replace('#', aData._id);
                $('#modalDelete').modal('show');
            });

            $('#formDelete').submit(function (event) {
                event.preventDefault();

                $('#modalDelete button[type=submit]').button('loading');
                var _data = $("#formDelete").serialize();

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: _data,
                    dataType: 'json',
                    cache: false,

                    success: function (response) {
                        if (response.status) {
                            table.draw();

                            $.toast({
                                heading: 'Sukses',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'success',
                                loader : false
                            });

                            $('#modalDelete').modal('toggle');
                        }
                        $('#modalDelete button[type=submit]').button('reset');
                        $('#formDelete')[0].reset();
                    },
                    error: function(response){
                        $('#formDelete button[type=submit]').button('reset');
                    }
                });
            });

            $('#tanggal_lahir').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
            });

            //autocomplete spesialis
            $("#namaspesialis").keypress(function(e){
                if(e.which == 13) {
                    e.preventDefault();
                    let objReq = {
                        'q' : $(this).val()
                    };
                    $.ajax({
                        url: '/administrator/master/dokter/spesialis',
                        type: "get",
                        data: objReq,
                        dataType: "json",
                        beforeSend: function() {
                            $('#load2x').show();
                        },
                        success: function(data){
                            $("#hasilpencarianspesialis").show();
                            var result = "";
                            result = "<ul id='daftar-autocomplete-spesialis'>";
                            for (let i = 0; i < data.length; i++) {
                                result +=
                                    "<li>" +
                                        "<span id='id' style='display:none;'>"+ data[i]._id +"</span>" +
                                        "<span id='nama'>"+data[i].nama+"</span></br>" +
                                    "</li>";
                            }
                            result += "</ul>";

                            $("#hasilpencarianspesialis").html(result);
                            $('#load2x').hide();
                        }
                    });
                }
            })

            //autocomplete poliklinik
            $("#namapoli").keypress(function(e){
                if(e.which == 13) {
                    e.preventDefault();
                    let objReq = {
                        'q' : $(this).val()
                    };
                    $.ajax({
                        url: '/administrator/master/dokter/poliklinik',
                        type: "get",
                        data: objReq,
                        dataType: "json",
                        beforeSend: function() {
                            $('#load3x').show();
                        },
                        success: function(data){
                            $("#hasilpencarianpoli").show();
                            var result = "";
                            result = "<ul id='daftar-autocomplete-poli'>";
                            for (let i = 0; i < data.length; i++) {
                                result +=
                                    "<li>" +
                                        "<span id='id' style='display:none;'>"+ data[i]._id +"</span>" +
                                        "<span id='nama'>"+data[i].nama+"</span></br>" +
                                    "</li>";
                            }

                            $("#hasilpencarianpoli").html(result);
                            $('#load3x').hide();
                        }
                    });
                }
            })

            $('#nama').on('keyup', function(event) {
                var $t = $(this);
                $t.val(toTitleCase($t.val()));
            });

            $('#tempat_lahir').on('keyup', function(event) {
                var $t = $(this);
                $t.val(toTitleCase($t.val()));
            });

            $('#alamat').on('keyup', function(event) {
                var $t = $(this);
                $t.val(toTitleCase($t.val()));
            });

        });
    </script>

    <script>
        $(document).on("click", "#daftar-autocomplete-spesialis li", function(){
            var id = $(this).find("span#id").html();
            var nama = $(this).find("span#nama").html();
            $("#spesialis").val(id);
            $("#namaspesialis").val(nama);
            $("#hasilpencarianspesialis").hide();
        });
        $(document).click(function(){
            $("#hasilpencarianspesialis").hide();
        });
    </script>

    <script>
        $(document).on("click", "#daftar-autocomplete-poli li", function(){
            var id = $(this).find("span#id").html();
            var nama = $(this).find("span#nama").html();
            $("#poliklinik").val(id);
            $("#namapoli").val(nama);
            $("#hasilpencarianpoli").hide();
        });
        $(document).click(function(){
            $("#hasilpencarianpoli").hide();
        });
    </script>

    <script>
        function toTitleCase(str) {
            return str.split(/\s+/).map(s => s.charAt(0).toUpperCase() + s.substring(1).toLowerCase()).join(" ");
        }
    </script>

@endsection

