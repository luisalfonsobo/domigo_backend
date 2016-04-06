app.controller('proveedoresController', function ($scope, proveedorService) {
    //VistaModelo
    $scope.Proveedor = {}; //Objeto Actual
    $scope.Proveedores = []; //Listado de Objetos
    $scope.editMode = false; // Modo de Edición
    $scope.accion = "guardar";
    $scope.active = "";
    //Cargar los datos
    loadRecords();
    
    
    //Function to Reset Scope variables
    function initialize() {
        $scope.Proveedor = {};
        $scope.Proveedor.id = "";
        $scope.Proveedor.nombre = "";
        $scope.Proveedor.direccion = "";
        $scope.Proveedor.telefono = "";
        $scope.Proveedor.email = "";
        $scope.Proveedor.nit = "";
    }
    
    
    //Function to load all Employee records
    function loadRecords() {
        var promiseGet = proveedorService.getAllCentrales(); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Proveedores = pl.data;},
              function (errorPl) {
                  console.log('failure loading Centrales', errorPl);
              });
    }
    
    $scope.activar = function(central){
        
        
        var estado =central.estado;
        
        if(estado == 'ACTIVO'){
            estado = 'INACTIVO';
        }else{
            estado = 'ACTIVO';
        }
        
        var object ={estado:estado};
        
        var promisePost = proveedorService.putEstadoCentral(central.id, object);
        promisePost.then(function (d) {
            
            loadRecords();
            alert(d.data.message);
            
        }, function (err) {
            alert("Error Al Modificar la Central");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
        
    };
    
    
    $scope.ejecutar = function(){
      
        if($scope.accion == "guardar"){
            
            $scope.add();
            
        }else{
            
            $scope.update();
            
        }
        
    };
    
    $scope.nuevo = function(){
        $scope.accion = "guardar";
        $scope.title = "Nueva Central";
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
    $scope.get = function (product) {
        $scope.active = "active";
        $scope.accion = "modificar";
        $scope.Proveedor = product;
        $scope.title = "Modificar Central";
         
        $('#modal').openModal();
     };
    $scope.showconfirm = function () {
         $scope.Proveedor = this.Proveedor;
         //$('#confirmModal').modal('show');
     };
    $scope.edit = function () {
         $scope.Proveedor = this.Proveedor;
         $scope.editMode = true;
         //$('#regModal').modal('show');
     };
    //Function to Submit the form
    $scope.add = function () {
        
        var Prov = {
            nombre: $scope.Proveedor.nombre,
            direccion: $scope.Proveedor.direccion,
            telefono: $scope.Proveedor.telefono,
            email: $scope.Proveedor.email,
            nit: $scope.Proveedor.nit
        };
        
        var promisePost = proveedorService.postCentral(Prov);
        promisePost.then(function (d) {
            
            //$scope.Proveedor.descripcion = d.data.resquest.descripcion;
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Guardar la Central");
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
        var prov = {
            id: $scope.Proveedor.id,
            nombre: $scope.Proveedor.nombre,
            direccion: $scope.Proveedor.direccion,
            telefono: $scope.Proveedor.telefono,
            email: $scope.Proveedor.email,
            nit: $scope.Proveedor.nit
        };
        
        var promisePost = proveedorService.putCentral(prov.id, prov);
        promisePost.then(function (d) {
            
            loadRecords();
            
            alert(d.data.message + ", " +d.data.request);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Modificar la Central");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
   }; 
//    //Confirmar Para Eliminar
//    $scope.showconfirm = function () {
//         $scope.Proveedor = this.proveedor;
//         if(confirm("Desea Eliminar El Proveedor:" +$scope.Proveedor.descripcion)){
//             alert("Implemente el método para eliminar" );
//             //Invocar Servicio de Eliminación
//             //Actualizar datos de $scope.Contacts 
//         }
//     };
});