app.service("claveService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.get = function (id) {
        var req = $http.get(uri+'/api/clave/' + id);
        return req;
    };
    this.getAll = function () {
        var req = $http.get(uri+'/api/clave');
        return req;
    };
    this.post = function (product) {
        
        var req = $http.post(uri+'/api/clave', product);
        return req;
        
    };
    
    this.put = function (id,product) {
        
        var req = $http.put(uri+'/api/clave/' + id, product);
        return req;
        
    };
});