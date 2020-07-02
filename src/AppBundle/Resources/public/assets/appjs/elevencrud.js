var paths        = {};
var entityName       = "";
var hasDatatable     = false;
var sUrlDatatable    = false;
var isFirstLoadTable = true;

function initializeCRUD(options) {
    paths = {
        _reload: options._reload,
        _show:   options._show,
        _new:    options._new,
        _edit:   options._edit,
        _edit_analyst:   options._edit_analyst,
        _edit_incharge:   options._edit_incharge,
        _delete: options._delete,
        _enabled: options._enabled
    };
    entityName    = options.entityName;
    hasDatatable  = options.hasDatatable;
    sUrlDatatable = options.sUrlDatatable;
    loadTable();
}

function loading(status){
    if (status) {
        $("#loading").fadeIn("slow", function() {
            $(this).removeClass("hidden");
        });
    }else{
        $("#loading").fadeOut("slow", function() {
            $(this).addClass("hidden");
        });
    }
}

function loadTable(){
    checkLoadingDevel(true);
    $.ajax({
        type: "GET",
        async: true,
        url: paths._reload,
        success: function(results) {
            $("#results").html("").html(results);
            checkLoadingDevel(false);
            documentOnReady();
        },
        error: function(e){
            console.log(e);
        }
    });
}


function viewElement(id) {
    var path = paths._show;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            bootbox.dialog({
                message: results,
                buttons: {
                    warning: {
                        label: "<i class='fa fa-pencil-square-o'></i> Editar",
                        className: "btn-default",
                        callback: function() {
                            editElement(id);
                        }
                    },
                    success: {
                        label: "<i class='fa fa-check'></i> Aceptar",
                        className: "btn-primary",
                        callback: function() {
                        }
                    }
                }
            });
            // visual adaptations
            $(".bootbox-close-button").hide();
            $(".modal-body").css("padding",0);
            $(".panel").css("margin-bottom",0);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function viewElementWithDelete(id) {
    var path = paths._show;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            bootbox.dialog({
                message: results,
                buttons: {
                    danger: {
                        label: "<i class='fa fa-trash'></i> Eliminar",
                        className: "btn-danger pull-left",
                        callback: function() {
                            deleteElement(id);
                        }
                    },
                    warning: {
                        label: "<i class='fa fa-pencil-square-o'></i> Editar",
                        className: "btn-default",
                        callback: function() {
                            editElement(id);
                        }
                    },
                    success: {
                        label: "<i class='fa fa-check'></i> Aceptar",
                        className: "btn-primary",
                        callback: function() {
                        }
                    }
                }
            });
            // visual adaptations
            $(".bootbox-close-button").hide();
            $(".modal-body").css("padding",0);
            $(".panel").css("margin-bottom",0);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function viewElementWithDisable(id, enabled) {
    var path = paths._show;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            var buttonsBootbox = {
                warning: {
                    label: "Editar",
                    className: "btn-default",
                    callback: function() {
                        editElement(id);
                    }
                },
                success: {
                    label: "Aceptar",
                    className: "btn-primary",
                    callback: function() {
                    }
                }
            };

            var btnState = {};
            if (enabled === 1) {
                btnState = {
                    danger: {
                        label: "Deshabilitar",
                        className: "btn-default pull-left",
                        callback: function() {
                            changeStateElement(id);
                        }
                    }
                };
            } else {
                btnState = {
                    success2: {
                        label: "Habilitar",
                        className: "btn-default pull-left",
                        callback: function() {
                            changeStateElement(id);
                        }
                    }
                };
            }

            $.extend(buttonsBootbox, btnState);

            bootbox.dialog({
                message: results,
                buttons: buttonsBootbox
            });
            // visual adaptations
            $(".bootbox-close-button").hide();
            $(".modal-body").css("padding",0);
            $(".panel").css("margin-bottom",0);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function  generateBootboxForm(options) {
    var title = "";
    var message = "";
    var form = "";
    var messageSuccess = "";
    
    if ( !(options.hasOwnProperty("title")) ) {
        title = "Alert! Debes agregar el title";
    } else {
        title = options.title;
    }

    if ( !(options.hasOwnProperty("message")) ) {
        message = "Alert! Debes agregar el message";
    } else {
        message = options.message;
    }

    if ( !(options.hasOwnProperty("form")) ) {
        form = "Alert! Debes agregar el form";
    } else {
        form = options.form;
    }

    if ( !(options.hasOwnProperty("messageSuccess")) ) {
        messageSuccess = "Alert! Debes agregar el messageSuccess";
    } else {
        messageSuccess = options.messageSuccess;
    }

    bootbox.dialog({
        message: message,
        buttons: {
            success: {
                label: "<i class='fa fa-reply'></i> Volver",
                className: "btn-default",
                callback: function() {
                }
            },
            main: {
                label: "<i class='fa fa-floppy-o'></i> Guardar",
                className: "btn-primary",
                callback: function() {
                    var formData = $(form).serializeArray();
                    var path     = $(form).prop("action");
                    checkLoadingDevel(true);
                    $.ajax({
                        type: "POST",
                        async: true,
                        data: formData,
                        url: path,
                        success: function(results) {
                            checkLoadingDevel(false);
                            if (results === "success") {
                                bootbox.alert(messageSuccess, function() {
                                    //loadTable();
                                    document.location.reload(true);
                                });
                            }else{
                                var options = {
                                    title:           title,
                                    message:        results,
                                    form:           form,
                                    messageSuccess: messageSuccess,
                                };
                                generateBootboxForm(options);
                            }
                        },
                        error: function(e){
                            console.log(e);
                        }
                    });
                }
            }
        }
    });
    $(".bootbox-close-button").hide();
    $(".modal-body").css("padding",0);
    $(".panel").css("margin-bottom",0);
}

function addElement() {
    var path = paths._new;
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            var options = {
                title:          "Añadir " + entityName,
                message:        results,
                form:           "#the-form",
                messageSuccess: "Agregado Correctamente",
            };
            generateBootboxForm(options);
        },
        error: function(e){
            console.log(e);
        }
    });
};

function editElement(id) {
    var path = paths._edit;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            var options = {
                title:          "Editar " + entityName,
                message:        results,
                form:           "#the-form",
                messageSuccess: "Editado Correctamente",
            };
            generateBootboxForm(options);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function editElementAnalyst(id) {
    var path = paths._edit_analyst;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            var options = {
                title:          "Editar " + entityName,
                message:        results,
                form:           "#the-form",
                messageSuccess: "Editado Correctamente",
            };
            generateBootboxForm(options);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function editElementIncharge(id) {
    var path = paths._edit_incharge;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "GET",
        async: true,
        url: path,
        success: function(results) {
            var options = {
                title:          "Editar " + entityName,
                message:        results,
                form:           "#the-form",
                messageSuccess: "Editado Correctamente",
            };
            generateBootboxForm(options);
        },
        error: function(e){
            console.log(e);
        }
    });
}

function deleteElement(id) {
    var div = "<div class='alert alert-danger alert-dark'>"+
        "<strong>Alerta!</strong> Desea realmente eliminar el/la " + entityName + ". <br>"+
        "Esto eliminará todos los elementos asociados."+
        "</div>";
    var divError = "<div class='alert alert-danger alert-dark'>"+
        "No es posible eliminar este elemento </div>";
    bootbox.dialog({
        message: div,
        buttons: {
            info: {
                label: "<i class='fa fa-reply'></i> Volver",
                className: "btn-dafault",
                callback: function() {
                }
            },
            danger: {
                label: "<i class='fa fa-trash'></i> Eliminar",
                className: "btn-danger",
                callback: function() {
                    var path = paths._delete;
                    path = path.replace("idElemento", id);
                    $.ajax({
                        type: "POST",
                        async: true,
                        url: path,
                        success: function(results) {
                            bootbox.alert(entityName + " Eliminada/o correctamente", function() {
                                loadTable();
                            });
                        },
                        error: function(e){
                            bootbox.alert(divError, function() {
                                loadTable();
                            });
                        }
                    });
                }
            }
        }
    });
}

function changeStateElement(id) {
    var path = paths._enabled;
    path = path.replace("idElemento", id);
    $.ajax({
        type: "POST",
        async: true,
        url: path,
        success: function(results) {
            bootbox.alert(entityName + " Editada/o correctamente", function() {
                loadTable();
            });
        },
    });
}
function jsShowWindowLoad(mensaje) {
    //eliminamos si existe un div ya bloqueando
    console.log('entro winload');
    jsRemoveWindowLoad();

    //si no enviamos mensaje se pondra este por defecto
    if (mensaje === undefined) mensaje = "Procesando la información<br>Espere por favor";

    //centrar imagen gif
    height = 20;//El div del titulo, para que se vea mas arriba (H)
    var ancho = 0;
    var alto = 0;

    //obtenemos el ancho y alto de la ventana de nuestro navegador, compatible con todos los navegadores
    if (window.innerWidth == undefined) ancho = window.screen.width;
    else ancho = window.innerWidth;
    if (window.innerHeight == undefined) alto = window.screen.height;
    else alto = window.innerHeight;

    //operación necesaria para centrar el div que muestra el mensaje
    var heightdivsito = alto/2 - parseInt(height)/2;//Se utiliza en el margen superior, para centrar

    //imagen que aparece mientras nuestro div es mostrado y da apariencia de cargando
    imgCentro = "<div style='text-align:center;height:" + alto + "px;'><div  style='color:#000;margin-top:" + heightdivsito + "px; font-size:20px;font-weight:bold'>" + mensaje + "</div></div>";

    //creamos el div que bloquea grande------------------------------------------
    div = document.createElement("div");
    div.id = "WindowLoad";
    div.style.width = ancho + "px";
    div.style.height = alto + "px";
    $("body").append(div);

    //creamos un input text para que el foco se plasme en este y el usuario no pueda escribir en nada de atras
    input = document.createElement("input");
    input.id = "focusInput";
    input.type = "text";

    //asignamos el div que bloquea
    $("#WindowLoad").append(input);

    //asignamos el foco y ocultamos el input text
    $("#focusInput").focus();
    $("#focusInput").hide();

    //centramos el div del texto
    $("#WindowLoad").html(imgCentro);

}


function jsRemoveWindowLoad() {
    // eliminamos el div que bloquea pantalla
    $("#WindowLoad").remove();

}
function documentOnReady() {
    if (isFirstLoadTable === true) {


        $(".btn-reload-table").on("click", function () {
            loadTable();
        });
    };
    $(".btn-add").on("click", function () {
        //console.log('entro en agregar');return false;
        addElement();
    });

    $(".btn-details").on("click", function () {
        if ($(this).hasClass('disabled-entity')) {
            viewElementWithDisable($(this).data("id"), $(this).data('enabled'));
        } else if($(this).hasClass('delete-entity')) {
            viewElementWithDelete($(this).data("id"));
        }else{
            viewElement($(this).data("id"));
        }
    });

    if (hasDatatable === true) {
        $('#table-entities').dataTable( {
                "dom": '<"pull-right"f><"pull-left"l>tip',
                "oLanguage": {
                    "sUrl": sUrlDatatable
                },
                responsive: true,
                autoFill: true,
                colReorder: true,
                keys: true,
                rowReorder: true,
                select: true
            },{
                "order": [[ 0, 'desc' ]]}
        );
    }

    isFirstLoadTable = false;
}

function loadtable(val){
    $('#'+val).dataTable( {
            "dom": '<"pull-right"f><"pull-left"l>tip',
            "oLanguage": {
                "sUrl": sUrlDatatable
            },
            responsive: true,
            autoFill: true,
            colReorder: true,
            keys: true,
            rowReorder: true,
            select: true
        },{
            "order": [[ 0, 'desc' ]]}
    );
}

function checkLoadingEleven(load) {
    $("html, body").animate({ scrollTop: 0 }, "slow");
    if (load) {
        $("#page-loader").removeClass("hide");
        // $('#page-loader').addClass('fade in');
        $("#content").append("<div id='page-content-loader'><span class='spinner'></span></div>");
    } else {
        setTimeout(function() {
            $("#page-loader").addClass("hide");
            $("#page-content-loader").remove();
        },300);
    }
}

function checkLoadingDevel(load) {
    if (load === true) {
        jsShowWindowLoad();
    } else {
        jsRemoveWindowLoad();
    }
}

function messagesDevelDiv(alert, message){
    console.log("entro en mensaje");
    $("#mensajeresult").append("<div id='alertamensaje' class='alert " +
        alert +
        " '>" +
        "<span class='close' data-dismiss='alert'>×</span>" +
        "<i class='fa fa-check fa-2x pull-left m-r-10'>" +
        "</i><p class='m-b-0'>" +
        message +
        "</p>" +
        "</div>");

    setTimeout(function() {
        $("#alertamensaje").remove();
    },3000);
}

function messagesDevelSwal(alert, message, title) {
    console.log("entro swal");
    swal({
        title: title,
        text: message
    });
}

function messageDevelGritter(alert, message, title){
    $.gritter.add({
        title: title,
        text: message,
        class_name: "gritter-"+alert+" gritter-center",
        time : 6000,
    });

}

