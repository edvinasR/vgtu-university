<div class="form-group {{ $errors->has('pavadinimas') ? 'has-error' : ''}}">
    <label for="pavadinimas" class="col-md-4 control-label">{{ 'Pavadinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pavadinimas" type="text" id="pavadinimas" value="{{ $paskaita->pavadinimas or ''}}" required>
        {!! $errors->first('pavadinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('vieta') ? 'has-error' : ''}}">
    <label for="vieta" class="col-md-4 control-label">{{ 'Vieta' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="vieta" type="text" id="vieta" value="{{ $paskaita->vieta or ''}}" required>
        {!! $errors->first('vieta', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('praktine_paskaita') ? 'has-error' : ''}}">
    <label for="praktine_paskaita" class="col-md-4 control-label">{{ 'Praktine Paskaita' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="praktine_paskaita" type="number" id="praktine_paskaita" value="{{ $paskaita->praktine_paskaita or ''}}" required>
	        <option value="0" {{isset($paskaita->praktine_paskaita) && 0 ==  $paskaita->praktine_paskaita?  'selected="selected"': ''}}>Ne</option>
	        <option value="1" {{isset($paskaita->praktine_paskaita) && 1 ==  $paskaita->praktine_paskaita?  'selected="selected"': ''}}>Taip</option>
        </select>
        {!! $errors->first('praktine_paskaita', '<p class="help-block">:message</p>') !!}
        
    </div>
</div><div class="form-group {{ $errors->has('pradzia') ? 'has-error' : ''}}">
    <label for="pradzia" class="col-md-4 control-label">{{ 'Pradzia' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pradzia" type="datetime-local" id="pradzia" value="{{  isset($paskaita) ? date('Y-m-d\TH:i:s', strtotime($paskaita->pradzia)) : ''}}" required>
        {!! $errors->first('pradzia', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('pabaiga') ? 'has-error' : ''}}">
    <label for="pabaiga" class="col-md-4 control-label">{{ 'Pabaiga' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pabaiga" type="datetime-local" id="pabaiga" value="{{ isset($paskaita) ? date('Y-m-d\TH:i:s', strtotime($paskaita->pabaiga)) : '' }}" required>
        {!! $errors->first('pabaiga', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('aprasymas') ? 'has-error' : ''}}">
    <label for="aprasymas" class="col-md-4 control-label">{{ 'Aprasymas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="aprasymas" type="textarea" id="aprasymas" required>{{ $paskaita->aprasymas or ''}}</textarea>
        {!! $errors->first('aprasymas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('instruktorius') ? 'has-error' : ''}}">
    <label for="instruktorius" class="col-md-4 control-label">{{ 'Instruktorius' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="instruktorius" type="number" id="instruktorius" value="{{ $paskaita->instruktorius or ''}}" required>
        	@foreach($instruktoriai as $key => $instruktorius)		
        			<option value={{$key}} {{isset($paskaita->instruktorius) && $key ==  $paskaita->instruktorius?  'selected="selected"': ''}}>{{$instruktorius}}</option>
        	@endforeach
        </select>
        {!! $errors->first('instruktorius', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('mokinys') ? 'has-error' : ''}}">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="mokinys" type="number" id="mokinys" value="{{ $paskaita->mokinys or ''}}" >
        
        	@foreach($mokiniai as $key => $mokinys)		
        			<option value={{$key}} {{isset($paskaita->mokinys) && $key ==  $paskaita->mokinys?  'selected="selected"': ''}}>{{$mokinys}}</option>
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
