$(function(){
    var base_url = "/public_html/parser/controller.php"
    $("#parse-btn").click(function(){
        event.preventDefault();
        $.getJSON(base_url, {action: "parse"}, function(data){
            console.log(data);
            $("#parse-result").html('Обработано: '+data['total_count']+' товаров '+ 'Обновлено: '+data['changed_count']+' Добавлено: '+data['inserted_count']);
        });
    }); 
       
    $("#parser-tbl").dataTable({
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>"
    });
    
    $("#exceptions-tbl").dataTable({
        "sDom": "<'row'<'col-md-6'l><'col-md-6'f>r>t<'row'<'col-md-6'i><'col-md-6'p>>",
        "bPaginate": false
    });

    $.getJSON(base_url, {action: "parsing_exceptions"}, function(data){
        for(var px in data) {
            $("#exceptions-tbl").dataTable().fnAddData([data[px][0],""]);
        }
    });
});