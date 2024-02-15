@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row wide" >
            @include('ket_instruktorius.sidebar')

            <div class="col-md-9" id="cal_container">
                <div class="panel panel-default" style="min-height: -webkit-fill-available;">
                    <div class="panel-heading">Įvertinimų langas</div>
                    <div class="panel-body">
                        <div id="kalendorius"></div>
				
                        <br/>
                   </div>
                </div>
            </div>
        </div>
    </div>
    
   
    <div class="modal fade" id="ivertinimuMeniu" role="dialog" style="top: 25%;">
	    <div class="modal-dialog" style="width:400px;">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Pasirikite veiksmą</h4>
	        </div>
	        <div class="modal-body">     
	        <div class="row"> 
	            <div class="col-md-6 text-center">
					<button type="button" class="btn btn-success" id="naujas_ivertinimas">Kurti naują įvertinimą</button>
			    </div>
			    <div class="col-md-6 text-center">
 					<button type="button" class="btn btn-primary" id="perziureti_ivertinimus" >Peržiūrėti įvertinimus</button>
			    </div>
				 
		        
	       </div>
	        </div>
	      </div>
	      
	    </div>
  </div> 
        <div class="modal fade" id="ivertinimoIvedimas" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Įrašyti naują įvertinimą</h4>
	        </div>
	        <div class="modal-body">
	          
	                    <form method="POST"  id="ivertinimoIvedimoForma" action="/ket_instruktorius/ivertinimai" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @include ('ket_instruktorius.ivertinimai.form')

                        </form>
	          
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
	        </div>
	      </div>
	      
	    </div>
  </div>
  
          <div class="modal fade" id="ivertinimuPerziura" role="dialog">
	    <div class="modal-dialog">
	    
	      <!-- Modal content-->
	      <div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal">&times;</button>
	          <h4 class="modal-title">Įvertinimai</h4>
	        </div>
	        <div class="modal-body" id="lentele">
	          	
				         <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Vardas ir pavardė</th><th>Grupė</th><th>Aprašymas</th><th>Įvertinimas</th><th>Veiksmai</th>
                                    </tr>
                                </thead>
                                <tbody id="ivertinimuLentele">

                               
                                </tbody>
                            </table>
                           
                        </div>
	          
	        </div>
	        <div class="modal-footer">
	          <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>
	        </div>
	      </div>
	      
	    </div>
  </div>
  
  


  
  
  <script>

   var eventGlobal = null;

   function paruostiPerziurosLanga(paskaitosId){
	   $("#ivertinimuLentele").html("");
	   var url = '/ket_instruktorius/ivertinimai/paskaita/'+paskaitosId;
		 $.ajax({
	            type: "GET",
	            url: url,
		       	  beforeSend: function (xhr) {
		     			var token = $('meta[name="csrf-token"]').attr('content');
		     			if (token) {
		     				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		     			}
		     	  },
	   
	            success: function(data){
		            
	            	  for (var key in  data.data) {
	            	
		            	  var grupesPav =  data.data[key].grupe;
		            	  var mokiniai = data.data[key].mokiniai;
		            	  for (var key2 in  mokiniai) {
		            		  $("#ivertinimuLentele").append('<tr id="ivertinimas'+mokiniai[key2].id+'"><td>'+mokiniai[key2].id+'</td><td>'+mokiniai[key2].mokinys+'</td><td>'+grupesPav+'</td><td><input id="aprasymas'+mokiniai[key2].id+'" type="text" value="'+mokiniai[key2].aprasymas+'"></td><td><input type="text" style="width: 40px;" id="ivertinimasInput'+mokiniai[key2].id+'" value="'+mokiniai[key2].ivertinimas+'"></td><td><button data-id="'+mokiniai[key2].id+'" class="btn edit btn-primary btn-xs"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button><button data-id="'+mokiniai[key2].id+'" class=" delete btn btn-primary btn-xs"><i class="fa fa-trash" aria-hidden="true"></i></button></td></tr>');	
		            	  }
	  	        		}	
	            }
	        });
	  
	   
	  }
  function populateMokiniaiSelect(){
	  $("#mokinys").find('option').remove().end();
	  $('#mokinys').prop('disabled', true);
	  var id = $('#grupe').val();
	   var url = '/ket_instruktorius/ivertinimai/mokiniai/'+id;
		 $.ajax({
	            type: "GET",
	            url: url,
		       	  beforeSend: function (xhr) {
		     			var token = $('meta[name="csrf-token"]').attr('content');
		     			if (token) {
		     				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
		     			}
		     	  },
	   
	            success: function(data){
				
	          	  for (var key in  data.data) {
	          		$('#mokinys').append('<option value="' + key + '">' +data.data[key] + '</option>');
	        		}	
	          		$('#mokinys').prop('disabled', false); 
	            }
	        });
	}
  $(document).on('change','#grupe',function(){
	  populateMokiniaiSelect();   
 });
  $(document).on('click','.delete',function(){
	  if(confirm("Ar tikrai norite ištrinti šį įvertinimą")){
		var id  = $(this).data("id");
	       $.ajax({
	              type: "DELETE",
	              url: '/ket_instruktorius/ivertinimai/'+id,
	         	  beforeSend: function (xhr) {
	           			var token = $('meta[name="csrf-token"]').attr('content');
	           			if (token) {
	           				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
	           			}
	           	  },
	              success: function(data)
	              {
		              if(data.success){
		            	  $('#ivertinimas'+id).remove();
			          }      
	              }
	            });
		};
	
 });


  $(document).on('click','.edit',function(){

		var id  = $(this).data("id");
		var ivertinimas = $("#ivertinimasInput"+id).val();
		var aprasymas = $("#aprasymas"+id).val();

	       $.ajax({
	              type: "PATCH",
	              url: '/ket_instruktorius/ivertinimai/'+id,
	         	  beforeSend: function (xhr) {
	           			var token = $('meta[name="csrf-token"]').attr('content');
	           			if (token) {
	           				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
	           			}
	           	  },
	           	  data: { "ivertinimas": ivertinimas, "aprasymas":aprasymas},
	              success: function(data)
	              {
		              
	            	  $('#ivertinimas'+id).css('background-color','#e5ffe5');
	              }
	            });
		
	
 });

  function clearDynamicSelects(){

	  $('#mokinys').prop('disabled', true);
	  $('#grupe').prop('disabled', true);
	  $("#grupe").find('option').remove().end();
	  $("#mokinys").find('option').remove().end();
	  
	}	

  
	function populateModal(event){
		clearDynamicSelects();
		$('.errorMsg').addClass('hide');
		var grupes = event.grupes;
		$('#paskaita').val(event.id);
		// ajax cal lto build ggrupes
		 $.ajax({
            type: "POST",
            url: "/ket_instruktorius/ivertinimai/grupes",
	       	  beforeSend: function (xhr) {
	     			var token = $('meta[name="csrf-token"]').attr('content');
	     			if (token) {
	     				return xhr.setRequestHeader('X-CSRF-TOKEN', token);
	     			}
	     	  },
            data: { 'grupes': grupes  },
            success: function(data){

          	  for (var key in  data.data) {
          		$('#grupe').append('<option value="' + key + '">' +data.data[key] + '</option>');
        		}	
          	$('#grupe').prop('disabled', false);          	
      	  populateMokiniaiSelect(); 

            }
        });
		
	}
// Duomenų šaltinis kalendoriui
   var source= function(start, end, timezone, callback) { 
       $.ajax({
       	type: 'GET',
   		dataType: 'JSON',
   		contentType: 'application/json',
   		url: '/ket_instruktorius/ket_paskaitos/'+start.unix()+'/'+end.unix(),
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
                               grupes: dataObject.grupes,
                               grupiu_pav: dataObject.grupiu_pav
                             }); 
           		   } 		   
           	   }
	   
   			}    
              callback(events);
           }
       });
   };


   
   $(document).ready(function() {


	
		 
	   $( "#naujas_ivertinimas" ).click(function() {
		 	 populateModal(eventGlobal);
		 	$('#ivertinimuMeniu').modal('hide')
			 $('#ivertinimoIvedimas').modal('show');
		 });

	   $( "#perziureti_ivertinimus" ).click(function() {
		 	 var paskaitosId = eventGlobal.id;
		 	 paruostiPerziurosLanga(paskaitosId);
		 	$('#ivertinimuMeniu').modal('hide')
			 $('#ivertinimuPerziura').modal('show');
		 });

	   $("#ivertinimoIvedimoForma").submit(function(e) {
		   	$('.errorMsg').addClass('hide');
		      e.preventDefault(); 
		       $.ajax({
		              type: "POST",
		              url: $("#ivertinimoIvedimoForma").attr('action'),
		              data: $("#ivertinimoIvedimoForma").serialize(), // serializes the form's elements.
		              success: function(data)
		              {
			              if(data.success){
				              window.location.reload();
				          }
			              else{
			            	  for (var key in  data.errors) {	
				            	  var id =  "#"+key;           
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
				    var templateHTML='<div class="tooltip" role="tooltip"><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:18px;font-weight: bold;  ">'+event.title+'</div><div class="tooltip-content" style="background-color:black; color:white;  padding: 7px; opacity: 0.85;"><div style="text-align:center; font-size:16px;font-weight: bold;  ">'+start.substring(0,16)+" - "+end.substring(0,16)+'</div><div class="tooltip-tittle" style="text-align:center; font-size:14px">'+event.aprasymas+' </div><div style="text-align:center; font-style: italic;">Vieta: '+event.vieta+'</div><div style="text-align:center; font-style: italic;">'+event.grupiu_pav+'</div></div></div>';	    
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
					var t = (""+calEvent.pabaiga).split(/[- :]/);
					var pabaiga = new Date(Date.UTC(t[0], t[1]-1, t[2], t[3], t[4], t[5])-(7200000));
					var today = new Date();
					today.setDate(today.getDate() - 1);
				    if(pabaiga > today)
				    {
				    	eventGlobal = calEvent;
				    	
						 $('#ivertinimuMeniu').modal('show');
				    }	
			 },
			displayEventEnd : true,
			eventLimitClick : 'popover'
	}); 
});
</script>
@endsection
