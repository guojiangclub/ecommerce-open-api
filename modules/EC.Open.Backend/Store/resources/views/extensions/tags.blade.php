<div class="form-group {!! !$errors->has($errorKey) ?: 'has-error' !!}">

    <label for="{{$id}}" class="col-sm-2 control-label">{{$label}}</label>

    <div class="{{$viewClass['field']}}">

        @include('admin::form.error')

        <input class="form-control" type="text" name="{{$name}}" id="tags_{{$name}}" value="{{ old($column, $value) }}">

        @include('admin::form.help-block')

    </div>
</div>