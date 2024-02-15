<div class="form-group {{ $errors->has('transporto_priemones_numeris') ? 'has-error' : ''}}">
    <label for="transporto_priemones_numeris" class="col-md-4 control-label">{{ 'Transporto Priemones Numeris' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="transporto_priemones_numeris" type="text" id="transporto_priemones_numeris" value="{{ $instruktorius->transporto_priemones_numeris or ''}}" required>
        {!! $errors->first('transporto_priemones_numeris', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('telefonas') ? 'has-error' : ''}}">
    <label for="telefonas" class="col-md-4 control-label">{{ 'Telefonas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="telefonas" type="textarea" id="telefonas" required>{{ $instruktorius->telefonas or ''}}</textarea>
        {!! $errors->first('telefonas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('naudotojas') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="naudotojas" class="col-md-4 control-label">{{ 'Naudotojas' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="naudotojas" type="number" id="naudotojas" value="{{ $instruktorius->naudotojas or ''}}"  >
        	@foreach($instruktoriai as $key => $isntruktorius)
        		<option value= {{$key}}>{{$isntruktorius}} </option>
        	@endforeach
        </select>
        {!! $errors->first('naudotojas', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
