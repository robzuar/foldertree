function tableload(nombretabla, sUrlDatatable) {

    $('#'+nombretabla).dataTable({
        "dom": '<"pull-right"f><"pull-left"l>tip',
        "oLanguage": {
            "sUrl": sUrlDatatable,
            "iDisplayLength": 2
        },
        responsive: true,
        autoFill: true,
        colReorder: true,
        keys: true,
        rowReorder: true,
        select: true
    });
}