@extends('chat.lay')

@section('title') {{trans('settings.settings')}} - @stop
@section('content')
    <h1></h1>

    <form class="form-horizontal well" action="" method="post" enctype="multipart/form-data">
        @if(session()->has('error'))
            <div class="alert alert-dismissible alert-danger">
                <button type="button" class="close" data-dismiss="alert">×</button>
                {{session()->get('error')}}

            </div>
        @endif
            @if(session()->has('success'))
                <div class="alert alert-dismissible alert-success">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    {{session()->get('success')}}

                </div>@endif
        <fieldset>
            <legend>{{trans('settings.settings')}}</legend>
            <div class="form-group">
                <label for="nck" class="col-md-2 control-label">{{trans('register.nickname')}}</label>

                <div class="col-md-10">
                    <input autocomplete="off" class="form-control" id="nck" placeholder="{{trans('register.nickname')}}" name="nickname" type="text" value="{{$user->nickname}}">
                </div>
            </div>
            <div class="form-group">
                <label for="inputEmail" class="col-md-2 control-label">Email</label>

                <div class="col-md-10">
                    <input autocomplete="off" class="form-control" id="inputEmail" value="{{$user->email}}" placeholder="Email" name="email" type="email">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-md-2 control-label">{{trans('settings.old_pass')}}</label>

                <div class="col-md-10">
                    <input class="form-control" name="oldPass" id="inputPassword" placeholder="{{trans('settings.old_pass')}}" type="password">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword" class="col-md-2 control-label">{{trans('settings.new_pass')}}</label>

                <div class="col-md-10">
                    <input class="form-control" name="newPass" id="inputPassword" placeholder="{{trans('settings.new_pass')}}" type="password">
                </div>
            </div>


            <div class="form-group">

                <label for="inputFile" class="col-md-2 control-label">{{trans('settings.avatar')}}</label>

                <div class="col-md-10">

                    <a href="javascript:void(0);" class="btn btn-flat btn-success">{{trans('settings.select')}}</a>
                    <input id="inputFile"  name="avatar" accept="image/*" type="file">
                </div>
                <a href="javascript:void(0);" id="saveCircle" class="btn btn-flat btn-success">{{trans('settings.crop')}}</a>
                <div id="crpp"></div>

            </div>

            <div class="form-group">
                <div class="col-md-10 col-md-offset-2">

                    <button type="submit" class="btn btn-primary">{{trans('settings.save')}}</button>
                </div>
            </div>
        </fieldset>
    </form>

    <link rel="stylesheet" href="/assets/css/croppie.css" />
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />

   

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
    <script>


        var mc = $('#crpp');
        mc.croppie({
            viewport: {
                width: 150,
                height: 150,
                type: 'circle',
                mouseWheelZoom: true
            }
            // mouseWheelZoom: false
        });
        mc.croppie('bind', '{{$user->avatar}}');
        $('#saveCircle').on('click', function (ev) {
            mc.croppie('result', {
                type: 'canvas',
                format: 'jpeg'
            }).then(function (resp) {
                var $im = resp;
                $.ajax({
                    url: '/settings/crop',
                    type: 'POST',
                    data: {base64: $im}
                }).done(function(r){
                    swal("Success", "OK", "success");
                    location.reload();
                });
            });
        });

        $('#inputFile').on('change', function(){

            // get file and pull attributes
            var input = $(this)[0];
            var file = input.files[0];



            var reader = new FileReader();
            reader.onload = function(e){
                //$('#imgAvatar').attr('src', e.target.result);
                mc.croppie('bind', e.target.result)
            };
            reader.readAsDataURL(file);

        });

        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "onclick": function() { location.href = '/';},
            "positionClass": "toast-bottom-left",
            "preventDuplicates": true,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "7000",
            "extendedTimeOut": "3000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

    </script>
@stop