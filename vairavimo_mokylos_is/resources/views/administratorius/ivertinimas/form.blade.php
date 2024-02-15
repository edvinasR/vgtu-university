<div class="form-group {{ $errors->has('ivertinimas') ? 'has-error' : ''}}">
    <label for="ivertinimas" class="col-md-4 control-label">{{ 'Įvertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="ivertinimas" type="number" id="ivertinimas" value="{{ $ivertinimas->ivertinimas or ''}}" required>
        {!! $errors->first('ivertinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('aprasymas') ? 'has-error' : ''}}">
    <label for="aprasymas" class="col-md-4 control-label">{{ 'Aprašymas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="aprasymas" type="textarea" id="aprasymas" required>{{ $ivertinimas->aprasymas or ''}}</textarea>
        {!! $errors->first('aprasymas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('mokinys') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="mokinys" type="number" id="mokinys" value="{{ $ivertinimas->mokinys or ''}}" required>
    	@foreach($mokiniai as $key => $paskaita)
        		<option value = {{$key}} {{isset($ivertinimas->mokinys) && $key ==  $ivertinimas->mokinys?  'selected="selected"': ''}}>{{$paskaita}}</option>
        @endforeach  
        </select>     
{!! $errors->first('mokinys', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('paskaita') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="paskaita" class="col-md-4 control-label">{{ 'Paskaita' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="paskaita" type="number" id="paskaita" value="{{ $ivertinimas->paskaita or ''}}" required>
        
        	@foreach($paskaitos as $key => $paskaita)
        		<option value = {{$key}} {{isset( $ivertinimas->paskaita) && $key ==   $ivertinimas->paskaita?  'selected="selected"': ''}}>{{$paskaita}}</option>
        	@endforeach
        </select>
        {!! $errors->first('paskaita', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
