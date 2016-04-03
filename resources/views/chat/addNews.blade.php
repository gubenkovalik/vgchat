@extends('chat.lay')

@section('content')
    <form class="well" action="" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" required="required" name="title" class="form-control" id="title" placeholder="Title">
        </div>
        <div class="form-group">
            <textarea required="required" name="html" class="form-control" id="html" placeholder="Text"></textarea>
        </div>
        <div class="form-group">
            <label for="inputFile" class="col-md-2 control-label">Image</label>
            <a href="javascript:void(0);" class="btn btn-flat btn-success">{{Lang::get('settings.select')}}</a>
            <input id="inputFile"  name="image" accept="image/*" type="file">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success btn-raised">Add<   /button>
        </div>
    </form>
@endsection