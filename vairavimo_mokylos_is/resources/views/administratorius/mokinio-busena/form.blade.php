<div class="form-group {{ $errors->has('teorinio_egzamino_ivertinimas') ? 'has-error' : ''}}">
    <label for="teorinio_egzamino_ivertinimas" class="col-md-4 control-label">{{ 'Teorinio Egzamino Ivertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="teorinio_egzamino_ivertinimas" type="number" id="teorinio_egzamino_ivertinimas" value="{{ $mokiniobusena->teorinio_egzamino_ivertinimas or ''}}" >
        {!! $errors->first('teorinio_egzamino_ivertinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('praktinio_egzamino_ivertinimas') ? 'has-error' : ''}}">
    <label for="praktinio_egzamino_ivertinimas" class="col-md-4 control-label">{{ 'Praktinio Egzamino Ivertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="praktinio_egzamino_ivertinimas" type="number" id="praktinio_egzamino_ivertinimas" value="{{ $mokiniobusena->praktinio_egzamino_ivertinimas or ''}}" >
        {!! $errors->first('praktinio_egzamino_ivertinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('mokinys') ? 'has-error' : ''}} {{isset($atnaujinamas)?'nematomas':''}}">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="mokinys" type="number" id="mokinys" value="{{ $mokiniobusena->mokinys or ''}}" >
       	@foreach($mokiniai as $key => $mokinys)
       		<option value ={{$key}}> {{$mokinys}}</option>
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
