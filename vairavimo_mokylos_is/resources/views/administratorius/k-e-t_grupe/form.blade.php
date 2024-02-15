<div class="form-group {{ $errors->has('kategorija') ? 'has-error' : ''}}">
    <label for="kategorija" class="col-md-4 control-label">{{ 'Kategorija' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="kategorija" type="text" id="kategorija" value="{{ $ket_grupe->kategorija or ''}}" required>
        {!! $errors->first('kategorija', '<p class="help-block">:message</p>') !!}
    </div>
</div><div class="form-group {{ $errors->has('pavadinimas') ? 'has-error' : ''}}">
    <label for="pavadinimas" class="col-md-4 control-label">{{ 'Pavadinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pavadinimas" type="text" id="pavadinimas" value="{{ $ket_grupe->pavadinimas or ''}}" required>
        {!! $errors->first('pavadinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ $submitButtonText or 'Sukurti' }}">
    </div>
</div>
