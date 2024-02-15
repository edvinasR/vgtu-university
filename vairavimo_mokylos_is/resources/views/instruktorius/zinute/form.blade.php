<div class="form-group {{ $errors->has('tema') ? 'has-error' : ''}}">
    <label for="tema" class="col-md-4 control-label">{{ 'Tema' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="tema" type="text" id="tema" value="{{ $zinute->tema or ''}}" required>
        {!! $errors->first('tema', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('zinute') ? 'has-error' : ''}}">
    <label for="zinute" class="col-md-4 control-label">{{ 'Zinute' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="zinute" type="textarea" id="zinute" required>{{ $zinute->zinute or ''}}</textarea>
        {!! $errors->first('zinute', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('mokinys') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>

    <div class="col-md-6">
        <select class="form-control" name="mokinys" type="number" id="mokinys" value="{{ $zinute->mokinys or ''}}" required>
        @foreach( $mokiniai as $key => $mokinys)
    		<option value={{$key}} {{isset($zinute->mokinys) && $key ==  $zinute->mokinys?  'selected="selected"': ''}}>{{$mokinys}}</option>
	    @endforeach
	    </select>
        {!! $errors->first('mokinys', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
