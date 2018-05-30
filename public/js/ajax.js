$(document).ready(function(){
    var url = "/servers";
    var services_url = "/services";
    //display modal form for server editing
    $('.open-modal').click(function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "GET",
            url: url + '/' + server_id,
            success: function (data) {
                //console.log(jQuery.parseJSON(data));
                var res = jQuery.parseJSON(data);
                $('#server_id').val(res['data']['id']);
                $('#address').val(res['data']['attributes']['parameters']['address']);
                $('#port').val(res['data']['attributes']['parameters']['port']);
                $('#protocol').val(res['data']['attributes']['parameters']['protocol']);
                $('#btn-save').val("update");

                $('#server').modal('show');
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //display modal form for creating new server
    $('#btn-add').click(function(){
        $('#btn-save').val("add");
        $('#addserver').trigger("reset");
        $('#server').modal('show');
    });

    //delete server and remove it from list
    $('.delete-server').click(function(){
        var server_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "DELETE",
            url: url + '/' + server_id,
            success: function (data) {
                console.log(data);

                $("#server" + server_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //create new server / update existing server
    $("#btn-save").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        e.preventDefault(); 

        var formData = {
            server_id: $('#server_id').val(),
            address: $('#address').val(),
            port: $('#port').val(),
            protocol: $('#protocol').val(),
            services: $('#services').val(),
            monitors: $('#monitors').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#btn-save').val();

        var type = "POST"; //for creating new resource
        var server_id = $('#server_id').val();;
        var my_url = url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + server_id;
        }

        console.log(formData);

        $.ajax({

            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);

                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#servers-list').append(server);
                }else{ //if user updated an existing record

                    $("#server" + server_id).replaceWith(server);
                }

                $('#addserver').trigger("reset");

                $('#server').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.stop-service').click(function(){
        var service_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: services_url + '/' + service_id,
            data: ({type: 'stop'}),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                //$("#server" + server_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.start-service').click(function(){
        var service_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: services_url + '/' + service_id,
            data: ({type: 'start'}),
            dataType: 'json',
            success: function (data) {
                console.log(data);

                //$("#server" + server_id).remove();
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
});