@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide" >
            @include('instruktorius.sidebar')

            <div class="col-md-9" id="cal_container">
                <div class="panel panel-default" style="min-height: -webkit-fill-available;">
                    <div class="panel-heading">Praktinių pamokų įvertinimai</div>
                    <div class="panel-body">
                        <div id="kalendorius"></div>

                        <br/>
                   </div>
                </div>
            </div>
        </div>
    </div>
    
     <div class="modal fade" id="ivertinimaiRedagavimas" role="dialog">
	    <div class="modal-dialog">
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Keisti įvertinimą</h4>
	        </div>
	        <div class="modal-body">
  
	                    <form id="ivertinimaiAtnaujinimoForma" method="POST" action="/instruktorius/ivertinimai" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                          
                            {{ csrf_field() }}
                            @include ('instruktorius.ivertinimai.editForm')
                        </form>
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
	        </div>
	      </div>  
	    </div>
  </div>
  
  <script>
	function populateModal(event,type){

		$('.errorMsg').addClass('hide');
		$('#naikinti').addClass('hide');
		if(event.ivertinimo_id == null)
		{
			$("#ivertinimaiAtnaujinimoForma").attr('method', "POST");	
			$("#ivertinimaiAtnaujinimoForma").attr('action', "/instruktorius/ivertinimai");	
		}
		else
		{
			$('#naikinti').removeClass('hide');
			$("#ivertinimaiAtnaujinimoForma").attr('method', "PATCH");	
			$("#ivertinimaiAtnaujinimoForma").attr('action', "/instruktorius/ivertinimai/"+event.ivertinimo_id);
		}
		$("#paskaitaEdit").val(event.id);
		$('#mokinysEdit option[value='+event.mokinio_id+']').attr('selected','selected');
		$("#ivertinimasEdit").val(event.ivertinimas);
		$("#aprasymasEdit").val(event.aprasymas);		
	}
// DuomenÅ³ Å¡altinis kalendoriui
   var source= function(start, end, timezone, callback) { 
       $.ajax({
       	type: 'GET',
   		dataType: 'JSON',
   		contentType: 'application/json',
   		url: '/instruktorius/mokinio_ivertinimai/'+start.unix()+'/'+end.unix(),
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
                               mokinys: dataObject.mokinys,
                               ivertinimas: dataObject.ivertinimas,
                               aprasymas: dataObject.aprasymas,
                               ivertinimo_id: dataObject.ivertinimo_id
                      
                             }); 
           		   } 		   
           	   }
	   
   			}    
              callback(events);
           }
       });
   };
   
   $(document).ready(function() {

	   // ivertinimai Ä¯raÅ¡o atnaujinimo forma
	   $("#ivertinimaiAtnaujinimoForma").submit(function(e) {
		   	$('.errorMsg').addClass('hide');
		      e.preventDefault(); 
		       $.ajax({
		              type: $("#ivertinimaiAtnaujinimoForma").attr('method'),
		              url: $("#ivertinimaiAtnaujinimoForma").attr('action'),
	         	    	beforeSend: function (xhr) {
	           			  var token = $('meta[name="csrf-token"]').attr('content');
	           			  if (token) {
	           				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
	           			 }
	           		  } ,
		              data: $("#ivertinimaiAtnaujinimoForma").serialize(), // serializes the form's elements.
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
		   if(confirm("Ar tikrai norite ištrinti šį įvertinimą?"))
		       $.ajax({
		              type: "DELETE",
		              url: $("#ivertinimaiAtnaujinimoForma").attr('action'),
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


		// Kalendroiaus sukurimas
	   var calendar =$('#kalendorius').fullCalendar({
			locale: 'lt',
			events: source,
			timeFormat: 'H:mm',
			eventLimit: true, // for all non-agenda views
				    
			header: {
			 	left: 'prev,next,today',
			    center: 'title',
				right: 'Eksportuoti'
			 },
			editable:false,
			firstDay: 1,
			eventRender: function (event, element, view) {
				    element.find('.fc-content').attr({"data-toggle":"tooltip","title": event.aprasymas});
					// Tooltip creation from template
					var start = ""+event.pradzia;
					var end = ""+event.pabaiga;
				    var templateHTML='<div class="tooltip" role="tooltip"><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:18px;font-weight: bold;  ">'+event.mokinys+' įvertinimas: '+(event.ivertinimas== null? "":event.ivertinimas)+'</div><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:16px;font-weight: bold;  ">'+start.substring(0,16)+" - "+end.substring(0,16)+'</div><div class="tooltip-tittle" style="text-align:center; font-size:14px">Paskaita: '+(event.title== null? "":event.title)+' </div></div></div>';	    
				    var tooltip= element.find('.fc-content').tooltip({
				  	   animated : 'fade',
				  	   placement : 'auto bottom',
				  	   container: 'body',
				  	   template: templateHTML  	   
				  	});
		
			 },
			eventClick: function(calEvent, jsEvent, view) {
			    	 populateModal(calEvent);
					 $('#ivertinimaiRedagavimas').modal('show');
				    	
			 },
			displayEventEnd : true,
			eventLimitClick : 'popover'
	}); 
});
</script>
@endsection
