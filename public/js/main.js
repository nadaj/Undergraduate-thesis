var base_url = 'http://localhost:8000/';
var cnt = 4;
$(document).ready(function() {

    $('.checkbox_click').on('change', function(){                  
        var currentValue = $(this).closest('tr').find('td').eq(2).html();
        var isChecked = $(this).is(':checked');
        var obj = $(this).closest('tr');

        $.ajax({
            url: base_url + 'admin/changeactive',
            method: 'post',             
            data: {id: currentValue, _token: $('input[name="_token"]').val()},
            success: function(data){
                if (isChecked)
                    obj.attr( "class", "active" );
                else
                    obj.attr( "class", "danger" );
            },
            error: function(){},
        });
    });  

    $('#postojeci').on('click', function(e){  
        var obj = $(this);
        var otherobj = document.getElementById("neodobreni");
        var t1 = document.getElementById("table1");
        var t2 = document.getElementById("table2");

        e.preventDefault();
        $.ajax({
            url: base_url + 'admin/getpostojeci',
            method: 'get',             
            data: {},
            success: function(data){
                otherobj.classList.remove('active');
                obj.attr("class", "btn active");
                t1.style.display = "table";
                t2.style.display = "none";
            },
            error: function(){},
        });
    });  

    $('#neodobreni').on('click', function(e){    
        var obj = $(this);
        var otherobj = document.getElementById("postojeci");
        var t1 = document.getElementById("table1");
        var t2 = document.getElementById("table2");

        e.preventDefault();

        $.ajax({
            url: base_url + 'admin/getneodobreni',
            method: 'get',             
            data: {},
            success: function(data){
                otherobj.classList.remove('active');
                obj.attr("class", "btn active");
                t1.style.display = "none";
                t2.style.display = "table";
            },
            error: function(){},
        });
    }); 

    $('#addUser').on('click', function(){                  
        var email = $(this).closest('tr').find('td').eq(2).html();
        var role = $(this).parent().prev().children().find(":selected").val();
        var obj = $(this);

        $.ajax({
            url: base_url + 'admin/adduser',
            method: 'post',             
            data: {email: email, role: role, _token: $('input[name="_token"]').val()},
            success: function(data){
                obj.attr("disabled", "true");
            },
            error: function(){},
        });
    });    

    $('#katedra').on('change', function() {
        var obj = $(this);

        if (obj.val() != 0)
        {
            $("#zvanje").removeAttr("disabled");
            var katedra = obj.val();

            $.ajax({
                url: base_url + 'initiator/getzvanja',
                method: 'get',             
                data: {katedra: katedra},
                success: function(data)
                {
                    $('#zvanje').empty();
                    $('#zvanje').append($("<option></option>")
                    .attr("value", 0)
                    .text("Odaberite zvanje:")); 

                    for (var i = 0; i < data.length; i++)
                        $('#zvanje').append($("<option></option>")
                            .attr("value", data[i].id)
                            .text(data[i].name)); 
                    
                },
                error: function(){},
            });
            $("#korisnik").attr( "disabled", "disabled" );
            $("#korisnik").val("0");
        }
        else
        {
            $("#zvanje").attr( "disabled", "disabled" );
            $("#zvanje").val("0");
            $("#korisnik").attr( "disabled", "disabled" );
            $("#korisnik").val("0");
        }
    });  

    $('#zvanje').on('change', function() {
        var obj = $(this);

        if (obj.val() != 0)
        {
            $("#korisnik").removeAttr("disabled");
            var zvanje = obj.val();
            var katedra = $('#katedra').val();
            $.ajax({
                url: base_url + 'initiator/getkorisnici',
                method: 'get',             
                data: {zvanje: zvanje, katedra: katedra},
                success: function(data)
                {
                    $('#korisnik').empty();
                    $('#korisnik').append($("<option></option>")
                    .attr("value", 0)
                    .text("Odaberite korisnika:")); 

                    for (var i = 0; i < data.length; i++)
                        $('#korisnik').append($("<option></option>")
                            .attr("value", data[i].id)
                            .text(data[i].email)); 
                    
                },
                error: function(){},
            });
        }
        else
        {
            $("#korisnik").attr("disabled", "disabled");
            $("#korisnik").val("0");
        }
    });  

    $('#add_user').on('click', function(e){  
        var obj = $('#korisnik');
        var glasaci = document.getElementById("tablevoters");
        var userExists = false;

        // provera da li takav odgovor vec postoji i da li je unet prazan odgovor
        if (obj.val() != 0)
        {
            for (var i = 0, row; row = glasaci.rows[i]; i++) {
                if (row.cells[0].innerHTML == $('#korisnik :selected').text())
                    userExists = true;
            }

            if (userExists == false)
            {
                if ($('#tablevoters tr').length == 1)
                    glasaci.style.display = "table";
                $('#tablevoters > tbody:last-child').append('<tr><td style="line-height:1.6">' + $('#korisnik :selected').text() + '</td><td style="padding-bottom:0px;padding-top:0px"><center><button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_glasaca" name="ukloni_glasaca"><i class="material-icons">delete</i></button></center></td></tr>');
                var opt = document.createElement('option');
                opt.value = $('#korisnik :selected').text();
                opt.innerHTML = $('#korisnik :selected').text();
                opt.selected = true;
                document.getElementById("glasaci").appendChild(opt);
            }
        }

        $("#katedra").val("0");
        $("#zvanje").attr( "disabled", "disabled" );
        $("#zvanje").val("0");
        $("#korisnik").attr( "disabled", "disabled" );
        $("#korisnik").val("0");
        e.preventDefault();
    }); 

    $('#nastavi_sa_tiketom').on('click', function(e){  
        var obj = $(this);

        e.preventDefault();
        $.ajax({
            url: base_url + 'voter/accessvote',
            method: 'post',             
            data: {
                voting_id: $('input[name="voting_id"]').val(),
                ticket: $('input[name="ticket"]').val(), 
                _token: $('input[name="_token"]').val()
            },
            success: function(data){
                window.location.replace(base_url + 'voter/vote/' + data); 
            },
            error: function(data){
                var errors = data.responseJSON;

                // status 422 je za validation error
                var errorsHtml = '<div class="alert alert-dismissible alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">×</button>';

                $.each( errors, function( key, value ) {
                    errorsHtml += '<p>' + value + '</p>';
                });
                errorsHtml += '</div>';
                    
                $('.center').html(errorsHtml); //appending to a <div class="center"></div>
                
            },
        });
    });  
    
    $('#nastavi_sa_tiketom_init').on('click', function(e){  
        var obj = $(this);

        e.preventDefault();
        $.ajax({
            url: base_url + 'initiator/accessvote',
            method: 'post',             
            data: {
                voting_id: $('input[name="voting_id"]').val(),
                ticket: $('input[name="ticket"]').val(), 
                _token: $('input[name="_token"]').val()
            },
            success: function(data){
                window.location.replace(base_url + 'initiator/vote/' + data); 
            },
            error: function(data){
                var errors = data.responseJSON;

                // status 422 je za validation error
                var errorsHtml = '<div class="alert alert-dismissible alert-danger">' +
                        '<button type="button" class="close" data-dismiss="alert">×</button>';

                $.each( errors, function( key, value ) {
                    errorsHtml += '<p>' + value + '</p>';
                });
                errorsHtml += '</div>';
                    
                $('.center').html(errorsHtml); //appending to a <div class="center"></div>
                
            },
        });
    }); 

    $('#tekuca').on('click', function(e){  
        var obj = $(this);
        var otherobj = document.getElementById("prosla");
        var t1 = document.getElementById("tekuca_glasanja");
        var t2 = document.getElementById("prosla_glasanja");

        e.preventDefault();
        otherobj.classList.remove('active');
        obj.attr("class", "btn active");
        t1.style.display = "block";
        t2.style.display = "none";
    });  

    $('#prosla').on('click', function(e){  
        var obj = $(this);
        var otherobj = document.getElementById("tekuca");
        var t1 = document.getElementById("tekuca_glasanja");
        var t2 = document.getElementById("prosla_glasanja");

        e.preventDefault();
        otherobj.classList.remove('active');
        obj.attr("class", "btn active");
        t2.style.display = "block";
        t1.style.display = "none";
    });  

    $('#nastavi_sa_brisanjem').on('click', function(e){  
        var obj = $(this);

        e.preventDefault();
        $.ajax({
            url: base_url + 'initiator/deletevoting',
            method: 'post',             
            data: {
                voting_id: $('input[name="voting_id"]').val(),
                _token: $('input[name="_token"]').val()
            },
            success: function(data){
                window.location.replace(base_url + 'initiator/votings'); 
            },
            error: function(data){
                
            },
        });
    }); 

    $('#dodaj_odgovor').on('click', function(e){  
        $('#tableanswers > tbody:last-child').append('<tr><td style="padding-bottom:2px;padding-top:0px"><div class="form-group" style="margin-top:0px;padding-bottom:0px;"><input type="text" style="margin-bottom:0px;padding-bottom:0px;padding-top:0px" class="form-control" name="odg[]"></div></td><td style="padding-bottom:0px;padding-top:0px"><center><button style="margin-top:2px;margin-bottom:0px" class="btn btn-raised btn-danger btn-xs" id="ukloni_odgovor" name="ukloni_odgovor"><i class="material-icons">delete</i></button></center></td></tr>');
        e.preventDefault();
    }); 

    $("[id^='ukloni_odgovor']").on('click', function(e){ 
        $(this).closest("tr").remove();
        e.preventDefault();
    }); 

    $('#tableanswers').on('click', '.btn-danger', function(e){  
        $(this).closest("tr").remove();
        e.preventDefault();
    }); 

    $('#tablevoters').on('click', '.btn-danger', function(e){  
        $(this).closest("tr").remove();
        $("#glasaci option[value='" + $(this).closest("tr").find('td:first').text() + "']").remove();
        if ($('#tablevoters tr').length == 1)
            document.getElementById("tablevoters").style.display = "none";
        e.preventDefault();
    }); 

    $("input[name='criteriumRadios']").on('change', function(e){ 
        if ($("input[name='criteriumRadios']:checked").val() == 2) 
        {
            $('#opcija').html('<option value="Odaberite odgovor:">Odaberite odgovor:</option>');
            
            var y = document.getElementsByName('odg[]');
            for (var i = 0; i < y.length; i++) {
                var opt = document.createElement('option');
                opt.value = y[i].value;
                opt.innerHTML = y[i].value;
                document.getElementById("opcija").appendChild(opt);
            }

            document.getElementById("opcija").style.display = "block";
        }
        else
        {
            document.getElementById("opcija").style.display = "none";
        }
        e.preventDefault();
    });

    // $("#opcija").on('change', function() {
    //     var obj = $(this);
    //     var opcija = obj.val();

    //     document.getElementById("opcija_skrivena").value = opcija;
    // });
});
 