function validDoi(doi) {
  $("#loading").show();
  clearForm();

  if (validateMyAjaxInputs()) {
    $.ajax({
      url: templateUrl + "/wp-content/themes/agimpacts/xmldoicreator.php",
      type: "POST",
      data: {doi: doi},
      success: function(result) {
        xmlDoiReader(result);
      },
      complete: function() {
        $("#loading").fadeOut('slow');
//      $("#result").show();
      }
    });
  } else {
    $("#loading").fadeOut('slow');
  }
}

function validateMyAjaxInputs() {
  $.validity.start();
  $("#doi").require("Doi is required to validate");
  var result = $.validity.end();
  return result.valid;
}

function saveArticle(form) {
  $.ajax({
    url: templateUrl + "/wp-content/themes/agimpacts/saveArticle.php?" + form,
    type: "POST",
    success: function(result) {
      if (!isNaN(result) && !isNaN(parseInt(result, 10))) {
        var n = noty({
          layout: 'top',
          type: 'success',
          timeout: 6000,
          text: 'Saved data'
        });
//        $("#article_id").val(result);
        $(location).attr('href', templateUrl + '/articleDetail?article=' + result);
//        $('#articleForm')[0].reset();
      } else {
//        alert(result);
        var n = noty({
          layout: 'top',
          type: 'error',
          timeout: 6000,
          text: 'Could not save data'
        });
      }
    },
    complete: function() {
      $("#loading").fadeOut('slow');
    }
  });
}

//function categoryChosen(id, form, page) {
//  page = page || 1;
//  document.location.hash = "category=" + id + ((form) ? "/" + form : "");
//  if (transport)
//    transport.postMessage("category=" + id + ((form) ? "/" + form : ""));
//  $.ajax({
//    url: "result.php?" + form,
//    type: "POST",
//    data: {category: id, page: page},
//    success: function(result) {
//      $("#loading").show();
//      $("#result").hide();
//      $("#result").html(result);
//    },
//    complete: function() {
//      $("#loading").fadeOut('slow');
//      $("#result").show();
//    }
//  });
//}

function xmlDoiReader(data) {
  if (window.DOMParser) {
    parser = new DOMParser();
    xmlDoc = parser.parseFromString(data, "text/xml");
  } else {// Internet Explorer
    xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
    xmlDoc.async = false;
    xmlDoc.loadXML(data);
  }

  if (data != 'null' && xmlDoc.doctype == null) {
    if (xmlDoc.getElementsByTagName("title")[0])
      $("#title").val(xmlDoc.getElementsByTagName("title")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("year")[0])
      $("#year").val(xmlDoc.getElementsByTagName("year")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("volume")[0])
      $("#volume").val(xmlDoc.getElementsByTagName("volume")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("full_title")[0])
      $("#journal").val(xmlDoc.getElementsByTagName("full_title")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("first_page")[0])
      $("#pstart").val(xmlDoc.getElementsByTagName("first_page")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("last_page")[0])
      $("#pend").val(xmlDoc.getElementsByTagName("last_page")[0].childNodes[0].nodeValue);
    if (xmlDoc.getElementsByTagName("issue")[0])
      $("#issue").val(xmlDoc.getElementsByTagName("issue")[0].childNodes[0].nodeValue);
    var auts = '';
    auothors = xmlDoc.getElementsByTagName("person_name");
    for (var i = 0, len = auothors.length; i < len; i++) {
      auts += auothors[i].getElementsByTagName("given_name")[0].childNodes[0].nodeValue + ' ' + auothors[i].getElementsByTagName("surname")[0].childNodes[0].nodeValue + ', ';
    }
    $("#author").val(auts);
  } else {
    var n = noty({
      layout: 'top',
      type: 'warning',
      timeout: 6000,
      text: 'DOI not found'
    });
  }
}

function addEstimate(form) {
  form = form || '';
  $("#loading").show();
  id = parseInt($("#estimate_count").val()) + 1;
  $.ajax({
    url: templateUrl + "/wp-content/themes/agimpacts/estimateForm.php?" + form,
    type: "POST",
    data: {id: id},
    success: function(result) {
      $("#estimateDiv").prepend(result);
    },
    complete: function() {
      $("#loading").fadeOut('slow');
      $("#estimate_count").val(id);
//      $("#result").show();
    }
  });
}

function saveAll() {
  var totalforms = $("#estimate_count").val();
  for (i = 1; i <= totalforms; i++) {
    $("#estimateForm" + i).submit();
  }
}

function saveOne(form, article) {
  $.ajax({
    url: templateUrl + "/wp-content/themes/agimpacts/saveAllEstimates.php?article=" + article,
    type: "POST",
    data: form,
    success: function(result) {
//      $("#estimateDiv").append(result);
      if (result != '') {
        var n = noty({
          layout: 'top',
          type: 'error',
          timeout: 6000,
          text: result
        });
//        console.log(result);
      } else {
        location.reload();
      }
    },
    complete: function() {
    }
  });
}

function deleteEstimate(id) {
  if (confirm("Do you want to delete it?")) {
    var estimate = $('#estimateForm' + id).find('input[name=estimate_id]').val();
    $.ajax({
      url: templateUrl + "/wp-content/themes/agimpacts/deleteEstimate.php",
      type: "POST",
      data: {estimate: estimate},
      success: function(result) {
        if (!result) {
          var n = noty({
            layout: 'top',
            type: 'error',
            timeout: 6000,
            text: result
          });
//        console.log(result);
        } else {
          var n = noty({
            layout: 'top',
            type: 'success',
            timeout: 6000,
            text: "Estimate deleted"
          });
          $('#contentEstimate' + id).remove();
//        location.reload();
        }
      },
      complete: function() {
      }
    });
  }
}

function validArticle(article) {
  $.ajax({
    url: templatePath+'/validArticle.php',
    type: "POST",
    data: {article: article},
    success: function(result) {
      if (result != '') {
        var n = noty({
          layout: 'top',
          type: 'error',
          timeout: 6000,
          text: result
        });
//        console.log(result);
      } else {
        location.reload();
      }
    }
          });
}

function clearForm() {
  $("#title").val('');
  $("#journal").val('');
  $("#year").val('');
  $("#author").val('');
  $("#volume").val('');
  $("#issue").val('');
  $("#pstart").val('');
  $("#pend").val('');
  $("#reference").val('');
}
