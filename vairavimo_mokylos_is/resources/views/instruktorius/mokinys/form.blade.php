<div class="form-group {{ $errors->has('praktinio_egzamino_ivertinimas') ? 'has-error' : ''}}">
    <label for="praktinio_egzamino_ivertinimas" class="col-md-4 control-label">{{ 'Praktinio egzamino įvertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="praktinio_egzamino_ivertinimas" type="text" id="praktinio_egzamino_ivertinimas" value="{{ $busena or ''}}" required>
        {!! $errors->first('praktinio_egzamino_ivertinimas', '<p class="help-block">:message</p>') !!}
    </div>
</div>
<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{ 'Atnaujinti mokinio būseną' }}">
    </div>
</div>
