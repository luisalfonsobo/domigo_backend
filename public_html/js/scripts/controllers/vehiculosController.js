app.controller('vehiculosController', function ($scope, vehiculoService) {
    //VistaModelo
    $scope.Estante = {}; //Objeto Actual
    $scope.Estantes = []; //Listado de Objetos
    $scope.editMode = false; // Modo de Edición
    $scope.accion = "guardar";
    $scope.active = "";
    //Cargar los datos
    loadRecords();
    
    //Function to Reset Scope variables
    function initialize() {
        $scope.Vehiculo = {};
        $scope.Vehiculo.id = "";
        $scope.Vehiculo.placa = "";
        $scope.Vehiculo.color = "";
    }
    
    
    //Function to load all Employee records
    function loadRecords() {
        var user = JSON.parse(session.getUsuario());
        var promiseGet = vehiculoService.getAllByEmpresa(user[1].id); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Vehiculos = pl.data;},
              function (errorPl) {
                  console.log('failure loading Contactos', errorPl);
              });
    }
    
    $scope.ejecutar = function(){
      
        if($scope.accion == "guardar"){
            
            $scope.add();
            
        }else{
            
            $scope.update();
            
        }
        
    };
    
    $scope.nuevo = function(){
        $scope.accion = "guardar";
        $scope.title = "Nuevo Estante";
        $scope.active = "";
    };
    
    //Model popup events
    $scope.showadd = function () {
        initialize();
        $scope.editMode = false;
        //$('#regModal').modal('show');
    };
    $scope.cancel = function () {
         console.log($scope.editMode);
         if (!$scope.editMode) {
             initialize();
         }
         //$('#regModal').modal('hide');
     };
    $scope.get = function (Estant) {
        $scope.active = "active";
        $scope.accion = "modificar";
        $scope.Estante = Estant;
        $scope.title = "Modificar Estante";
         
        $('#modal').openModal();
     };
    $scope.showconfirm = function () {
         $scope.Estante = this.Estante;
         //$('#confirmModal').modal('show');
     };
    $scope.edit = function () {
         $scope.Estante = this.Estante;
         $scope.editMode = true;
         //$('#regModal').modal('show');
     };
    //Function to Submit the form
    $scope.add = function () {
        
        var user = JSON.parse(session.getUsuario());
        
        var Estant = {
            placa: $scope.Vehiculo.placa,
            color:$scope.Vehiculo.color,
            idEmpresa: user[1].id            
        };
        
        var promisePost = vehiculoService.post(Estant);
        promisePost.then(function (d) {
            
            //$scope.Product.descripcion = d.data.resquest.descripcion;
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Guardar El Estante");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
    };
    //Function to Cancel Form
    $scope.cancelForm = function () {
        initialize();
    };
    
    
    $scope.cerrarModal = function(){
        initialize(); 
        $('#modal').closeModal();
    };
    
    //Functin Para Actualizar
    $scope.update = function () {
        var Estant = {
            id: $scope.Vehiculo.id,
            placa: $scope.Vehiculo.placa,
            color:$scope.Vehiculo.color
        };
        
        var promisePost = vehiculoService.put(Estant.id, Estant);
        promisePost.then(function (d) {
            
            loadRecords();
            
            alert(d.data.message + ", " +d.data.request);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Modificar El Estante");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
   }; 
    //Confirmar Para Eliminar
    $scope.showconfirm = function () {
         $scope.Estante = this.estante;
         if(confirm("Desea Eliminar El Estante:" +$scope.Estante.descripcion)){
             alert("Implemente el método para eliminar" );
             //Invocar Servicio de Eliminación
             //Actualizar datos de $scope.Contacts 
         }
     };
});