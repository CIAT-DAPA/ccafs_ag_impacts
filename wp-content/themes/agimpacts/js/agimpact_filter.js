$(document).ready(function() {
  $('#doi').select2({
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(term, page) {
        return {doi: term, option: 1};
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#crop').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          crop: params.term,
          option: 3
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#model').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          model: params.term,
          option: 4,
          crop: $('#crop').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      },
      cache: true
    }
  });

  $('#climate').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          climate: params.term,
          option: 9,
          crop: $('#crop').val(),
          model: $('#model').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#baseline').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          baseline: params.term,
          option: 5,
          suboption: 1,
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#period').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          period: params.term,
          option: 6,
          suboption: 1,
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#scale').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          scale: params.term,
          option: 2,
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val(),
          period: $('#period').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#continents').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          continents: params.term,
          option: 8,
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val(),
          period: $('#period').val(),
          scale: $('#scale').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#regions').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          region: params.term,
          option: 12,
          continent: $('#continents').val(),
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val(),
          period: $('#period').val(),
          scale: $('#scale').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#country').select2({
    placeholder: "Choose an option...",
    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          country: params.term,
          option: 7,
          region: $('#regions').val(),
          continent: $('#continents').val(),
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val(),
          period: $('#period').val(),
          scale: $('#scale').val(),
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });

  $('#adaptation').select2({
    placeholder: "Choose an option...",
//    allowClear: true,
    ajax: {
      url: templatePath + "/options.php",
      dataType: 'json',
      data: function(params) {
        return {
          adaptation: params.term,
          option: 10,
          crop: $('#crop').val(),
          model: $('#model').val(),
          climate: $('#climate').val(),
          baseline: $('#baseline').val(),
          period: $('#period').val(),
          scale: $('#scale').val(),
          subcontinents: $('#subcontinents').val(),
          country: $('#country').val()
        };
      },
      processResults: function(data, page) {
        return {results: data};
      }
    }
  });
  $("#search").click(function() {
    $("#loading").show();
    onchangeSubmit();
    $("#loading").fadeOut('slow');
    /*$('#results').empty();
     $.ajax({
     url: templatePath + "/options.php",
     type: "POST",
     data: "submit=&scale=" + $('#scale').val() + "&crop=" + $('#crop').val() + "&model=" + $('#model').val() + "&baseline=" + $('#baseline').val() + "&period=" + $('#period').val() + "&country=" + $('#country').val() + "&subcontinents=" + $('#continents').val() + "&climate=" + $('#climate').val() + "&adaptation=" + $('#adaptation').val() + "&option=" + 11,
     success: function(datos) {
     $('#results').append(datos);
     //        $("#resulttable").tablesorter({theme: 'green'});
     },
     complete: function() {
     $("#loading").fadeOut('slow');
     }
     });
     $('#results').show();*/
  });
  $("#reset").on("click", function() {
    $('.js-data-ajax').val(null).trigger("change");
  });

  $("#loading").fadeOut('slow');

  $("#mapDropdown").change(function() {
    var $selectedItem = $("option:selected", this),
            mapDesc = $selectedItem.text(),
            mapKey = this.value;
    minColor = '#F75945', maxColor = '#102D4C';
    if (mapKey == 1) {
      //        minColor = '#990041',
//        maxColor = '#990041';
    }

    $.ajax({
      url: templateUrl + "/wp-content/themes/agimpacts/filteredTable.php?type=highmap",
      type: "POST",
      data: $('#filtersh').serialize(),
      success: function(result) {
        if (result == 'null') {
          $('#mapBox').hide();
          $("#map-geochart").empty();
//          $('#map-geochart').attr('style', '');
          var objJSON = [
            ['Country', 'DY'],
          ];
        } else {
          var objJSON = eval("(function(){return " + result + ";})()");
          $('#mapBox').show();
        }
        var data = objJSON;
        $('#map-geochart').highcharts('Map', {
          chart: {
//              borderWidth: 1
          },
          colors: ['rgba(19,64,117,0.05)', 'rgba(19,64,117,0.2)', 'rgba(19,64,117,0.4)',
            'rgba(19,64,117,0.5)', 'rgba(19,64,117,0.6)', 'rgba(19,64,117,0.8)', 'rgba(19,64,117,1)'],
          title: {
            text: 'Projected yield change by country'
          },
          mapNavigation: {
            enabled: true
          },
          legend: {
            title: {
              text: mapDesc + ' projected yield change (%)',
              style: {
                color: (Highcharts.theme && Highcharts.theme.textColor) || 'black'
              }
            },
//              align: 'left',
//              verticalAlign: 'bottom',
//              floating: true,
//              layout: 'vertical',
//              valueDecimals: 0
          },
          colorAxis: {
//              minColor: '#313695',
//              maxColor: '#a50026',
            min: -100,
            max: 100,
            stops: [
              [0, '#a50026'],
              [0.5, '#e7f5ea'],
              [0.9, '#313695']
            ]
//              dataClasses: [{
//                  from: 75,
//                  to: 100,
//                  color: '#313695'
//                }, {
//                  from: 50,
//                  to: 75,
//                  color: '#588cc0'
//                }, {
//                  from: 25,
//                  to: 50,
//                  color: '#a1d1e4'
//                }, {
//                  from: 0,
//                  to: 25,
//                  color: '#e7f5ea'
//                }, {
//                  from: -25,
//                  to: 0,
//                  color: '#fee79b'
//                }, {
//                  from: -50,
//                  to: -25,
//                  color: '#fba25b'
//                }, {
//                  from: -75,
//                  to: -50,
//                  color: '#e34932'
//                }, {
//                  from: -100,
//                  to: -75,
//                  color: '#a50026'
//                }]
          },
          series: [{
              name: 'median',
              data: data[mapKey],
              mapData: Highcharts.geojson(Highcharts.maps['custom/world']),
              joinBy: ['iso-a2', 'code'],
              animation: true,
              name: 'Projected yield change',
                      states: {
                        hover: {
                          color: '#BADA55'
                        }
                      },
              tooltip: {
                //                valueSuffix: '%',
                useHTML: true,
                headerFormat: '<span style="font-weight: bold;">{point.key}</span><br/>',
                pointFormat: '<span style="font-weight: bold;">Median</span>: {point.median:.1f} ± {point.dev:.1f}%<br><span style="font-weight: bold;">Mean</span>: {point.mean:.1f} %<br><span style="font-weight: bold;">Range</span>: [{point.min:.1f}, {point.max:.1f}]<br><span style="font-weight: bold;">N. of stimates</span>: {point.num}'
              }
            }]
        });
      },
      complete: function() {
      }
    })
  });

});

function downloadData() {
  window.open(templatePath + "/agImpact_download.php?scale=" + $('#scale').val() + "&crop=" + $('#crop').val() + "&model=" + $('#model').val() + "&baseline=" + $('#baseline').val() + "&period=" + $('#period').val() + "&country=" + $('#country').val() + "&subcontinents=" + $('#continents').val() + "&climate=" + $('#climate').val() + "&adaptation=" + $('#adaptation').val(), "_blank");
  window.close();
}

function downloadDataCSV() {
  window.open(templatePath + "/agImpact_downloadCSV.php?scale=" + $('#scale').val() + "&crop=" + $('#crop').val() + "&model=" + $('#model').val() + "&baseline=" + $('#baseline').val() + "&period=" + $('#period').val() + "&country=" + $('#country').val() + "&subcontinents=" + $('#continents').val() + "&climate=" + $('#climate').val() + "&adaptation=" + $('#adaptation').val(), "_blank");
  window.close();
}

function viewAllFields() {
  window.open(templatePath + "/allFieldsTable.php?scale=" + $('#scale').val() + "&crop=" + $('#crop').val() + "&model=" + $('#model').val() + "&baseline=" + $('#baseline').val() + "&period=" + $('#period').val() + "&country=" + $('#country').val() + "&subcontinents=" + $('#continents').val() + "&climate=" + $('#climate').val() + "&adaptation=" + $('#adaptation').val() + "&custom=1", "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=10, width=400, height=400");
//  window.close();
}

function viewAllFieldsh() {
  window.open(templateUrl + "/fullview/?scale=" + $('#scale').val() + "&crop=" + $('#crop').val() + "&model=" + $('#model').val() + "&baseline=" + $('#baseline').val() + "&period=" + $('#period').val() + "&country=" + $('#country').val() + "&subcontinents=" + $('#continents').val() + "&climate=" + $('#climate').val() + "&adaptation=" + $('#adaptation').val(), "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=10, left=10, width=1120, height=600");
//  window.close();
}

function onchangeSubmit() {
  $("#resultsx").show();
  $("#ag-menu").show();
  $('#resulttablex').dataTable({
    destroy: true,
    'scrollX': true,
    'jQueryUI': true,
    "processing": true,
    "serverSide": true,
    "ajax": {
      url: templateUrl + "/wp-content/themes/agimpacts/dataTableFilter.php",
      data: function(d) {
        d.crop = $('#crop').val();
        d.model = $('#model').val();
        d.scale = $('#scale').val();
        d.climate = $('#climate').val();
        d.baseline = $('#baseline').val();
        d.period = $('#period').val();
        d.country = $('#country').val();
        d.continents = $('#continents').val();
        d.regions = $('#regions').val();
        d.adaptation = $('#adaptation').val();
      }
    }
  });

  $('#mapDropdown').change();

  $.ajax({
    url: templateUrl + "/wp-content/themes/agimpacts/filteredTable.php?type=columnChart",
    type: "POST",
    data: $('#filtersh').serialize(),
    success: function(result) {
      if (result == 'null') {
        $("#column-chart").empty();
        $('#column-chart').attr('style', '');
        return;
      } else {
        var objJSON = eval("(function(){return " + result + ";})()");
        $('#column-chart').show();
      }
      var data = objJSON;
      $('#column-chart').highcharts({
        chart: {
          zoomType: 'xy'
        },
        title: {
          text: 'Projected yield change by country'
        },
        tooltip: {
          shared: true
        }, yAxis: {// Secondary yAxis
          title: {
            text: 'Yield change (%)',
            style: {
              //                color: Highcharts.getOptions().colors[0]
            }
          },
          labels: {format: '{value} %',
            style: {
              //                color: Highcharts.getOptions().colors[0]
            }
          },
          //            opposite: true
        },
        xAxis: {
          type: 'category',
          labels: {
            rotation: -45,
            style: {
              fontSize: '13px',
              fontFamily: 'Verdana, sans-serif'
            }
          }
        },
        credits: {
          enabled: false
        },
        series: [{
            name: 'Delta Yield',
            type: 'column',
            data: data[0],
            tooltip: {
              pointFormat: '<span style="font-weight: bold; color: {series.color}">ΔYield</span>: <b>{point.y:.1f} %</b> '
            },
            dataLabels: {
              enabled: true, //                rotation: -90,
              color: '#FFFFFF',
              align: 'center',
              format: '{point.y:.1f}', // one decimal
              y: 10, // 10 pixels down from the top
              style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
              }
            }
          }, {
            name: 'Crop error',
            type: 'errorbar',
            //              yAxis: 1,
            data: data[1],
            tooltip: {
              pointFormat: '(error range: ({point.low})-({point.high}) %)<br/>'
            }
          }]
      });
    },
    complete: function() {
    }
  });

  $.ajax({
    url: templateUrl + "/wp-content/themes/agimpacts/filteredTable.php?type=scatterChart",
    type: "POST",
    data: $('#filtersh').serialize(), success: function(result) {
      if (result == 'null') {
        $("#scatter-chart").empty();
        $('#scatter-chart').attr('style', '');
        return;
      } else {
        var objJSON = eval("(function(){return " + result + ";})()");
        $('#scatter-chart').show();
      }
      var data = objJSON;
      $('#scatter-chart').highcharts({
        xAxis: {
          title: {
            text: 'ΔTemperature change(C°)'
          }
        },
        yAxis: {
          title: {
            text: 'ΔYield change (%)'
          }
        },
        tooltip: {
          shared: true
        },
        title: {
          text: 'Yield response by Temperature change'
        },
        series: [{
            type: 'scatter',
            name: 'Temperature',
            data: data[0],
            tooltip: {
              pointFormat: 'Temperature: {point.x:.1f}C° <br/>Yield: {point.y:.1f}%<br/>'
            },
            marker: {
              radius: 4
            }
          }, {
            type: 'spline',
            name: 'Moving average',
            data: data[1],
            tooltip: {
              headerFormat: '',
              pointFormat: 'Yield moving average: {point.y:.2f}%<br/>'
            },
            marker: {
              lineWidth: 2,
              lineColor: Highcharts.getOptions().colors[3],
              fillColor: 'white'
            }
          }]
      });
    }
  });
}