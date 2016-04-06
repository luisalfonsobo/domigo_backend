app.service("facturaService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    
    this.getByFecha = function (fechaIni, fechaFin) {
        
        var req = $http.get(uri+'/api/fecha/' + fechaIni + "/" + fechaFin + "/factura");
        return req;
    };
    
    this.getByFechaProductos = function (fechaIni, fechaFin) {
        
        var req = $http.get(uri+'/api/fecha/' + fechaIni + "/" + fechaFin + "/factura/producto");
        return req;
    };
    
    this.getByIdPersona = function (idPersona) {
        
        var req = $http.get(uri+'/api/persona/' + idPersona + "/factura");
        return req;
    };
    
    this.getDetalles = function (idFactura) {
        
        var req = $http.get(uri+'/api/factura/' + idFactura + "/detalle");
        return req;
    };
    
    this.getDetallesWhitDevoluciones = function (idFactura) {
        
        var req = $http.get(uri+'/api/factura/' + idFactura + "/detalle/devolucion");
        return req;
    };
    
    this.getByIdVendedor = function (idPersona) {
        
        var req = $http.get(uri+'/api/vendedor/' + idPersona + "/factura");
        return req;
    };
    
    this.get = function (id) {
        var req = $http.get(uri+'/api/factura/' + id);
        return req;
    };
    
    this.getAll = function () {
        var req = $http.get(uri+'/api/factura');
        return req;
    };
    
    this.post = function (factura) {
        
        var req = $http.post(uri+'/api/factura', factura);
        return req;
        
    };
    
    this.put = function (id,factura) {
        
        var req = $http.put(uri+'/api/factura/' + id, factura);
        return req;
        
    };
});