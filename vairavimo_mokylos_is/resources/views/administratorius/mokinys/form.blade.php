<div class="form-group {{ $errors->has('kategorija') ? 'has-error' : ''}}">
    <label for="kategorija" class="col-md-4 control-label">{{ 'Kategorija' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="kategorija" type="text" id="kategorija" value="{{ $mokiny->kategorija or ''}}" required>
        {!! $errors->first('kategorija', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('grupe') ? 'has-error' : ''}}">
    <label for="grupe" class="col-md-4 control-label">{{ 'Grupė' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="grupe" type="number" id="grupe" value="{{ $mokiny->grupe or ''}}" required>
       	@foreach($grupe as $key =>  $grup)

       	<option value={{$key}} {{isset($mokiny->grupe) && $key ==  $mokiny->grupe?  'selected="selected"': ''}}>{{$grup}}</option>
       	@endforeach
       	</select>
       
        {!! $errors->first('grupe', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('vairavimo_instruktorius') ? 'has-error' : ''}}">
    <label for="vairavimo_instruktorius" class="col-md-4 control-label">{{ 'Vairavimo instruktorius' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="vairavimo_instruktorius" type="number" id="vairavimo_instruktorius" value="{{ $mokiny->vairavimo_instruktorius or ''}}" >
       	@foreach($inst as $key =>  $singleInstruktorius)
       		<option value={{$key}} {{isset($mokiny->vairavimo_instruktorius) && $key ==  $mokiny->vairavimo_instruktorius?  'selected="selected"': ''}}>{{$singleInstruktorius}}</option>
       	@endforeach
       </select>
        {!! $errors->first('vairavimo_instruktorius', '<p class="help-block">:message</p>') !!}
    </div>
</div><div   class="form-group  {{ $errors->has('naudotojas') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="naudotojas" class="col-md-4 control-label">{{ 'Naudotojas' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="naudotojas" type="number" id="naudotojas" value="{{ $mokiny->naudotojas or ''}}" required >
       	@foreach($users as $key =>  $singleUser)
       		<option value={{$key}}>{{$singleUser}}</option>
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
