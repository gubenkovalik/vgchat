@extends('chat.lay')
@section('content')
    <h1>{{trans('files.files')}}</h1>
    <style>
        @media screen and (max-width: 500px) {
            .row-action-primary {
                display: none !important;
            }
        }
    </style>

    <div class="panel panel">
        <div class="panel-heading">
            <h3 class="panel-title text-primary"><button onclick="$('#uplpanel').slideToggle(600);" class="btn btn-inverse">{{trans('files.upload')}}</button></h3>
        </div>
        <div class="panel-body" style="display:none;" id="uplpanel">
            <blockquote>100 GB Max</blockquote>
            <form action="/files/upload" class="dropzone" id="uplf" enctype="multipart/form-data">
                <div class="fallback">
                    <input name="file" type="file" multiple />
                </div>
            </form>

            <div class="progress progress-striped active">
                <div class="progress-bar" id="uplbar" style="width: 0%"></div>
            </div>
        </div>
    </div>


    <div class="list-group">
        @foreach($files as $file)
        <div class="list-group-item well">
            <div class="row-action-primary">
                @php
                $ext = \App\Jen\JenCat::getFileExtension($file->file_name);
                @endphp
                <img src="/assets/fti/{{$ext}}.png" alt="{{$ext}}"/>
            </div>
            <div class="row-content" style="width:100% !important;">
                <?php
                    $f = new Symfony\Component\HttpFoundation\File\File($file->path);
                    $mime = $f->getMimeType();
                    ?>
                <h4 class="list-group-item-heading">{{$file->file_name}}</h4>
                <div>

                    @if(strpos($mime, "audio") !== false || \App\Jen\JenCat::getFileExtension($file->file_name) == "mp3")
                        <audio controls src="/files/download/{{$file->access_token}}"></audio>
                    @endif
                </div>
                <span class="u-pull-left"><a title="{{trans('files.download')}}" href="/files/download/{{$file->access_token}}" class="btn btn-sm btn-flat btn-info"><i class="material-icons">cloud_download</i></a></span>
                @if($file->user_id == session()->get('uid'))
                <span class="u-pull-right"><a title="{{trans('files.share')}}" href="javascript:void(0);" onclick="makePublic({{$file->id}})" class="btn btn-sm btn-flat btn-primary"><i class="material-icons" id="icon{{$file->id}}">{{$file->public == 0 ? "lock_outline" : "lock_open"}}</i> <span style="display: none;" id="text{{$file->id}}">{{$file->public == 0 ? "Open" : "Close"}}</span></a></span>
                <span class="u-pull-right"><a title="{{trans('files.delete')}}" href="javascript:void(0);" onclick="deleteFile({{$file->id}})" class="btn btn-sm btn-flat btn-danger"><i class="material-icons">delete</i></a></span>


                <div {!! $file->public == 0 ? ' style="display: none" ' : ' ' !!}class="panel panel-default" id="panel{{$file->id}}">

                    <div class="panel-body">

                        <div class="form-group">
                            <label for="share{{$file->id}}">{{trans('files.share_with')}}</label>
                            <select id="share{{$file->id}}" data-file-id="{{$file->id}}" class="userShare" multiple style="width:100%;display:none;">
                                @foreach($users as $user)
                                    @if($user->id == session()->get('uid')) <? continue; ?> @endif;
                                    <option {{App\Http\FileShares::where('file_id', $file->id)->where('user_id', $user->id)->exists() ? "selected " : ""}}value="{{$user->id}}">{{$user->nickname}}</option>
                                @endforeach
                            </select>
                        </div>
                      <input onclick="this.setSelectionRange(0, this.value.length)" class="form-control text-primary fileurl" value="https://jencat.ml/files/download/{{$file->access_token}}"/>
                    </div>

                </div>
                @endif
            </div>
        </div>
        <div class="list-group-separator"></div>
        @endforeach
    </div>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.3.0/min/dropzone.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css"/>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.2/toastr.min.css"/>

    <script>
        eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('3 w(0){8(K("Удалить файл?")){$.a({9:\'/b/p\',c:\'o\',d:{0:0},}).5(3(r){q.s(u)})}}3 t(0){$.a({9:\'/b/h/\',c:\'k\',d:{0:0},}).5(3(r){8(r==1){$("#f"+0).2(\'G\');$("#6"+0).2(\'5\');$("#4"+0).2(\'H\');$("#7"+0).I(e)}J{$("#f"+0).2(\'D\');$("#6"+0).2(\'x\');$("#4"+0).2(\'A\');$("#7"+0).v(e)}})}B C(\'.z\',{4:3(g){y("F l m j!");i g.n(\'E\')}});',47,47,'id||html|function|text|done|icon2|panel|if|url|ajax|files|type|data|100|icon|trigger|public|return|clipboard|PATCH|copied|to|getAttribute|DELETE|delete|location||reload|makePublic|true|slideUp|deleteFile|lock|alert|fileurl|Open|new|Clipboard|lock_outline|value|Link|lock_open|Close|slideDown|else|confirm'.split('|')));
        $(".userShare").show().select2({
            placeholder: "{{trans('files.select_share')}}",
            allowClear: true
        }).change(function(){
            var _this = this;
            $.ajax({
                url: '/files/share',
                type: 'PATCH',
                data: {file_id: $(_this).attr('data-file-id'), users: $(_this).val()}
            })
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

        var myDropzone = new Dropzone("#uplf",
            {
                url: "/files/upload",
                withCredentials: true,
                maxFilesize: 104000
            }
        );

        console.log(myDropzone.options);

        myDropzone.on("complete", function(file) {
            setTimeout(function(){

                alert("ok!")
            }, 500);

        });
        myDropzone.on("uploadprogress", function(obj, PROGRESS) {
            $("#uplbar").width(PROGRESS+"%");
        });
        $(function() {
            $("img.lazy").lazyload();
        });

    </script>
@stop