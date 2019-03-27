$(document).ready(function(){
    var url = "/servers";
    var services_url = "/services";
    var monitors_url = "/monitors"
    var users_url = "/users";
    var settings_url = "/settings";

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
    $('.table').on('click', '.delete-server', function(){
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
                $('div.flash-message').html(data);
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                server += '<button class="btn btn-danger btn-xs btn-delete delete-server" value="' + data['data']['id'] + '">Delete</button></div></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#servers-list').append(server);
                }else{ //if user updated an existing record

                    $("#server" + server_id).replaceWith(server);
                }

                $('#addserver').trigger("reset");

                $('#server').modal('hide')
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);       
            }
        });
    });

     //create new server / update existing server
     $("#add-mon").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        e.preventDefault(); 

        var formData = {
            monitor_id: $('#monitor_id').val(),
            monitor_type: $('#monitor_type').val(),
            module: $('#module').val(),
            monitor_interval: $('#monitor_interval').val(),
            servers: $('#servers').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#add-mon').val();

        var type = "POST"; //for creating new resource
        var monitor_id = $('#monitor_id').val();;
        var my_url = monitors_url;

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + monitor_id;
        }

        console.log(formData);

        $.ajax({

            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {

                var servers = "";
                for(i = 0; i < data['data']['relationships']['servers']['data'].length; i++){
                    servers+= data['data']['relationships']['servers']['data'][i]['id'] + " "
                }
                var monitor = '<tr id="monitor' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['type'] + '</td><td>' + data['data']['attributes']['module'] + '</td><td>' + data['data']['attributes']['state']+ '</td><td>' + servers + '</td>';
                monitor += '<td><button class="btn btn-success btn-xs btn-detail start-monitor" value="' + data['data']['id'] + '">Start</button>';
                monitor += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                monitor += '<button class="btn btn-danger btn-xs btn-delete delete-monitor" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#monitors-list').append(monitor);
                }else{ //if user updated an existing record

                    $("#monitor" + monitor_id).replaceWith(monitor);
                }

                $('#addmonitor').trigger("reset");

                $('#monitor').modal('hide')
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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
                server += '<button class="btn btn-warning btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
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

                var state = '<td id="state'+ data['data']['id'] + '">' + data['data']['attributes']['state'] + '</td>'
                var action = '<td id="action'+ data['data']['id'] + '"><button class="btn btn-success btn-xs btn-detail start-service" value="' + data['data']['id'] + '">Start</button>';
                action += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                action += '<button class="btn btn-danger btn-xs btn-delete delete-service" value="' + data['data']['id'] + '">Delete</button></td>';

                $("#state" + service_id).replaceWith(state);
                $("#action" + service_id).replaceWith(action);
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

                var state = '<td id="state'+ data['data']['id'] + '">' + data['data']['attributes']['state'] + '</td>'
                var action = '<td id="action'+ data['data']['id'] + '"><button class="btn btn-warning btn-xs btn-detail stop-service" value="' + data['data']['id'] + '">Stop</button>';
                action += '<button class="btn btn-info btn-xs btn-detail open-modal" value="' + data['data']['id'] + '">Edit</button>';
                action += '<button class="btn btn-danger btn-xs btn-delete delete-service" value="' + data['data']['id'] + '">Delete</button></td>';

                $("#state" + service_id).replaceWith(state);
                $("#action" + service_id).replaceWith(action);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //create new server / update existing server
    $("#add-service").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        e.preventDefault(); 

        var formData = {
            service_id: $('#service_id').val(),
            service_type: $('#service_type').val(),
            module: $('#service_module').val(),
            user: $('#user').val(),
            password: $('#password').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#add-service').val();

        var type = "POST"; //for creating new resource
        var service_id = $('#service_id').val();
        var my_url = services_url + '/';

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + service_id;
        }

        console.log(formData);

        $.ajax({

            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {
                var service = '<tr id=service' + data['data']['id'] + '"><td>' + '<a href="' + my_url +  data['data']['id'] + '" class="btn btn-primary btn-xs btn-detail service-info" value="' + data['data']['id'] + '">' + data['data']['id'] + '</a></td><td>' + data['data']['attributes']['router'] + '</td><td>' + data['data']['attributes']['state'] + '</td><td>' + data['data']['attributes']['total_connections'] + '</td><td>' + data['data']['attributes']['connections'] + '</td><td>' + data['data']['attributes']['started'] + '</td>';
                service += '<td><button class="btn btn-danger btn-xs btn-delete delete-service" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#services-list').append(service);
                }else{ //if user updated an existing record

                    $("#service" + service_id).replaceWith(service);
                }

                $('#service').trigger("reset");

                $('#service').modal('hide');
            },
            error: function (data) {
                console.log('Error:', data);
                $('#service').modal('hide');
                //$('div.validation').html(data);
            }
        });
    });

     //delete service and remove it from list
     $('.table').on('click', '.delete-service', function(){
        var service_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        var formData = {
            service: service_id
        }

        $.ajax({

            type: "DELETE",
            url: services_url + '/' + service_id,
            success: function (data) {
                console.log(data);

                $("#service" + service_id).remove();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
            }
        });
    });

    //create new server / update existing server
    $("#add-listener").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        e.preventDefault(); 

        var formData = {
            listener_id: $('#listener_id').val(),
            listener_type: $('#listener_type').val(),
            address: $('#address').val(),
            port: $('#port').val(),
            protocol: $('#protocol').val(),
            auth: $('#auth').val(),
            auth_options: $('#auth_options').val(),
            ssl_key: $('#ssl_key').val(),
            ssl_cert: $('#ssl_cert').val(),
            ssl_ca_cert: $('#ssl_ca_cert').val(),
            ssl_version: $('#ssl_version').val(),
            ssl_depth: $('#ssl_depth').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#add-listener').val();

        var type = "POST"; //for creating new resource
        var service_id = $('#service_id').val();
        var my_url = services_url + '/' + service_id + '/createlistener';

        if (state == "update"){
            type = "PUT"; //for updating existing resource
            my_url += '/' + service_id;
        }

        console.log(formData);

        $.ajax({

            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {

                var listener = '<tr id="listener' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['type'] + '</td><td>' + data['data']['attributes']['parameters']['port'] + '</td><td>' + data['data']['attributes']['parameters']['protocol'] + '</td><td>' + data['data']['attributes']['parameters']['authenticator'] + '</td>';
                listener += '<td><button class="btn btn-danger btn-xs btn-delete delete-listener" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#listeners-list').append(listener);
                }else{ //if user updated an existing record

                    $("#listener" + listener_id).replaceWith(listener);
                }

                $('#addlistener').trigger("reset");

                $('#listener').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    //delete server and remove it from list
    $('.table').on('click', '.delete-listener', function(){
        var listener_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        var formData = {
            listener: listener_id
        }

        $.ajax({

            type: "DELETE",
            url: services_url + '/' + $('#service_id').val()+ '/deletelistener/',
            data: formData,
            dataType: 'json',
            success: function (data) {
                console.log(data);
                if(data[0]='error'){
                    $('#listener-button').append('</br>'+'<p class="text-danger">'+data[1]+'</p>');
                }else{
                    $("#listener" + listener_id).remove();
                }
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
            }
        });
    });

    //delete server and remove it from list
    $('.table').on('click', '.delete-monitor', function(){
        var monitor_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        $.ajax({

            type: "DELETE",
            url: monitors_url + '/' + monitor_id,
            success: function (data) {
                console.log(data);

                $("#monitor" + monitor_id).remove();
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

    $("#add-user").click(function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        e.preventDefault(); 

        var formData = {
            user_id: $('#user_id').val(),
            password: $('#password').val(),
            account: $('#account').val(),
        }

        //used to determine the http verb to use [add=POST], [update=PUT]
        var state = $('#add-user').val();

        var type = "POST"; //for creating new resource
        var user_id = $('#user_id').val();
        var my_url = users_url + '/';

        console.log(formData);

        $.ajax({

            type: type,
            url: my_url,
            data: formData,
            dataType: 'json',
            success: function (data) {

                var user = '<tr id="user' + data['data']['id'] + '"><td>' + data['data']['id'] + '</td><td>' + data['data']['type'] + '</td><td>' + data['data']['attributes']['account'] + '</td>';
                user += '<td><button class="btn btn-danger btn-xs btn-delete delete-user" value="' + data['data']['id'] + '">Delete</button></td></tr>';

                if (state == "add"){ //if user added a new record
                    $('#users-list').append(user);
                }else{ //if user updated an existing record

                    $("#user" + user_id).replaceWith(user);
                }

                $('#adduser').trigger("reset");

                $('#user').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.delete-user', function(){
        var user_id = $(this).val();
        var user_type = $(this).attr('name');
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
        });
        
        var formData = {
            user: user_id,
            type: user_type
        }

        $.ajax({

            type: "DELETE",
            url: users_url + '/' + user_id,
            data: formData,
            dataType: 'html',
            success: function (data) {
                console.log(data);
               
                $("#user" + user_id).remove();
                $('div.flash-message').html(data);
                //$('#button-user').append('<p class="text-success">'+data[1]+'</p>');
                
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
            }
        });
    });

    $('.table').on('click', '.select', function(){
        var setting_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
          });

        var formData = {
            id: setting_id
        }

        $.ajax({

            type: "PUT",
            url: settings_url + '/' + setting_id + "/select",
            data: formData,
            dataType: 'html',
            success: function (data) {
                $('div.flash-message').html(data);
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });

    $('.table').on('click', '.delete-maxscale', function(){
        var setting_id = $(this).val();

        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }     
        });
        
        var formData = {
            id: setting_id
        }

        $.ajax({

            type: "DELETE",
            url: settings_url + '/' + setting_id,
            data: formData,
            dataType: 'html',
            success: function (data) {
                console.log(data);
               
                $("#setting" + setting_id).remove();
                $('div.flash-message').html(data);
                //$('#button-user').append('<p class="text-success">'+data[1]+'</p>');
                
                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.responseText);
                
            }
        });
    });
});