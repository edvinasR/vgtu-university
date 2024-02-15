   // parado tą statistikos bloką kurį tipą yra pasirinkęs naudotojas
    function showBlock(block){
        $('.general_statistics').hide();
        $('.free_storage').hide();
        $('.used_storage').hide();
        $('.double_chart').hide();
        $('.'+block).show();
    }
    // Skirtas nupaišyti Rinkmenų statistikai
	function doughnutChart(data) {
        if ( window.myPie != null){
            window.myPie.destroy();
        }
        if(window.myPie2!= null){
            window.myPie2.destroy();
        }
        var ctx = document.getElementById("myChart").getContext("2d");
        window.myPie = new Chart(ctx, data.kiekis);
        var ct2 = document.getElementById("myChart2").getContext("2d");
        window.myPie2 = new Chart(ct2, data.dydis);
        showBlock('double_chart');
        
    }
    // Skirtas nupaišyti atminties panaudojamumo grafikui
    function storageChart(id, data){
        if(window.myPienew !=null){
            window.myPienew.destroy();
        }
        var ctx = document.getElementById(id).getContext("2d");
        window.myPienew = new Chart(ctx, data);
        showBlock(id);
    }

     function bytesToSize(bytes) {
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        if (bytes == 0) return '0 Byte';
        var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
        return Math.round(bytes / Math.pow(1024, i), 2) + ' ' + sizes[i];
     };

     // Saugykluose saugomų rinkmenų statistika
    function getStorageServiceExtensionStats(service){

        $.ajax({
            url: service == null ? '/busena/extension' : '/busena/extension/' + service,
            type: 'GET',
            success: function(result) {
                var data = result.data.kiekis;
                var data2 = result.data.dydis;
                
                var options = {
                     legend: {
                        display: false
            
                      },
                      pieceLabel: {
                        render: function (args) {

                          var withoutRiightSide = args.label.substring(0, args.label.indexOf(" tipo"));
                          if(withoutRiightSide.indexOf(" ") !== -1){
                            return withoutRiightSide.substring(withoutRiightSide.indexOf(" ")).toUpperCase()+" "+ args.percentage+"%";
                          }
                          return withoutRiightSide.toUpperCase()+" ("+ args.percentage+"%)";
                        },
                        fontSize: 12,
                        fontStyle: 'bold',
                        fontColor: '#4f5f6f',
                        fontFamily: '"Lucida Console", Monaco, monospace'
                      }
                }
                var config = {
                    kiekis:{
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data:  jQuery.map( data, function( i) {return i.value;}),
                                backgroundColor: jQuery.map( data, function( i) {return i.color;}),
                                borderWidth: 0
                            }],
                            labels: jQuery.map( data, function( i) {return i.label;})
                        },
                        options: options
                    },
                    dydis:{
                        type: 'doughnut',
                        data: {
                            datasets: [{
                                data:  jQuery.map( data2, function( i) {return Math.round( (i.value / 1048576)*100)/100}),
                                backgroundColor: jQuery.map( data2, function( i) {return i.color;}),
                                borderWidth: 0
                            }],
                            labels: jQuery.map( data2, function( i) {return i.label+" megabaitais";})
                        },
                        options: options
                    }

                };
                doughnutChart(config);
            }    
        }); 
    }
    // Informacijos apie apjungtos sagyklos laisvą atmintį ir užimtą atminti užkrovimas
    function getStorageStats(type){
        $.ajax({
            url: '/busena/'+type,
            type: 'GET',
            success: function(result) {
                var data = result.data;
                var config = {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data:  jQuery.map( data, function( i) {return Math.round( (i.value / 1048576)*100)/100}),
                            backgroundColor: jQuery.map( data, function( i) {return i.color;}),
                            borderWidth: 0
                        }],
                        labels: jQuery.map( data, function( i) {return i.label;})
                    },
                    options: {
                        showAllTooltips: true,
                        responsive: true
                    }
                };
                storageChart(type,config); 
            }    
        }); 

    }
    // Bendros informacijos apie saugykla užkrovimas
    function getGeneralStatistics(service){

        $.ajax({
            url: service == null ? '/busena/general' : '/busena/general/' + service,
            type: 'GET',
            success: function(result) {
                var totalSpace = 0;
                var usedSpace = 0
                var freeSpace = 0
                $('#general_text').html('');
                result.data.forEach(function(item){
                   
                    if(item.key =="Bendrai užimta atminties" ||item.key == "Užimta atminties"){
                        usedSpace = parseFloat(item.value);
                        $('#general_text').append('<span style="float:left;">'+item.key+'</span><span  style="float:right;">'+bytesToSize(item.value)+'</span><br>');
                    }
                    else if(item.key =="Liko laisvos aminties" ||item.key == "Laisvos atminties"){
                        freeSpace = parseFloat(item.value);
                        $('#general_text').append('<span style="float:left;">'+item.key+'</span><span  style="float:right;">'+bytesToSize(item.value)+'</span><br>');
                    }else{
                        $('#general_text').append('<span style="float:left;">'+item.key+'</span><span  style="float:right;">'+item.value+'</span><br>');
                    }
                    
                   
                });
                totalSpace = usedSpace+ freeSpace;
                $('.general_storage_p').text('Užtimta '+ bytesToSize(usedSpace)+' iš '+bytesToSize(totalSpace));
                $('#myProgress').val(usedSpace/totalSpace * 100);
            
            }    
        }); 
    }

    $( document ).ready(function() {   

//Regstruojams papildinys skritulinems diagramomms
Chart.pluginService.register({
    beforeRender: function (chart) {
      if (chart.config.options.showAllTooltips) {
          // create an array of tooltips
          // we can't use the chart tooltip because there is only one tooltip per chart
          chart.pluginTooltips = [];
          chart.config.data.datasets.forEach(function (dataset, i) {
              chart.getDatasetMeta(i).data.forEach(function (sector, j) {
                  chart.pluginTooltips.push(new Chart.Tooltip({
                      _chart: chart.chart,
                      _chartInstance: chart,
                      _data: chart.data,
                      _options: chart.options.tooltips,
                      _active: [sector]
                  }, chart));
              });
          });
  
          // turn off normal tooltips
          chart.options.tooltips.enabled = false;
      }
  },
    afterDraw: function (chart, easing) {
      if (chart.config.options.showAllTooltips) {
          // we don't want the permanent tooltips to animate, so don't do anything till the animation runs atleast once
          if (!chart.allTooltipsOnce) {
              if (easing !== 1)
                  return;
              chart.allTooltipsOnce = true;
          }
  
          // turn on tooltips
          chart.options.tooltips.enabled = true;
          Chart.helpers.each(chart.pluginTooltips, function (tooltip) {
              tooltip.initialize();
              tooltip.update();
              // we don't actually need this since we are not animating tooltips
              tooltip.pivot();
              tooltip.transition(easing).draw();
          });
          chart.options.tooltips.enabled = false;
      }
    }
  });
        // csrf token įdedamas į kiekvieną užklausą
        $.ajaxSetup({
            beforeSend: function(xhr, type) {
                if (!type.crossDomain) {
                    xhr.setRequestHeader('X-CSRF-Token', $('meta[name="csrf-token"]').attr('content'));
                }
            },
        });
        getStorageStats('free_storage');
        $('#statistics_type').val('free_storage');
    });

    
   // Saugyklos pakeitimas
    $(document).on('change','#service',function(){
        var type = $("#statistics_type").val();
        var val = $(this).val() == "" ? null : $(this).val() ;
        if(type == 'double_chart'){
            getStorageServiceExtensionStats(val);
        }
        if(type == 'general_statistics' ){
            getGeneralStatistics(val)
        }
   });

   // Statistikos tipo sąrašo pakeitimas
   $(document).on('change','#statistics_type',function(){
        var val = $(this).val();
        var service =  $("#service").val() == "" ? null : $("#service").val();
        showBlock(val);
        if(val == 'general_statistics'){
            getGeneralStatistics(service)
        }
        if(val == 'double_chart'){
            getStorageServiceExtensionStats(service);
        }
        if(val == 'free_storage'){
            getStorageStats('free_storage');
        }
        if(val == 'used_storage'){
            getStorageStats('used_storage');
        }
});