app.service("devolucionFacturaService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.get = function (id) {
        var req = $http.get(uri+'/api/devolucionFactura/' + id);
        return req;
    };
    
    this.post = function (devolucion) {
        
        var req = $http.post(uri+'/api/devolucionFactura', devolucion);
        return req;
        
    };
    
    this.put = function (id,devolucion) {
        
        var req = $http.put(uri+'/api/devolucionFactura/' + id, devolucion);
        return req;
        
    };
    this.delete = function (id) {
        
        var req = $http.delete(uri+'/api/devolucionFactura/' + id);
        return req;
        
    };
});