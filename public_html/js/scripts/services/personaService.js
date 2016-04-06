app.service("personasService", function ($http) {
    //var uri="http://localhost/tucarro.com/public";
    
    
    this.getByLogin = function (user) {
        
        var req = $http.post(uri+'/api/user/login',user);
        return req;
        
    };
    
    this.getMensajerosByIdEmpresa= function(idEmpresa){
      
        var req = $http.get(uri+'/api/empresa/'+idEmpresa+'/mensajero');
        return req;
        
    };
    
    this.getMensajeroByCedula = function(cedula){
        var req = $http.get(uri+'/api/cedula/' + cedula +'/mensajero');
        return req;
    };
    
    this.putEmpresa = function(id,object){
        
        var req = $http.put(uri+'/api/mensajero/updateempresa/' + id ,object);
        return req;
        
    };
    this.putVehiculo = function(id,object){
        
        var req = $http.put(uri+'/api/mensajero/updatevehiculo/' + id ,object);
        return req;
        
    };
    
    this.get = function (id) {
        var req = $http.get(uri+'/api/persona/' + id);
        return req;
    };
    
    this.getAll = function () {
        var req = $http.get(uri+'/api/persona');
        return req;
    };
    
    this.post = function (persona) {
        
        var req = $http.post(uri+'/api/persona', persona);
        return req;
        
    };
    
    this.put = function (id,persona) {
        
        var req = $http.put(uri+'/api/persona/' + id, persona);
        return req;
        
    };
});