<div class="form-group ">
    <label for="paskaita" class="col-md-4 control-label">{{ 'Paskaita#' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="paskaita" type="text" id="paskaita" value="" readonly>      
    	<p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="mokinys" class="col-md-4 control-label">{{ 'Mokinys' }}</label>
    <div class="col-md-6">
        <select class="form-control" name="mokinys"  id="mokinys" style="width:100%;" disabled="disabled"> 
       	    @foreach($mokiniai as $key => $mokinys)
        		<option value={{$key}}>{{$mokinys}}</option>
        	@endforeach
        </select>
        <p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="ivertinimas" class="col-md-4 control-label">{{ 'Įvertinimas' }}</label>
    <div class="col-md-6">
        <input class="form-control" name="ivertinimas" type="text" id="ivertinimas" value="" >      
    	<p class="help-block errorMsg hide"></p>
    </div>
</div>
<div class="form-group ">
    <label for="aprasymas" class="col-md-4 control-label">{{ 'Aprašymas' }}</label>
    <div class="col-md-6">
        <textarea class="form-control" rows="5" name="aprasymas" type="textarea" id="aprasymas" required></textarea>
 		<p class="help-block errorMsg hide"></p>     
    </div>
</div>


<div class="form-group">
    <div class="col-md-offset-4 col-md-4">
        <input class="btn btn-primary" type="submit" value="{{  'Išsaugoti' }}">
    	
    </div>
</div>
