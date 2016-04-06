app.service("compraService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    
    this.getByFecha = function (fechaIni, fechaFin) {
        
        var req = $http.get(uri+'/api/fecha/' + fechaIni + "/" + fechaFin + "/compra");
        return req;
    };
    
    this.getDetalles = function (idFactura) {
        
        var req = $http.get(uri+'/api/compra/' + idFactura + "/detalle");
        return req;
    };
    
    this.getDetallesWhitDevoluciones = function (idFactura) {
        
        var req = $http.get(uri+'/api/compra/' + idFactura + "/detalle/devolucion");
        return req;
    };
    
    
    this.get = function (id) {
        var req = $http.get(uri+'/api/compra/' + id);
        return req;
    };
    
    this.getAll = function () {
        var req = $http.get(uri+'/api/compra');
        return req;
    };
    
    this.post = function (factura) {
        
        var req = $http.post(uri+'/api/compra', factura);
        return req;
        
    };
    
    this.put = function (id,factura) {
        
        var req = $http.put(uri+'/api/compra/' + id, factura);
        return req;
        
    };
    
    this.putCostoCompra = function (detalle) {
        
        var req = $http.put(uri+'/api/detalle/' +detalle.id, detalle);
        return req;
        
    };
});