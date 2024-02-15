@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide" >
            @include('mokinys.sidebar')

            <div class="col-md-9" id="cal_container">
                <div class="panel panel-default" style="min-height: -webkit-fill-available;">
                    <div class="panel-heading">Mano paskaitos</div>
                    <div class="panel-body">
                        <div id="kalendorius"></div>

                        <br/>
                   </div>
                </div>
            </div>
        </div>
    </div>

     <div class="modal fade" id="eksportas" role="dialog">
	    <div class="modal-dialog" style="margin-right: 100px;">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Eksportuoti tvarkaraštį</h4>
	        </div>
	        <div class="modal-body" style="height: 100px;">	
				<div class="form-group" >
			    	<label for="pradziaEks" class="col-md-4 control-label">{{ 'Pradžia' }}</label>
				    <div class="col-md-6">
							 <input class="form-control" name="pradziaEks" type="date" id="pradziaEks"  />
				    </div>
				</div>
				<div class="form-group" >
			    	<label for="pabaigaEks" class="col-md-4 control-label">{{ 'Pabaiga' }}</label>
				    <div class="col-md-6">
							 <input class="form-control" name="pabaigaEks" type="date" id="pabaigaEks"  />
				    </div>
				</div>  
	        </div>
	        <div class="modal-footer">
	         	<a id="eksportoNuoroda" data-nuoroda="{{ url('/export/mokinys/tvarkarastis/'.Auth::user()->getRoleEntityId()) }}" href="" title="Eksportuoti"><button class="btn btn-primary btn-xs"> Eksportuoti</button></a>
	        </div>
	      </div>
	      
	    </div>
  </div>
  <script>

// DuomenÃ…Â³ Ã…Â¡altinis kalendoriui
   var source= function(start, end, timezone, callback) { 
       $.ajax({
       	type: 'GET',
   		dataType: 'JSON',
   		contentType: 'application/json',
   		url: '/mokinys/paskaitos/'+start.unix()+'/'+end.unix(),
   		beforeSend: function (xhr) {
   			var token = $('meta[name="csrf-token"]').attr('content');
   			if (token) {
   				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
   			}
   		},
           success: function(data) { 
              var events = [];
              for (i = 0; i < data.length; i++) { 
           	   var dataObject=data[i];
           	   if(typeof (dataObject)!== 'undefined'){
           		   if(dataObject){
               		   console.log(dataObject);
           			   		events.push({
                     		   id: dataObject.id,       
                               title:  dataObject.pavadinimas,
                               aprasymas: dataObject.aprasymas,
                               start: dataObject.pradzia,
                               pradzia: dataObject.pradzia,
                               pabaiga: dataObject.pabaiga,
                               end: dataObject.pabaiga,
                               vieta: dataObject.vieta,
                               praktine: dataObject.praktine,
                               allDay: false,
                               color: dataObject.praktine == 1 ? '#3097D1': '#FF6666' ,
                               instruktoriaus_vardas: dataObject.instruktoriaus_vardas,
                               instruktoriaus_numeriai: dataObject.numeriai
                             }); 
           		   } 		   
           	   }
	   
   			}    
              callback(events);
           }
       });
   };
   
   $(document).ready(function() {


		// Eksporto inicialziacija
	   var tomorrow = new Date();
	   tomorrow.setDate(tomorrow.getDate() + 1);
	   document.getElementById('pradziaEks').valueAsDate = new Date();
	   document.getElementById('pabaigaEks').valueAsDate = tomorrow;
	   var initialURL = $("#eksportoNuoroda").data('nuoroda');
		   initialURL =  initialURL+'/'+ $("#pradziaEks").val()+'/'+$("#pabaigaEks").val();
	   $("#eksportoNuoroda").attr('href', initialURL);	

	   $('#pradziaEks').bind('input', function() { 
		    var initialURL = $("#eksportoNuoroda").data('nuoroda');
		    initialURL =  initialURL+'/'+ $("#pradziaEks").val()+'/'+$("#pabaigaEks").val();
	   		$("#eksportoNuoroda").attr('href', initialURL);	
		});
	   $('#pabaigaEks').bind('input', function() { 
		    var initialURL = $("#eksportoNuoroda").data('nuoroda');
		    initialURL =  initialURL+'/'+ $("#pradziaEks").val()+'/'+$("#pabaigaEks").val();
	   		$("#eksportoNuoroda").attr('href', initialURL);
		});


		// Kalendroiaus sukurimas
	   var calendar =$('#kalendorius').fullCalendar({
			locale: 'lt',
			events: source,
			timeFormat: 'H:mm',
			eventLimit: true, // for all non-agenda views
			customButtons: {
		        eksportuoti: {
		            text: 'Eksprtuoti',
		            click: function() {
		            		$("#eksportas").modal("show");
		            }
		        }
		    },
			header: {
			 	left: 'prev,next,today',
			    center: 'title',
				right: 'eksportuoti'
			 },
			editable:false,
			firstDay: 1,
			eventRender: function (event, element, view) {
				    element.find('.fc-content').attr({"data-toggle":"tooltip","title": event.aprasymas});
					// Tooltip creation from template
					var start = ""+event.pradzia;
					var end = ""+event.pabaiga;
					var templateHTML = "";
					if(event.praktine ==1){
						templateHTML='<div class="tooltip" role="tooltip"><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:18px;font-weight: bold;  ">Praktinė paskaita "'+event.title+'"</div><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:16px;font-weight: bold;  ">'+start.substring(0,16)+" - "+end.substring(0,16)+'</div><div class="tooltip-tittle" style="text-align:center; font-size:14px">'+event.aprasymas+' </div><div style="text-align:center; font-style: italic;">Vieta: '+event.vieta+'</div><div style="text-align:center; font-style: italic;">'+event.instruktoriaus_vardas+' Numeriai:'+event.instruktoriaus_numeriai+'</div></div></div>';	    
					}
					else{
						templateHTML='<div class="tooltip" role="tooltip"><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:18px;font-weight: bold;  ">KET paskaita "'+event.title+'"</div><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:16px;font-weight: bold;  ">'+start.substring(0,16)+" - "+end.substring(0,16)+'</div><div class="tooltip-tittle" style="text-align:center; font-size:14px">'+event.aprasymas+' </div><div style="text-align:center; font-style: italic;">Vieta: '+event.vieta+'</div><div style="text-align:center; font-style: italic;">'+event.instruktoriaus_vardas+'</div></div></div>';	
					}
					   
				    var tooltip= element.find('.fc-content').tooltip({
				  	   animated : 'fade',
				  	   placement : 'auto bottom',
				  	   container: 'body',
				  	   template: templateHTML  	   
				  	});
		
			 },
			dayClick: function(date, jsEvent, view) {

				 },
		
			eventClick: function(calEvent, jsEvent, view) {

			 },
			displayEventEnd : true,
			eventLimitClick : 'popover'
	}); 


});
</script>
@endsection
