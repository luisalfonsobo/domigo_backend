app.controller('productosController', function ($scope, productoService) {
    //VistaModelo
    $scope.Product = {}; //Objeto Actual
    $scope.Products = []; //Listado de Objetos
    $scope.Estants = []; //Listado de Estantes
    $scope.Claves = [];
    $scope.editMode = false; // Modo de Edición
    $scope.accion = "guardar";
    $scope.active = "";
    //Cargar los datos
    loadRecords();
    
    $scope.ocultar=false;
    
    //Function to Reset Scope variables
    function initialize() {
        $scope.Product = {};
        $scope.Product.id = "";
        $scope.Product.descripcion = "";
        $scope.Product.precio = "";
        $scope.Product.referencia = "";
        $scope.Product.codigo = "";
        $scope.Product.stockMinimo = "";
        $scope.Product.idEstante = "";
    }
    
    function loadRecords() {
        var promiseGet = productoService.getAll(); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Products = pl.data;},
              function (errorPl) {
                  console.log('failure loading Productos', errorPl);
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
        $scope.title = "Nuevo Servicio";
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
        product.precio = parseInt(product.precio);
        $scope.Product = product;
        $scope.title = "Modificar Servicio";
         
        $('#modal').openModal();
     };
     
    $scope.showconfirm = function () {
         $scope.Product = this.Product;
         //$('#confirmModal').modal('show');
     };
    $scope.edit = function () {
         $scope.Product = this.Product;
         $scope.editMode = true;
         //$('#regModal').modal('show');
     };
    //Function to Submit the form
    $scope.add = function () {
        
        var Prod = {
            descripcion: $scope.Product.descripcion,
            precio:$scope.Product.valor,
            nombre:$scope.Product.nombre,
            estado:$scope.Product.estado
        };
        
        var promisePost = productoService.post(Prod);
        promisePost.then(function (d) {
            
            //$scope.Product.descripcion = d.data.resquest.descripcion;
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Guardar El Servicio");
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
    
    $scope.cerrarModalInventario = function(){
        initialize(); 
        $('#modalInventario').closeModal();
    };
    
    //Functin Para Actualizar
    $scope.update = function () {
        var Prod = {
            id: $scope.Product.id,
            descripcion: $scope.Product.descripcion,
            precio:$scope.Product.valor,
            nombre:$scope.Product.nombre,
            estado:$scope.Product.estado
        };
        
        var promisePost = productoService.put(Prod.id, Prod);
        promisePost.then(function (d) {
            
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Modificar El Producto");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
   }; 
});