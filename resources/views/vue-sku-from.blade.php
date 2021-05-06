<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}" >
    <label for="{{$id}}" class="col-sm-2 control-label" >{{$label}}</label>
    <div class="col-sm-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <span style="color: red;">
            @include('admin::form.error')
        </span>
        <div id="wrapper">
        </div>
        <div class="input-group">
            <input readonly style="background-color: #fff" type="hidden" id="{{$name}}-savedpath" name="{{$name}}" value="{{ old($column, $value) }}" class="form-control title" placeholder="{{$label}}">
        </div>
        @include('admin::form.help-block')
    </div>
</div>