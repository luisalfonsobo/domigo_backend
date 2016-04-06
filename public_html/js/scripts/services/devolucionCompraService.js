app.service("devolucionCompraService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.get = function (id) {
        var req = $http.get(uri+'/api/devolucionCompra/' + id);
        return req;
    };
    
    this.post = function (devolucion) {
        
        var req = $http.post(uri+'/api/devolucionCompra', devolucion);
        return req;
        
    };
    
    this.put = function (id,devolucion) {
        
        var req = $http.put(uri+'/api/devolucionCompra/' + id, devolucion);
        return req;
        
    };
    this.delete = function (id) {
        
        var req = $http.delete(uri+'/api/devolucionCompra/' + id);
        return req;
        
    };
});