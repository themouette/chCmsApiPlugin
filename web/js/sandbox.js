!function($, undefined) {
  var url_field, method_field, results_container, http_code_container,
      add_param_btn, send_btn;

  var updateUrlField = function(route_id) {
    if (!Routes || !Routes[route_id]) {
      return;
    }

    var url = Routes[route_id].url;
    url = url.replace(':sf_format', 'json');

    url_field.val(url);
  };

  var prettifyJSONResponse = function(text) {
    try {
      var data = typeof text === 'string' ? JSON.parse(text) : text;
      text = JSON.stringify(data, undefined, ' ');
    } catch (err) {
      text = $('<div>').text(text).html();
    }

    return text;
  };

  var updateHTTPCodeInfo = function(code, msg) {
    msg = msg || '';
    http_code_container.html(code + (msg ? ' ' + msg : ''));

    if (!http_code_container.hasClass('label')) {
      http_code_container.addClass('label');
    }

    // reset
    http_code_container.removeClass('label-success');
    http_code_container.removeClass('label-important');

    if (code === 200) {
      http_code_container
        .addClass('label')
        .addClass('label-success');
    } else {
      http_code_container
        .addClass('label')
        .addClass('label-important');
    }
  };

  var executeRequest = function() {
    var request_url = url_field.val();
    var request_method = method_field.val();

    if (!request_method || !request_url) {
      return;
    }

    var params = {};
    $('.param_tuple').each(function() {
      var key, value;

      key = $('.key', $(this)).val();
      value = $('.value', $(this)).val();

      if (value) {
        params[key] = value;
      }
    });

    var jqXhr = $.ajax({
      url: request_url,
      type: request_method,
      data: params,
      beforeSend: function() {
        send_btn.prepend($('<i>', {'class': 'icon-refresh'}));
        send_btn.attr('disabled', true);
      },
      error: function(xhr) {
        results_container.html(prettifyJSONResponse(xhr.responseText));
        updateHTTPCodeInfo(xhr.status, xhr.statusText);
      },
      success: function(data) {
        results_container.html(prettifyJSONResponse(data));
        updateHTTPCodeInfo(200, 'OK');
      },
      complete: function() {
        send_btn.find('i').remove();
        send_btn.attr('disabled', false);
      }
    });

    // highlight the results
    jqXhr.always(function() {
      prettyPrint && prettyPrint();
    });
  };

  var addParameter = function() {
    var html = '<p class="param_tuple">'+
        '<input type="text" placeholder="Name" class="key">'+
        '<span> = </span>'+
        '<input type="text" placeholder="Value" class="value">'+
        '<i class="icon-minus-sign" style="cursor:pointer"></i>'+
      '</p>';

    add_param_btn.before(html);
  };


  $(function() {
    url_field = $('#url');
    method_field = $('#method');
    results_container = $('#results');
    http_code_container = $('#http_code');
    add_param_btn = $('#new_param_btn');
    send_btn = $('#send');

    // prefill the url field when the selected api method changes
    $('#route').change(function() {
      updateUrlField($(this).val());
    });
    if ($('#route').val()) {
      $('#route').change();
    }

    // execute the request!
    send_btn.click(executeRequest);
    $('#sandbox-form').submit(function() {
      executeRequest();
      return false;
    });

    // add a parameter
    add_param_btn.click(function() {
      addParameter();
      return false;
    });

    $('#sandbox-form').on('click', 'i.icon-minus-sign', function() {
      $(this).parent().remove();
    });
  });
}(window.jQuery);
