<div class="form-group ">
    <label for="data" class="col-md-4 control-label">{{ 'Data' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="data" type="text" id="data" value="" readonly>      
    	<p class="help-block errorMsg hide"></p>
    </div>
</div>

<div class="form-group ">
    <label for="pavadinimas" class="col-md-4 control-label">{{ 'Pavadinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pavadinimas" type="text" id="pavadinimas" value="" required>
      	<p class="help-block errorMsg hide"></p>
    </div>
</div><div class="form-group ">
    <label for="vieta" class="col-md-4 control-label">{{ 'Vieta' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="vieta" type="text" id="vieta" value="" required>
    </div>
</div>
><div class="form-group ">
    <label for="pradzia" class="col-md-4 control-label">{{ 'Pradžios laikas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pradzia" type="time" id="pradzia" value="" required>
		<p class="help-block errorMsg hide"></p>
    </div>
</div><div class="form-group ">
    <label for="pabaiga" class="col-md-4 control-label">{{ 'Pabaigos laikas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="pabaiga" type="time" id="pabaiga" value="" required>
  		<p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="mokinys" id="mokinys" value="" required>
  		@foreach($mokiniai as $key=> $mokinys)
  			<option value={{$key}}>{{$mokinys}}</option>
  		@endforeach
  		
  		</select>
  		<p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="aprasymas" class="col-md-4 control-label">{{ 'Aprasymas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="aprasymas" type="textarea" id="aprasymas" required></textarea>
 		<p class="help-block errorMsg hide"></p>     
    </div>
</div>

<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="Issaugoti">
    	
    </div>
</div>
