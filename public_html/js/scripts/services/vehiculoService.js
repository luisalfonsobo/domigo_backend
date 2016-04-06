app.service("vehiculoService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.get = function (id) {
        var req = $http.get(uri+'/api/vehiculo/' + id);
        return req;
    };
    
    this.getAllByEmpresa = function (id) {
        var req = $http.get(uri+'/api/empresa/' + id + '/vehiculo');
        return req;
    };
    
    this.getAll = function () {
        var req = $http.get(uri+'/api/vehiculo');
        return req;
    };
    this.post = function (product) {
        
        var req = $http.post(uri+'/api/vehiculo', product);
        return req;
        
    };
    
    this.put = function (id,product) {
        
        var req = $http.put(uri+'/api/vehiculo/' + id, product);
        return req;
        
    };
});