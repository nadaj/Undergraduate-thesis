var base_url = 'http://localhost:8000/';

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

    $('#add_answer').on('click', function(e){    
        var obj = $('#unet_odgovor');
        var odgovori = document.getElementById("odgovori");
        var answerExists = false;

        // provera da li takav odgovor vec postoji i da li je unet prazan odgovor
        if (obj.val() != "" && $.trim(obj.val()).length != 0)
        {
            for (var i = 1; i < odgovori.length; i++) 
                if (odgovori.options[i].text.toUpperCase() == obj.val().toUpperCase())
                    answerExists = true;

            if (answerExists == false)
            {
                var opt = document.createElement('option');
                opt.value = obj.val();
                opt.innerHTML = obj.val();
                opt.selected = true;
                odgovori.appendChild(opt);
            }
        }

        obj.val("");
        e.preventDefault();
    }); 

    $('#add_user').on('click', function(e){    
        var obj = $('#korisnik');
        var glasaci = document.getElementById("glasaci");
        var userExists = false;

        // provera da li takav odgovor vec postoji i da li je unet prazan odgovor
        if (obj.val() != 0)
        {
            for (var i = 0; i < glasaci.length; i++) {
                if (glasaci.options[i].text == $('#korisnik :selected').text())
                    userExists = true;
            }

            if (userExists == false)
            {
                var opt = document.createElement('option');
                opt.value = $('#korisnik :selected').text();
                opt.innerHTML = $('#korisnik :selected').text();
                opt.selected = true;
                glasaci.appendChild(opt);
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

    // format date
    // $('p:visible[id*=vreme_pocetka]').each(function() {

    //     var vreme = document.getElementById("vreme_pocetka").innerHTML;
    //     console.log(vreme); 
    //     var startIndex = vreme.indexOf('</b>') + 5;
    //     var datum = vreme.substring(startIndex);
    //     var day = datum.substring(8, 10);
    //     var year = datum.substring(0, 4);
    //     var mon = datum.substring(5, 7);
    //     var nov_datum = day + "-" + mon + "-" + year + datum.substring(10);

    //     document.getElementById("vreme_pocetka").innerHTML = 
    //         vreme.substring(0, startIndex) + nov_datum;
    // });

    // $("#vreme_zavrsetka").each(function() {
    //     var vreme = document.getElementById("vreme_zavrsetka").innerHTML;
    //     var startIndex = vreme.indexOf('</b>') + 5;
    //     var datum = vreme.substring(startIndex);
    //     var day = datum.substring(8, 10);
    //     var year = datum.substring(0, 4);
    //     var mon = datum.substring(5, 7);
    //     var nov_datum = day + "-" + mon + "-" + year + datum.substring(10);

    //     document.getElementById("vreme_zavrsetka").innerHTML = 
    //         vreme.substring(0, startIndex) + nov_datum;
    // });

    // if (document.getElementById("progress_bar") != null)
    // {
    //     console.log('fdfs');
    // }
});
 