
function sendNewSotrageServiceAJAXReq(type){
    $("#settings_error").hide();
    $("#settings_body").html('');
    $("#settings_body").append('<label for="pavadinimas">Pavadinimas (gali būti prisijugimo el. paštas)</label><input type="text" class="settings_input" id="pavadinimas" name="pavadinimas" placeholder="Pavadinimas..." > <input id="disk_submit" type="submit" class="settings_submit" value="Sukurti">');
    $('body').on('click','#disk_submit',function(){
        $.ajax({
            url: '/saugykla',
            type: 'POST',
            headers: {          
                Accept: "text/plain; charset=utf-8"
              } ,
            data: {'pavadinimas': $("#pavadinimas").val(), 'type':type},
            success: function(result) {
        
                window.location.reload();

            },
            error: function(xhr, status) {
                var resp =  JSON.parse(xhr.responseText).errors;
                let text = "";
                for(var propertyName in resp) {
                    text+= resp[propertyName] + " ";
                 }
                 $("#error_text").text(text == "" ? "Neteisingas kliento identifikatroius" : text);
                 $("#settings_error").show();
            }
        });
    });
}

function buildGoogleServiceModal(){
    sendNewSotrageServiceAJAXReq('google');
};

function buildOneDriveServiceModal(){
    sendNewSotrageServiceAJAXReq('onedrive');
};

function buildDropBoxServiceModal(){
    sendNewSotrageServiceAJAXReq('dropbox');
};

$( document ).ready(function() {
        // Funkcijos
        $.ajaxSetup({
            beforeSend: function(xhr, type) {
                if (!type.crossDomain) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                }
            },
        });

        setTimeout(function(){ 
            // Užkraunama papildoma informacija
            $('.table tr').each(function (i, row) {
                var $row = $(row),
                    $freeSpace = $row.find('.freeSpace'),
                    $usedSpace= $row.find('.usedSpace'),
                    $belongsTo = $row.find('.belongsTo');
                //---
                if($row.data('id') !== undefined){
                    $.ajax({
                        url: '/saugykla/'+$row.data('id')+'/about',
                        type: 'GET',
                        success: function(result) {
                            $freeSpace.html(result.data.freeSpace);
                            $usedSpace.html(result.data.usedSpace);
                            $belongsTo.html(result.data.belongsTo);    
                        }
                    });
                }    
            });
         }, 1000);


    $('#type option[value=empty]').attr('disabled', 'disabled').hide();
    // Paspaudus ant tipo langas suformuojamas pagal atitinkmao tiekėjo taisykles
    $("#type").change(function(val){
        $("#settings_error").hide();
        var type = $(this).val();
        switch(type) {
            case 'google': buildGoogleServiceModal();
                break;
            case 'onedrive': buildOneDriveServiceModal();
                break;
            case 'dropbox': buildDropBoxServiceModal();
                break;
        }
    });

    $(".delete").click( function() {
        if(confirm("Ar tikrai norite ištrinti šitą saugyklą?")){
                var url = $(this).data("target");
                $.ajax({
                    url: url,
                    type: 'DELETE',
                    success: function(result) {
                        window.location.reload();
                    }
                });

        }
        console.log();
    });

    $(".saugyklos_pav").change( function() { 
        if( $(this).val() != ''){
            $.ajax({
                url: $(this).data("target"),
                data: { 'name': $(this).val()},
                type: 'PUT'
            });
        }
    });

    $("#set_btn").click(function(val){
        $("#settings_error").hide();
    });

    

});