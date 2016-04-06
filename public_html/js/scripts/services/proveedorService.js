app.service("proveedorService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    this.getCentral = function (id) {
        var req = $http.get(uri+'/api/empresa/' + id);
        return req;
    };
    this.getAllCentrales = function () {
        var req = $http.get(uri+'/api/empresa');
        return req;
    };
    this.postCentral = function (product) {
        
        var req = $http.post(uri+'/api/empresa', product);
        return req;
        
    };
    
    this.putCentral = function (id,product) {
        
        var req = $http.put(uri+'/api/empresa/' + id, product);
        return req;
        
    };
    this.putEstadoCentral = function (id,product) {
        
        var req = $http.put(uri+'/api/empresa/' + id +'/estado', product);
        return req;
        
    };
});