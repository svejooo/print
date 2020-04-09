$("#calc_submit_button").on('click', function(){


    // /alert( location.search );

    var tpl = $.cookie('asystemTpl');
    //alert(tpl);

    // Какого тот формируется name без calc. Менияем перд отправкой
    $('#_tir').attr("name","calc[tir]");

    var data = $('#calc_form').serialize();
    $.ajax({

        type: "POST",
        url: "api" +  location.search + "&template="+tpl,
        data:data,
        error:function(){
            $("#contentResult").html("<strong>Ошибка AJAX обращения к CatalogController - actionApi </strong>");
            //document.getElementById('truePhone').value = "";

        },
        beforeSend: function() {
            $("#contentResult").html('Загрузка...');
        },
        success: function(data){
            $("#contentResult").html(data);
            console.log(data);
        }
    });
    return false;
});
