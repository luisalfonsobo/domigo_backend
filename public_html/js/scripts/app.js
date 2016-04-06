
var uri = "webapi/public";
//var uri = "http://webapi.tucarropuntocomvalledupar.com/public";
var app;
(function(){
    app = angular.module("buscame", ['ngRoute','angularUtils.directives.dirPagination','ui.keypress']);
    
    app.config(['$routeProvider', '$locationProvider', function AppConfig($routeProvider, $locationProvider){
            
            //$locationProvider.html5Mode(true);
            
            $routeProvider
                    .when("/centrales",{
                        templateUrl: 'pages/centrales.html'
                    })
                    .when("/vehiculos",{
                        templateUrl: 'pages/vehiculos.html'
                    })
                    .when("/mensajeros",{
                        templateUrl: 'pages/mensajeros.html'
                    })
                    .when("/tiposervicio",{
                        templateUrl: 'pages/tiposervicio.html'
                    })
                    .when("/blanco",{
                        templateUrl: 'pages/blanco.html'
                    })
                    .otherwise({
                        redirectTo:"/blanco"
                    });
                    
            
    }]);

    app.directive('ngEnter', function () {
        return function (scope, elements, attrs) {
            elements.bind('keydown keypress', function (event) {
                if (event.which === 13) {
                    scope.$apply(function () {
                        scope.$eval(attrs.ngEnter);
                    });
                    event.preventDefault();
                }
            });
        };
    });
    
    app.filter('ifEmpty', function() {
        return function(input, defaultValue) {
            if (angular.isUndefined(input) || input === null || input === '') {
                return defaultValue;
            }

            return input;
        };
    });
    
    app.filter('sumByKey', function () {
        return function (data, key) {
            if (typeof (data) === 'undefined' || typeof (key) === 'undefined') {
                return 0;
            }

            var sum = 0;
            for (var i = data.length - 1; i >= 0; i--) {
                sum += parseInt(data[i][key]);
            }

            return sum;
        };
    });
                

})();

var session = {
    
    setUsuario: function(user){
      
        sessionStorage.setItem("usuario",JSON.stringify(user));
       
    },
    
    getUsuario: function(){
      
        return this.validarObjectLocal("usuario")? JSON.parse(sessionStorage.getItem("usuario")) :  null;
       
    },
    
    validarObjectLocal: function(string){
        
        return sessionStorage.getItem(string) !== "" && sessionStorage.getItem(string) !== undefined && sessionStorage.getItem(string) !== null;
        
    },
    
    cerrarSesion: function(){
        sessionStorage.setItem("usuario","");
        location.href = "index.html";
    }

};