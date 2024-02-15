<div class="form-group {{ $errors->has('paskaita') ? 'has-error' : ''}}">
    <label for="paskaita" class="col-md-4 control-label">{{ 'Paskaita' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="paskaita" type="number" id="paskaita" value="{{ $grupiupaskaito->paskaita or ''}}" required>
        @foreach($paskaitos as $key=>$value)
		        <option value={{$key}}>{{$value}}</option>
	        @endforeach
        </select>
        {!! $errors->first('paskaita', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('grupe') ? 'has-error' : ''}}">
    <label for="grupe" class="col-md-4 control-label">{{ 'Grupė' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="grupe" type="number" id="grupe" value="{{ $grupiupaskaito->grupe or ''}}" required>
	        @foreach($grupes as $key=>$value)
		        <option value={{$key}}>{{$value}}</option>
	        @endforeach
        </select>
        {!! $errors->first('grupe', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
