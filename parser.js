$(function(){
    var base_url = "/public_html/parser/controller.php"
    $("#parse-btn").click(function(){
        event.preventDefault();
        $(this).addClass('active-spinner');
        $(this).button('loading');
        $("#parse-result").html("Пожайлуйста, подождите");
        $.getJSON(base_url, {action: "parse"}, function(data){
            console.log(data);
            $("#parse-btn").removeClass('active-spinner');
            $("#parse-btn").button('reset');
            $("#parse-result").html('Обработано: '+data['total_count']+' товаров '+ 'Обновлено: '+data['changed_count']+' Добавлено: '+data['inserted_count']);
        });
    }); 
    
    $("#create-exception-btn").click(function(){
        event.preventDefault();
        $.post(base_url, { action: "create_exception", articul: $("#articul-input").val() }, function(data){
            console.log("exception created");
            $("#exceptions-tbl").dataTable().fnAddData([$("#articul-input").val(),""]);
        });
    });
       
    $("#parser-tbl").dataTable({
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        oLanguage: {
              sProcessing: "Подождите...",
              sLengthMenu: "Показать _MENU_ записей",
              sZeroRecords: "Записи отсутствуют.",
              sInfo: "Записи с _START_ до _END_ из _TOTAL_ записей",
              sInfoEmpty: "Записи с 0 до 0 из 0 записей",
              sInfoFiltered: "(отфильтровано из _MAX_ записей)",
              sInfoPostFix: "",
              sSearch: "Поиск:",
              sUrl: "",
              oPaginate: {
                sFirst: "Первая",
                sPrevious: "Предыдущая",
                sNext: "Следующая",
                sLast: "Последняя"
              }
          }
    });
    
    $("#exceptions-tbl").dataTable({
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "bPaginate": false,
        oLanguage: {
              sProcessing: "Подождите...",
              sLengthMenu: "Показать _MENU_ записей",
              sZeroRecords: "Записи отсутствуют.",
              sInfo: "Записи с _START_ до _END_ из _TOTAL_ записей",
              sInfoEmpty: "Записи с 0 до 0 из 0 записей",
              sInfoFiltered: "(отфильтровано из _MAX_ записей)",
              sInfoPostFix: "",
              sSearch: "Поиск:",
              sUrl: "",
              oPaginate: {
                sFirst: "Первая",
                sPrevious: "Предыдущая",
                sNext: "Следующая",
                sLast: "Последняя"
              }
          }
    });

    $.getJSON(base_url, {action: "parsing_exceptions"}, function(data){
        for(var px in data) {
            $("#exceptions-tbl").dataTable().fnAddData([data[px][0],""]);
        }
    });
});