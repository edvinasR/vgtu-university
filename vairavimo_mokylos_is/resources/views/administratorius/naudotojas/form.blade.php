<div class="form-group {{ $errors->has('kategorija') ? 'has-error' : ''}}">
    <label for="name" class="col-md-4 control-label">{{ 'Vardas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="name" type="text" id="name" value="{{ $naudotojas->name or ''}}" required>
        {!! $errors->first('name', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('surename') ? 'has-error' : ''}}">
    <label for="grupe" class="col-md-4 control-label">{{ 'Pavardė' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="surename" type="text" id="surename" value="{{ $naudotojas->surename or ''}}" required>
        {!! $errors->first('surename', '<p class="help-block">:message</p>') !!}
    </div>
</div>

	<div class="form-group {{ $errors->has('email') ? 'has-error' : ''}}">
    <label for="email" class="col-md-4 control-label">{{ 'El. paštas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="email" type="email" id="email" value="{{ $naudotojas->email or ''}}" required>
        {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group {{ $errors->has('password') ? 'has-error' : ''}}">
    <label for="password" class="col-md-4 control-label">{{ 'Slaptažodis' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="password" type="text" id="password" value="{{ $naudotojas->password or ''}}" >
        {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
    </div>
</div><div   class="form-group  {{ $errors->has('teises_FK') ? 'has-error' : ''}}">
    <label for="teises_FK" class="col-md-4 control-label">{{ 'Teisės' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="teises_FK" type="number" id="teises_FK" value="{{ $naudotojas->teises_FK or ''}}" required >
       	@foreach($teises as $key =>  $teise)
       		<option value={{$key}} {{isset($naudotojas->teises_FK) && $key ==  $naudotojas->teises_FK?  'selected="selected"': ''}}>{{$teise}}</option>
       	@endforeach
       	</select>
       
        {!! $errors->first('teises_FK', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
