app.controller('sessionController',function ($scope){  
    
    $scope.admin =false;
    $scope.empresa = false;
    $scope.moto = false;
    $scope.cliente = false;
    $scope.sitio = false;
    
    var user = JSON.parse(session.getUsuario());
    console.log(user);
    
    switch (user[0].tipoAcceso){
        
        case 1: 
            $scope.admin = true;
            break;
        case 2: 
            $scope.empresa = true;
            break;
        case 3:
            $scope.moto = true;
            break;
        case 4: 
            $scope.cliente = true;
            break;
        case 5:
            $scope.sitio = true;
            break;
        
    }
    
    
});


