app.service("productoService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.get = function (id) {
        var req = $http.get(uri+'/api/tiposervicio/' + id);
        return req;
    };
    
    this.getAll = function () {
        var req = $http.get(uri+'/api/tiposervicio');
        return req;
    };
   
    this.post = function (product) {
        
        var req = $http.post(uri+'/api/tiposervicio', product);
        return req;
        
    }; 
    
    this.put = function (id,product) {
        
        var req = $http.put(uri+'/api/tiposervicio/' + id, product);
        return req;
        
    };
});