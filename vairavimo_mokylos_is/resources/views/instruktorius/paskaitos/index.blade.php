@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide" >
            @include('instruktorius.sidebar')

            <div class="col-md-9" id="cal_container">
                <div class="panel panel-default" style="min-height: -webkit-fill-available;">
                    <div class="panel-heading">Praktinių pamokų kalendorius</div>
                    <div class="panel-body">
                        <div id="kalendorius"></div>

                        <br/>
                   </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="paskaitosIvedimas" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Sukurti/atnaujniti pamoką</h4>
	        </div>
	        <div class="modal-body">
	          
	                    <form method="POST"  id="paskaitosIvedimoForma" action="/instruktorius/paskaitos" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include ('instruktorius.paskaitos.form')

                        </form>
	          
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
	        </div>
	      </div>
	      
	    </div>
  </div>
  
  
     <div class="modal fade" id="paskaitosRedagavimas" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Atnaujinti pamoką</h4>
	        </div>
	        <div class="modal-body">
  
		                    <form id="paskaitosAtnaujinimoForma" method="POST" action="/instruktorius/paskaitos" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
	                            {{ method_field('PATCH') }}
	                            {{ csrf_field() }}
	                            @include ('instruktorius.paskaitos.editForm')
	
	                        </form>
	          
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
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
	         	<a id="eksportoNuoroda" data-nuoroda="{{ url('/export/praktines_paskaitos/'.Auth::user()->getRoleEntityId()) }}" href="" title="Eksportuoti"><button class="btn btn-primary btn-xs"> Eksportuoti</button></a>
	        </div>
	      </div>
	      
	    </div>
  </div>
  
  <script>
	function populateModal(event,type){
		$('.errorMsg').addClass('hide');
			if(type =="PUT"){
				$("#paskaitosAtnaujinimoForma").attr('action', "/instruktorius/paskaitos/"+event.id);	
				$('#dataEdit').val(event.pabaiga.substr(0,10));
				$("#pavadinimasEdit").val(event.title);
				$("#vietaEdit").val(event.vieta);
				$("#aprasymasEdit").val(event.aprasymas);
				$("#pradziaEdit").val(event.pradzia.substr(11,9));
				$("#pabaigaEdit").val(event.pabaiga.substr(11,9));
				$('#mokinysEdit option[value='+event.mokinio_id+']').attr('selected','selected');
	
			}
			if(type == "POST"){
				$("#pavadinimas").val('');
				$("#vieta").val('');
				$("#aprasymas").val('');
				$("#pradzia").val("00:00");
				$("#pabaiga").val("00:00");		
			}
		}
// DuomenÅ³ Å¡altinis kalendoriui
   var source= function(start, end, timezone, callback) { 
       $.ajax({
       	type: 'GET',
   		dataType: 'JSON',
   		contentType: 'application/json',
   		url: '/instruktorius/paskaitos/'+start.unix()+'/'+end.unix(),
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
           			   		events.push({
                     		   id: dataObject.id,       
                               title:  dataObject.pavadinimas,
                               aprasymas: dataObject.aprasymas,
                               start: dataObject.pradzia,
                               pradzia: dataObject.pradzia,
                               pabaiga: dataObject.pabaiga,
                               end: dataObject.pabaiga,
                               vieta: dataObject.vieta,
                               praktine: dataObject.praktine_paskaita,
                               allDay: false,
                               color: '#3097D1',
                               mokinio_id: dataObject.mokinio_id,
                               mokinys: dataObject.mokinys
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
	   //Eksporto inicialziacijos pabaiga
	   // Paskaitos Ä¯raÅ¡o atnaujinimo forma
	   $("#paskaitosAtnaujinimoForma").submit(function(e) {
		   	$('.errorMsg').addClass('hide');
		      e.preventDefault(); 
		       $.ajax({
		              type: "PATCH",
		              url: $("#paskaitosAtnaujinimoForma").attr('action'),
		              data: $("#paskaitosAtnaujinimoForma").serialize(), // serializes the form's elements.
		              success: function(data)
		              {
			              if(data.success){
				              window.location.reload();
				          }
			              else{
			            	  for (var key in  data.errors) {
				            	  var id = "#"+key+"Edit";
			            		  $(id).next().text(data.errors[key][0]);
			            		  $(id).next().removeClass('hide');
			            		}		      
						   }       
		              }
		            });
		   });
	   // Psskaitos Ä¯raÅ¡o iÅ¡trinimo forma
	   
	   $("#naikiniti").click(function(e) {
		   if(confirm("Ar tikrai norite ištrinti šią praktinio vairavimo pamoką?"))
		       $.ajax({
		              type: "DELETE",
		              url: $("#paskaitosAtnaujinimoForma").attr('action'),
		         	  beforeSend: function (xhr) {
		           			var token = $('meta[name="csrf-token"]').attr('content');
		           			if (token) {
		           				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		           			}
		           	  },
		              success: function(data)
		              {
			              if(data.success){
				              window.location.reload();
				          }      
		              }
		            });
	
		});
	   // Paskaitos Ä¯raÅ¡o skÅ«rimo
	   $("#paskaitosIvedimoForma").submit(function(e) {
		   	$('.errorMsg').addClass('hide');
		      e.preventDefault(); 
		       $.ajax({
		              type: "POST",
		              url: $("#paskaitosIvedimoForma").attr('action'),
		              data: $("#paskaitosIvedimoForma").serialize(), // serializes the form's elements.
		              success: function(data)
		              {
			              if(data.success){
			            	  window.location.reload();
				          }
			              else{
			            	  for (var key in  data.errors) {
				            	  var id = "#"+key;
			            		  $(id).next().text(data.errors[key][0]);
			            		  $(id).next().removeClass('hide');
			            		}		      
						   }       
		              }
		        });
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
				    var templateHTML='<div class="tooltip" role="tooltip"><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:18px;font-weight: bold;  ">'+event.title+'</div><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:16px;font-weight: bold;  ">'+start.substring(0,16)+" - "+end.substring(0,16)+'</div><div class="tooltip-tittle" style="text-align:center; font-size:14px">Mokinys: '+event.mokinys+' </div><div style="text-align:center; font-style: italic;">Vieta: '+event.vieta+'</div></div></div>';	    
				    var tooltip= element.find('.fc-content').tooltip({
				  	   animated : 'fade',
				  	   placement : 'auto bottom',
				  	   container: 'body',
				  	   template: templateHTML  	   
				  	});
		
			 },
			dayClick: function(date, jsEvent, view) {
					var data = new Date(date);
					var today = new Date();
					today.setDate(today.getDate() - 1);
					
				    if(data > today)
				    {
				    	populateModal(null,"POST");
						$('#data').val(data.getFullYear()+'-'+(data.getMonth() + 1) + '-' + data.getDate());
					 	$('#paskaitosIvedimas').modal('show');
				    }
				 },
		
			eventClick: function(calEvent, jsEvent, view) {
					var t = (""+calEvent.pabaiga).split(/[- :]/);
					var pabaiga = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])-(7200000));
					var today = new Date();
					today.setDate(today.getDate() - 1);
				    if(pabaiga > today)
				    {
				    	 populateModal(calEvent,"PUT");
						 $('#paskaitosRedagavimas').modal('show');
				    }	
			 },
			displayEventEnd : true,
			eventLimitClick : 'popover'
	}); 
});
</script>
@endsection
