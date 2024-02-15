    // 
      var rinkmenos = [];
      var zymejimas = false;
      var previewNode = null;
      var previewTemplate = null;
      var myDropzone = null;
    //
    function isInArray(value, array) {
       return  jQuery.inArray( value, array ) !== -1;
    }
    // Rinkmenų perkelimo juos tempiant vadymas
    //--------------------------------------------------
      function dragStart(event) {
        event.dataTransfer.setData("id", event.target.id);
    }

    function dragging(event) {
    }

    function allowDrop(event) {
        event.preventDefault();
    }

    function fileUpload(mode) 
    {
        if(mode){
            $('#side_window_header').css('width','100%');
            $(".info").addClass('drop');
        }else{
            $('#side_window_header').css('width','350px');
            $(".info").removeClass('drop');    
        }
    }


    function drop(event) {
        event.preventDefault();
        var source = event.dataTransfer.getData("id");
        var target = event.target.id;

        $.ajax({
            url: '/file/move/'+target,
            type: 'PUT',
            data: {'files': [source]},
            success: function(result) {
                window.location.reload();
            },
            error: function(xhr, status) {

            }
        });

    }
  //------------------------------------------------
  //Duomenų užkrovimo animacija
  //------------------------------------------------
    function showLoader(show){
            if(show){
                $(".grid-item").hide();
                $(".list_icons_table").hide();
                $(".loading").show();
            }else{        
                $(".grid-item").show();
                $(".list_icons_table").show();
                $(".loading").hide();
            }
    }
  //------------------------------------------------
  // Sukuriama rusiavimo ir paieskos nuoroda
  function sukurtiNuorodosStruktura(){
    var e = document.getElementById("paieska");
    var ivestasTekstas = e.value;
    var e2 = document.getElementById("rusiavimas");
    var filtras = e2.options[e2.selectedIndex].value;
    var parametruKiekis = 0;
    var url = window.location.href.substr(0, window.location.href.indexOf('?'));

    if(ivestasTekstas != ''){
        parametruKiekis++;
        url =url+"?search="+ivestasTekstas;
    }
    if(filtras != ''){
        url = url + (parametruKiekis == 0 ? "?" : "&")+filtras;
    }window.location.href
     window.location.href = url;
}
// Gaudomas mygtuko paspaudimo ivykis
document.getElementById('vykdyti_paieska').onclick = function() {
    sukurtiNuorodosStruktura();
};
// Gaunams rusiavimo saraso pasirinkimo ivykis
var rusiavimas = document.getElementById("rusiavimas");
rusiavimas.addEventListener("change", function(item) {
    sukurtiNuorodosStruktura();   
});
// keleto rinkmenų atsisuntimo AJAX užkalsua
function atsiustiRinkmenuArchyva(files, url){

    console.log(files);
    $(".toast_msg").fadeOut();
    $.ajax({
        url: url,
        type: 'POST',
        data: {'files': files},
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
        success: function(result) {
            var message = result.message;
            $("#msg").text(message);
            $(".toast_msg").fadeIn();
           
            setTimeout(function(){   $(".toast_msg").fadeOut(); }, 6000);
        }
    }); 
}
//---------------------------------------------------
//KOTEKSTINIU MENIU FUNKCIJU REALIZACIJOS
// Rinkmenų pereklimo langas
function atidarytiPerkelimoLanga(files){
    // 
    fileUpload(false);
    $('#side_window_header').text("PASIRINKITE TIKSLĄ");
    $("#informacijos_laukas").html('');
    $("#informacijos_laukas").append('<div style="overflow: auto;height:400px;"><div id="medis"></div></div><button id="move" class="context-button btn btn-success">Perkelti</button>');
    $('.info').show("slide", { direction: "right" }, 400);
    $.get( "/folders/"+files[0], function( data ){
        $('#medis').jstree({ 'core' : {
            'data' : data.tree
        }});       
    });

    $('body').on('click', '#move', function() {
        var pasirinkta_direktorija = $("#medis").jstree("get_selected",true);
        if(pasirinkta_direktorija.length == 0){
            window.alert("Privalote pasirkinti tikslo aplankalą");
            return;
        }
        showLoader(true);
        $.ajax({
            url: '/file/move/'+pasirinkta_direktorija[0].id,
            type: 'PUT',
            data: {'files': rinkmenos},
            success: function(result) {
                window.location.reload();
            },
            error: function(xhr, status) {
                showLoader(false);

            }
        }); 
    });
    // Perkelimo uzklasua i backenda
};
// Naujo aplankalo kūrimo langas
function atidarytiNaujoAplankaloKurimoLanga(folderId){
    fileUpload(false);
    //TODO callsas i naujo aplankalo kurimui
    $('#side_window_header').text("ĮVESKITE PVADINIMĄ");
    $("#informacijos_laukas").html('');
    $("#informacijos_laukas").append('<input type="text" style="width:100%;" id="new_folder"/><button id="create_folder" class="context-button  btn btn-success">Sukurti</button>');
     $('.info').show("slide", { direction: "right" }, 400);

     //-----------------
     $('body').on('click', '#create_folder', function() {
        $(".toast_msg").fadeOut();
        showLoader(true);
        $.ajax({
            url: '/file/create/folder/'+folderId,
            type: 'POST',
            data: {'folder_name': $("#new_folder").val()},
            success: function(result) {
                $('.info').hide("slide", { direction: "right" }, 400);  
                var message = result.message;
                $("#msg").text(message);
                showLoader(false);
                $(".toast_msg").fadeIn();  
                setTimeout(function(){   
                    $(".toast_msg").fadeOut(); 
                }, 6000);
            
            },
            error: function(xhr, status) {

                showLoader(false);
            }
        }); 
    });
};
//Rinkmenos įkelimo langas
function atidarytiRinkmenosIkelimoLanga(folderId){

    //TODO callsas i BAKENDA failo ikelimui
    fileUpload(true);
    $('#side_window_header').text("NUVILKITE RINKMENAS Į PLOTĄ ESANTĮ ŽEMIAU:");
    $("#informacijos_laukas").html('');
    $("#informacijos_laukas").append('<div class="bulk_buttons" style="display:none;"><button class="btn btn-primary startAll">Įkelti viską</button><button class="btn btn-danger stopAll" >Ištrinti viską </button></div>')
    $("#informacijos_laukas").append('<div class="table table-striped files uploads_table"  id="preview"></div>');
    $(".uploads_table").addClass('box');
    $('.info').fadeIn(300);
   

    if(myDropzone != null){
        myDropzone.destroy();
        myDropzone = null;
    }

     myDropzone = new Dropzone('.info', { 
        url: "/file/"+folderId+rootUrl, // Set the url
        thumbnailWidth: 80,
        thumbnailHeight: 80,
        maxFilesize: 5000,
        parallelUploads: 5,
        previewTemplate: previewTemplate,
        autoQueue: false, // Make sure the files aren't queued until manually adde
        headers: {'X-CSRF-Token' : $('meta[name="csrf-token"]').attr('content')},
        previewsContainer: "#preview", // Define the container to display the previews
        dictDefaultMessage: "Rinkmena nusiusta į serverį. Artimiausiu metu rinkmena bus perkelta į debesį"
       
       
    });
  

    myDropzone.on("addedfile", function(file) {
        $(".uploads_table").removeClass('box');
        // Hookup the start button
        $('.bulk_buttons').show();
        file.previewElement.querySelector(".start").onclick = function() { myDropzone.enqueueFile(file); };
      });
      
      myDropzone.on("sending", function(file) {
        // Show the total progress bar when upload starts
         document.querySelector(".progress-bar").style.opacity = "1";
        file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
      });

      myDropzone.on("cancel", function(file) {
        myDropzone.removeFile(file);
      });

      myDropzone.on("success", function(file) {
         myDropzone.removeFile(file); 
        
      });

      myDropzone.on("uploadprogress", function(file,progress) {
        if(progress == 100){
            file.previewElement.classList.add('dz-success');
        }
        
     });


      myDropzone.on("reset", function (file) {
        $('.bulk_buttons').hide();
        $.ajax({
            url: '/update_storage_info',
            type: 'GET',
            success: function(result) {
              //  window.location.reload();
            },
            error: function(xhr, status) {
             //  window.location.reload();

            }
        }); 
        window.location.reload();
    });

      
      document.querySelector(".stopAll").onclick = function() {
        $('.bulk_buttons').hide();
        $(".uploads_table").addClass('box');
        myDropzone.enqueueFiles(myDropzone.removeAllFiles(true));
      
      };
      document.querySelector(".startAll").onclick = function() {
        myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
      };
     
    
};
// Rinkmenų peervadinimo langas
function atidarytiPervardinimoLanga(fileId,name){
    //TODO callsas i BAKENDA
    fileUpload(false);
    $('#side_window_header').text("ĮVESKITE PAVADINIMĄ");
    $("#informacijos_laukas").html('');
    $("#informacijos_laukas").append('<input type="text" style="width:100%;" id="file_name" value="'+name+'"/><button id="rename" class="context-button  btn btn-success">Pervardinti</button>');
    $('body').on('click', '#rename', function() {
        showLoader(true);
        $.ajax({
            url: '/file/'+fileId,
            type: 'PUT',
            data: {'pavadinimas': $("#file_name").val()},
            success: function(result) {
                var message = result.message;
                $("#msg").text(message);
                showLoader(false);

                if(result.data.queued){
                    $(".toast_msg").fadeIn();
                    $('.info').hide("slide", { direction: "right" }, 400);  
                    setTimeout(function(){   
                        $(".toast_msg").fadeOut();   
                    }, 6000);
                }else{
                    window.location.reload();
                }
                
            },
            error: function(xhr, status) {
                showLoader(false);

            }
        }); 
    });
    $('.info').show("slide", { direction: "right" }, 400);
};
// Rinkemnos istrynimo langas
function atidarytiRinkmensSitrynimoLanga(files){
    fileUpload(false);
    $('#side_window_header').text("Ar Tikrai Norite Išrinti?");
    $("#informacijos_laukas").html('');
    $("#informacijos_laukas").append('<span class="patvirtinimas">Bus ištrinta '+files.length +' rinkmenų</span><div style ="margin 10px 10px; text-align:center;"><button id="reject_delete"  style="width: 53px;" class="btn btn-danger">Ne</button><button id="confirm_delete" data-file="'+files[0]+'" class="btn btn-success" style="margin-left:10px;">Taip</button></div>');
    $("#reject_delete").click(function(){
        $('.info').hide("slide", { direction: "right" }, 400);
        return;
    });
    $('body').on('click', '#confirm_delete', function() {
        showLoader(true);
        $.ajax({
            url: '/file',
            type: 'DELETE',
            data: {'files': rinkmenos},
            success: function(result) {
                var message = result.message;
                $("#msg").text(message);
                showLoader(false);

                if(result.data.queued){
                    $(".toast_msg").fadeIn();
                    $('.info').hide("slide", { direction: "right" }, 400);  
                    setTimeout(function(){   
                        $(".toast_msg").fadeOut();   
                    }, 6000);
                }else{
                    window.location.reload();
                }

               
            },
            error: function(xhr, status) {
                window.location.reload();

            }
        }); 
    });


    //TODO callsas į BAKENDA istrynimui
    $('.info').show("slide", { direction: "right" }, 400);
};
// Rinkmenos informacija
function gautiInformacija(fileId){
    fileUpload(false);
    $('#side_window_header').text("INFORMACIJA");
    $("#informacijos_laukas").html('');
    $('.info').show("slide", { direction: "right" }, 400);
    $.get( "/file/"+fileId, function( data ){
        var fileInfo = data.file_info;
        $("#informacijos_laukas").append('<span class="bold">Pavadinimas:</span> <span class="info_text">'+fileInfo.name+'</span><br><span class="bold">Dydis:</span><span class="info_text"> '+data.size+'</span><br><span class="bold">Plėtinys:</span><span class="info_text"> '+fileInfo.extension+'</span>');
        if (fileInfo.hasOwnProperty('advanced_info')) { 
            var advanced = fileInfo.advanced_info;
 
            $("#informacijos_laukas").append('<br><span class="bold">Saugyklos tipas:</span><span class="info_text"> '+advanced.saugykla+'</span>');
            $("#informacijos_laukas").append('<br><span class="bold">Saugyklos pavadinimas:</span><span class="info_text"> '+advanced.saugyklos_pavadinimas+'</span>');
            $("#informacijos_laukas").append('<br><span class="bold">Saugykla priklauso:</span><span class="info_text"> '+advanced.priklauso+'</span>');
        }

        if(fileInfo.extension == 'a_folder' && rootUrl != ""){
      
            $("#informacijos_laukas").append('<br><span class="bold">Nuoroda:</span><span class="info_text"><a class="download_link download_archive"  href="" data-id="'+fileInfo.id+'"  data-url="'+'/files/content/archived'+rootUrl+'" download>Atsiųsti rinkmeną &darr;</a></span>' );
        }else if (fileInfo.extension == 'a_folder'  &&  rootUrl == ""){
            $("#informacijos_laukas").append('<br><span class="bold">Nuoroda:</span><span class="info_text"><a class="download_link download_archive" href="" data-id="'+fileInfo.id+'"  data-url="'+'/files/content/archived" download>Atsiųsti rinkmeną &darr;</a></span>' );
        }else if(fileInfo.chunked == 1){
            $("#informacijos_laukas").append('<br><span class="bold">Nuoroda:</span><span class="info_text"><a class="download_link download_archive"  href="" data-id="'+fileInfo.id+'"  data-url="'+'/files/content/archived" download>Atsiųsti rinkmeną &darr;</a></span>' );
        }else{
            $("#informacijos_laukas").append('<br><span class="bold">Nuoroda:</span><span class="info_text"><a class="download_link" href="'+'/file/'+fileInfo.id+'/content" download>Atsiųsti rinkmeną &darr;</a></span>' );
        }   
        $("#informacijos_laukas").append('<br><span class="bold">Paskutinį karta atnaujintas:</span><span class="info_text"> '+fileInfo.updated_at+'</span>');
        if(fileInfo.hasOwnProperty('advanced_info') && advanced.paveikslelis){
            $("#informacijos_laukas").append('<br><div style="text-align:center"><img  style="height: 240px;" src="'+advanced.atsisuti+'" img></div>' );
        }
      
    });

    $('body').on('click', '.download_archive', function() {
        event.preventDefault();
        $(".toast_msg").fadeOut();
        var url = $(this).data('url');
        var files = [$(this).data('id')];
        atsiustiRinkmenuArchyva(files, url);
    });
}
// Zymejimo iniciaimas
function pradetiKeletoFailuZymejima(pradeti){
    
    if(pradeti){
        $( ".selectable" ).selectable({
            unselected: function( event, ui ) {
                rinkmenos = [];
                $('.grid-item.ui-selectee.ui-selected').each(function(i, obj) {
                    var value = $(this).data('id');
                    if(!isInArray(value,rinkmenos)){
                        rinkmenos.push(value);
                    }
                }); 
            },
            selected: function( event, ui ) {
                rinkmenos = [];
                $('.grid-item.ui-selectee.ui-selected').each(function(i, obj) {
                    var value = $(this).data('id');
                    if(!isInArray(value,rinkmenos)){
                        rinkmenos.push(value);
                    }
                });   
            }
        });
        zymejimas = true;
    }
    else{
        rinkmenos = [];
        $( ".selectable" ).selectable( "destroy" );
        zymejimas = false;
    };
}
//---------------------------------------------------

//Dešinio klavišo meniu
$( document ).ready(function() {
    showLoader(true);
//
previewNode = document.querySelector("#template");
previewNode.id = "";
previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);
//




    $.contextMenu({
        selector: '.ui-selected', 
        callback: function(key, options) {
            switch(key){
                case "perkelti"  : atidarytiPerkelimoLanga(rinkmenos);
                    break; 
                case "istrinti": atidarytiRinkmensSitrynimoLanga(rinkmenos);
                    break;
                case "atsiusti": atsiustiRinkmenuArchyva(rinkmenos,"/files/content/archived");
                    break;
            }
        },
        items: {
            "atsiusti": {name: "Atsiųsti suarchyvuotą", icon: "download"},
            "istrinti": {name: "Ištrinti viską", icon: "delete"},
            "perkelti": {name: "Perkleti visus", icon: "folder"},
            "step1": "---------"
        }
    });
    // Meniu paspaudus ant rinkmenos arba aplankalo
    $.contextMenu({
        selector: '.context-menu', 
        callback: function(key, options) {
       
            var fileId =  options.$trigger.context.id;
            var name = $(this).data('name');
    
            if(!isInArray(fileId,rinkmenos)){
                rinkmenos.push(fileId);
            }
            switch(key){
                case "perkelti"  : atidarytiPerkelimoLanga(rinkmenos);
                    break; 
                case "pervadinti" : atidarytiPervardinimoLanga(fileId,name);
                    break;
                case "istrinti" : atidarytiRinkmensSitrynimoLanga(rinkmenos);
                    break;
                case "informacija" : gautiInformacija(fileId);
                    break;
            }
            // TODO šioje veitoje reikia siųsti užsklausas į saugyklas SAAS
        },
        items: {
            "pervadinti": {name: "Pervadinti", icon: "edit"},
            "istrinti": {name: "Ištrinti", icon: "delete"},
            "perkelti": {name: "Perkleti", icon: "folder"},
            "step1": "---------",
            "informacija": {name: "Apie"},
        }
    });

    var menuConfig = null;
    if ($("#format_id").hasClass("icon")) {
        menuConfig={
            selector: '.context-menu-main', 
            callback: function(key, options) {
                var directory =  $(this).data('directory')
                switch(key){
                    case "naujas_aplankalas"  : atidarytiNaujoAplankaloKurimoLanga(directory);
                        break; 
                    case "ikelti": atidarytiRinkmenosIkelimoLanga(directory);
                        break;
                    case "zymeti": zymejimas == true ?  pradetiKeletoFailuZymejima(false) :  pradetiKeletoFailuZymejima(true);
                        break;
                }
            },
            items: {
                "naujas_aplankalas": {name: "Naujas aplankalas", icon: "folder"},
                "ikelti": {name: "Įkelti rinkmeną", icon: "add"},
                "zymeti": {name: "Keisti rinkmenų žymejimo režimą"},
                "sep1": "---------"
            }
        };
    }
    else {
        menuConfig={
            selector: '.context-menu-main', 
            callback: function(key, options) {
                var directory =  $(this).data('directory')
                switch(key){
                    case "naujas_aplankalas"  : atidarytiNaujoAplankaloKurimoLanga(directory);
                        break; 
                    case "ikelti": atidarytiRinkmenosIkelimoLanga(directory);
                        break;
                }
            },
            items: {
                "naujas_aplankalas": {name: "Naujas aplankalas", icon: "folder"},
                "ikelti": {name: "Įkelti rinkmeną", icon: "add"},
                "sep1": "---------"
            }
        };
    };
              // Meniu pasapaudus ant tusčios erdvės
    $.contextMenu(menuConfig);

//  Paspaudimų valdymas
//---------------------------------------------------
    $( ".info" ).dblclick(function() {
        $('.info').fadeOut(300);
    });
    $( "#close" ).click(function() {
        $('.info').fadeOut(300);
    });
    jQuery(function($) {
        $('.folder_link').click(function() {
            event.preventDefault();
        }).dblclick(function() {
            window.location = this.href;
            return false;
        });
    }); 
    $("#change_view").click(function(){

        if(!$('.grid-container').hasClass('small_icons')){
            $('.grid-container').addClass('small_icons');  
        }else{
            $('.grid-container').removeClass('small_icons');  
        }
    });



    // Funkcijos
    $.ajaxSetup({
        beforeSend: function(xhr, type) {
            if (!type.crossDomain) {
                xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
            }
        },
    });

    $(window).load(function() {
        $('.section_content').addClass('files_container');
        showLoader(false);
     });
    showLoader(false);

});
