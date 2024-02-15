<div class="form-group {{ $errors->has('teorinio_egzamino_ivertinimas') ? 'has-error' : ''}}">
    <label for="teorinio_egzamino_ivertinimas" class="col-md-4 control-label">{{ 'KET egzamino įvertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="teorinio_egzamino_ivertinimas" type="text" id="teorinio_egzamino_ivertinimas" value="{{ $busena or ''}}" required>
        {!! $errors->first('teorinio_egzamino_ivertinimas', '<p class="help-block">:message</p>') !!}
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
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ 'Atnaujinti mokinio būseną' }}">
    </div>
</div>
