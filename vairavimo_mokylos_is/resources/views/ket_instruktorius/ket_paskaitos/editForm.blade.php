<div class="form-group ">
    <label for="data" class="col-md-4 control-label">{{ 'Data' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="data" type="text" id="dataEdit" value="" readonly>      
        <p class="help-block errorMsg hide"></p>
    	</div>
</div>

<div class="form-group ">
    <label for="pavadinimas" class="col-md-4 control-label">{{ 'Pavadinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pavadinimas" type="text" id="pavadinimasEdit" value="" required>
      	<p class="help-block errorMsg hide"></p>
    </div>
</div><div class="form-group ">
    <label for="vieta" class="col-md-4 control-label">{{ 'Vieta' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="vieta" type="text" id="vietaEdit" value="" required>
   		<p class="help-block errorMsg hide"></p>
   	 </div>
</div>
<div class="form-group ">
    <label for="pradzia" class="col-md-4 control-label">{{ 'Pradzios laikas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pradzia" type="time" id="pradziaEdit" value="" required>
		<p class="help-block errorMsg hide"></p>
    </div>
</div><div class="form-group ">
    <label for="pabaiga" class="col-md-4 control-label">{{ 'Pabaigos laikas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pabaiga" type="time" id="pabaigaEdit" value="" required>
  		<p class="help-block errorMsg hide"></p>
    </div>
</div><div class="form-group ">
    <label for="aprasymas" class="col-md-4 control-label">{{ 'Aprasymas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="aprasymas" type="textarea" id="aprasymasEdit" required></textarea>
      	<p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="grupes" class="col-md-4 control-label">{{ 'Grupes' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="grupiu_pasirinkmimas[]"  id="grupiu_pasirinkmimasEdit" style="width:100%;"  multiple>
        @foreach($grupes as $key => $grupe)
        	<option value={{$key}}>{{$grupe}}</option>
        @endforeach
        </select>
        
		<p class="help-block errorMsg hide"></p>
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-2">
        <input class="btn btn-primary" type="submit" value="{{  'Išsaugoti' }}">
        
    </div>
        <div class="col-md-2">
        <input class="btn btn-danger" type="button" id="naikiniti" value="{{  'Ištrinti' }}">   
    </div>
</div>
