$(document).ready(function(){
    var url = "/servers";
    var services_url = "/services";
    var monitors_url = "/monitors"
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
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
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
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

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

    $('.table').on('click', '.master', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Master'}),
            dataType: 'json',
            success: function (data) {

                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';
                
                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                alert('Error:', data);
            }
        });
    });

    $('.table').on('click', '.slave', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Slave'}),
            dataType: 'json',
            success: function (data) {
                
                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.maintenance', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Maintenance'}),
            dataType: 'json',
            success: function (data) {

                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.running', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Running'}),
            dataType: 'json',
            success: function (data) {
                
                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.synced', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Synced'}),
            dataType: 'json',
            success: function (data) {
                
                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.ndb', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'NDB'}),
            dataType: 'json',
            success: function (data) {
                
                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.stale', function(){
        var server_id = $(this).val(); 

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: url + '/' + server_id,
            data: ({state: 'Stale'}),
            dataType: 'json',
            success: function (data) {
                
                var server = '<tr id="server' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['parameters']['address'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['statistics']['connections'] + '</td><td>' + data['data']['attributes']['statistics']['total_connections'] + '</td>';
                server += '<td><div class="dropdown"><button class="btn btn-primary btn-xs dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">State<span class="caret"></span></button><ul class="dropdown-menu" aria-labelledby="dropdownMenu1">';
                server += '<li><button type="button" class="btn btn-link btn-xs master" value="' + data['data']['id'] + '">master</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs slave" value="' + data['data']['id'] + '">slave</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs maintenance" value="' + data['data']['id'] + '">maintenance</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs running" value="' + data['data']['id'] + '">running</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs synced" value="' + data['data']['id'] + '">synced</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs ndb" value="' + data['data']['id'] + '">ndb</button></li>';
                server += '<li><button type="button" class="btn btn-link btn-xs stale" value="' + data['data']['id'] + '">stale</button></li></ul>';
                server += '<td><button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                $("#server" + server_id).replaceWith(server);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.stop-service', function(){
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

                var service = '<tr id="service' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['router'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['total_connections']+ '</td><td>' + data['data']['attributes']['connections'] + '</td><td>' + data['data']['attributes']['started'] + '</td>';
                service += '<td><button class="btn btn-success btn-xs btn-detail start-service" value="' + data['data']['id'] + '">Start</button>';
                service += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                service += '</td></tr>';

                $("#service" + service_id).replaceWith(service);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.start-service', function(){
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

                var service = '<tr id="service' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['attributes']['router'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['total_connections']+ '</td><td>' + data['data']['attributes']['connections'] + '</td><td>' + data['data']['attributes']['started'] + '</td>';
                service += '<td><button class="btn btn-warning btn-xs btn-detail stop-service" value="' + data['data']['id'] + '">Stop</button>';
                service += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                service += '</td></tr>';

                $("#service" + service_id).replaceWith(service);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //delete server and remove it from list
    $('.delete-listener').click(function(){
        var listener_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "DELETE",
            url: url + '/' + listener_id,
            success: function (data) {
                console.log(data);

                $("#listener" + listener_id).remove();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
            }
        });
    });

    $('.table').on('click', '.stop-monitor', function(){
        var monitor_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: monitors_url + '/' + monitor_id,
            data: ({type: 'stop'}),
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var servers = "";
                for(i = 0; i < data['data']['relationships']['servers']['data'].length; i++){
                    servers+= data['data']['relationships']['servers']['data'][i]['id'] + " "
                }
                var monitor = '<tr id="monitor' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['type'] + '</td><td>' + data['data']['attributes']['module'] + '</td><td>' + data['data']['attributes']['state']+ '</td><td>' + servers + '</td>';
                monitor += '<td><button class="btn btn-success btn-xs btn-detail start-monitor" value="' + data['data']['id'] + '">Start</button>';
                monitor += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                monitor += '<button class="btn btn-danger btn-xs btn-delete delete-monitor" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                $("#monitor" + monitor_id).replaceWith(monitor);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.start-monitor', function(){
        var monitor_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "PUT",
            url: monitors_url + '/' + monitor_id,
            data: ({type: 'start'}),
            dataType: 'json',
            success: function (data) {
                console.log(data);
                var servers = "";
                for(i = 0; i < data['data']['relationships']['servers']['data'].length; i++){
                    servers+= data['data']['relationships']['servers']['data'][i]['id'] + " "
                }

                var monitor = '<tr id="monitor' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['type'] + '</td><td>' + data['data']['attributes']['module'] + '</td><td>' + data['data']['attributes']['state']+ '</td><td>' + servers + '</td>';
                monitor += '<td><button class="btn btn-warning btn-xs btn-detail stop-monitor" value="' + data['data']['id'] + '">Stop</button>';
                monitor += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                monitor += '<button class="btn btn-danger btn-xs btn-delete delete-monitor" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                $("#monitor" + monitor_id).replaceWith(monitor);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
});