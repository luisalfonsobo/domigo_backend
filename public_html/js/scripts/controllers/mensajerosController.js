app.controller('mensajerosController', function ($scope, personasService, vehiculoService) {
    //VistaModelo
    $scope.Persona = {}; //Objeto Actual
    $scope.Personas = []; //Listado de Objetos
    $scope.editMode = false; // Modo de Edición
    $scope.accion = "guardar";
    $scope.active = "";
    
    $scope.Facturas = [];
    $scope.Factura = {};
    $scope.Detalles=[];
    //Cargar los datos
    loadRecords();
    loadVehiculos();
    
    $scope.Mensajero={id:""};
    
    function loadVehiculos() {
        var user = JSON.parse(session.getUsuario());
        var promiseGet = vehiculoService.getAllByEmpresa(user[1].id); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Vehiculos = pl.data;},
              function (errorPl) {
                  console.log('failure loading Contactos', errorPl);
              });
    }
    
    $scope.buscarMensajero = function(){
        
        var promiseGet = personasService.getMensajeroByCedula($scope.Mensajero.cedula);
        promiseGet.then(function (pl) {
            
            if(pl.data ==null){
                
                alert("Mensajero no Encontrado");
                $scope.Mensajero={id:""};
                
            }else{
                $scope.Mensajero = pl.data;
            }
            
        },
        
        function (errorPl) {
            alert("Mensajero no Encontrado");
            $scope.Mensajero={id:""};
        });
        
    };
    
    $scope.addMensajero = function(){
        
        if($scope.Mensajero.id==""){
            return;
        }
        
        var user = JSON.parse(session.getUsuario());
        console.log(user[1]);
        var object = {
            idEmpresa: user[1].id
        };
        
        var promisePost = personasService.putEmpresa($scope.Mensajero.id, object);
        promisePost.then(function (d) {
            
            alert(d.data.message);
            console.log(d.data.request);
            $('#modalAgregar').closeModal();
            loadRecords();
        }, function (err) {
            if(err.status == 401){
                alert(err.data.message);
            }else{
                alert("Error Al Guardar El Cliente");
            }
            
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
        
        
    };
    
    //Function to Reset Scope variables
    function initialize() {
        $scope.Persona = {};
        $scope.Persona.id = "";
        $scope.Persona.cedula = "";
        $scope.Persona.nombres = "";
        $scope.Persona.apellidos = "";
        $scope.Persona.direccion = "";
        $scope.Persona.telefono = "";
        $scope.Persona.email = "";
        $scope.Persona.sexo = "M";
    }
    
    
    //Function to load all Employee records
    function loadRecords() {
        
        var user = JSON.parse(session.getUsuario());
        
        var promiseGet = personasService.getMensajerosByIdEmpresa(user[1].id);
        promiseGet.then(function (pl) {$scope.Personas = pl.data;},
              function (errorPl) {
                  console.log('failure loading Mensajero', errorPl);
              });
    }
    
    
    function loadFacturas(idPersona) {
        var promiseGet = facturaService.getByIdPersona(idPersona); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Facturas = pl.data;},
              function (errorPl) {
                  console.log('failure loading Clientes', errorPl);
              });
    }
    
    function loadDetalles(idFactura) {
        var promiseGet = facturaService.getDetalles(idFactura); //The Method Call from service
        promiseGet.then(function (pl) {$scope.Detalles = pl.data;},
              function (errorPl) {
                  console.log('failure loading Clientes', errorPl);
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
        initialize();
        $scope.accion = "guardar";
        $scope.title = "Nuevo Cliente";
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
    $scope.get = function (persona) {
        $scope.active = "active";
        $scope.accion = "modificar";
        $scope.Persona = persona;
        $scope.title = "Modificar Cliente";
         
        $('#modal').openModal();
     };
     
     $scope.devolver=function(detalle){
         
         var cantidad = prompt("¿Cantidad a Devolver?", 1);
                if (cantidad != null && cantidad > 0 && cantidad <= detalle.cantidad) {
                    
                     
                    var motivo = prompt("¿Motivo de la Devolución?", "Escriba Aquí Motivo de la Devolución");
                    if (motivo != null || motivo != "") {
                       var devolucion = {
                            idDetalle: detalle.id,
                            motivo: motivo,
                            cantidad: cantidad
                        }; 
                        var promisePost = devolucionDetalleFacturaService.post(devolucion);
                        promisePost.then(function (d) {

                            alert(d.data.message);

                            loadFacturas($scope.Persona.id);

                        }, function (err) {
                            if(err.status == 401){
                                alert(err.data.message);
                            }else{
                                alert("Error Al procesar Solicitud");
                            }

                            console.log("Some Error Occured "+ JSON.stringify(err));
                        });
                        
                    }else{
                        alert("Digite un motivo Válido");
                    }
                    
                }else{
                    alert("Digite una cantidad Válida");
                }
         
     };
     
     $scope.devolverFactura=function(factura){
         
        if(confirm("¿Estas Seguro de anular la Factura?")){
            
            var motivo = prompt("¿Motivo de la Anulación?", "Escriba Aquí Motivo de la Anulación");
            if (motivo != null && motivo != "") {
                var devolucion = {
                    idFactura: factura.id,
                    motivo: motivo
                }; 
                var promisePost = devolucionFacturaService.post(devolucion);
                promisePost.then(function (d) {
            
                    alert(d.data.message);

                    loadFacturas($scope.Persona.id);
            
                }, function (err) {
                    if(err.status == 401){
                        alert(err.data.message);
                    }else{
                        alert("Error Al procesar Solicitud");
                    }

                    console.log("Some Error Occured "+ JSON.stringify(err));
                });
                
                        
            }else{
                alert("Digite un motivo Válido");
            }
            
        }
     
     };
     
     $scope.getDetalles = function (factura) {
        
        $scope.Factura = factura;
        loadDetalles($scope.Factura.id);
         
        $('#modalDetalles').openModal();
     };
    $scope.showconfirm = function () {
         $scope.Persona = this.Persona;
         //$('#confirmModal').modal('show');
     };
    $scope.edit = function () {
         $scope.Persona = this.Persona;
         $scope.editMode = true;
         //$('#regModal').modal('show');
     };
    //Function to Submit the form
    $scope.add = function () {
        
        var Pers = {
            cedula: $scope.Persona.cedula,
            nombres:$scope.Persona.nombres,
            apellidos:$scope.Persona.apellidos,
            direccion: $scope.Persona.direccion,
            telefono: $scope.Persona.telefono,
            sexo: $scope.Persona.sexo,
            email: $scope.Persona.email,
            persona: "cliente"
        };
        
        var promisePost = personasService.post(Pers);
        promisePost.then(function (d) {
            
            //$scope.Persona.descripcion = d.data.resquest.descripcion;
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            if(err.status == 401){
                alert(err.data.message);
            }else{
                alert("Error Al Guardar El Cliente");
            }
            
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
    };
    
    $scope.asignar= function(persona){
        
        $scope.Mensajero = persona;
        $('#modalAsignar').openModal();
        
    };
    
    $scope.escoger = function(vehiculo){
        
        var Pers = {
            idMensajero: $scope.Mensajero.id,
            idVehiculo: vehiculo.id
        };
        
        var promisePost = personasService.putVehiculo($scope.Mensajero.id,Pers);
        promisePost.then(function (d) {
            
            //$scope.Persona.descripcion = d.data.resquest.descripcion;
            loadRecords();
            
            alert(d.data.message);
            initialize();
            $('#modalAsignar').closeModal();
            
            
        }, function (err) {
            if(err.status == 401){
                alert(err.data.message);
            }else{
                alert("Error al Ejecutar el proceso");
            }
            
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
    };
    
    //Function to Cancel Form
    $scope.cancelForm = function () {
        initialize();
    };
    
    $scope.cerrarModalAgregar= function(){
        $('#modalAgregar').closeModal();
    };
    
    $scope.cerrarModal = function(){
        initialize(); 
        $('#modal').closeModal();
    };
    
    $scope.cerrarModalMovimientos = function(){
        
        $('#modalMovimientos').closeModal();
    };
    
    $scope.cerrarModalDetalles = function(){
        
        $('#modalDetalles').closeModal();
    };
    
    $scope.cerrarModalAsignar = function(){
        $('#modalAsignar').closeModal();
    };
    
    $scope.movimientos = function(persona){
        
        $scope.Persona = persona;
        loadFacturas($scope.Persona.id);
        $('#modalMovimientos').openModal();
        
    };
    
    //Functin Para Actualizar
    $scope.update = function () {
        var Prod = {
            id: $scope.Persona.id,
            cedula: $scope.Persona.cedula,
            nombres:$scope.Persona.nombres,
            apellidos:$scope.Persona.apellidos,
            direccion: $scope.Persona.direccion,
            telefono: $scope.Persona.telefono,
            sexo: $scope.Persona.sexo,
            email: $scope.Persona.email
        };
        
        var promisePost = personasService.put(Prod.id, Prod);
        promisePost.then(function (d) {
            
            loadRecords();
            
            alert(d.data.message + ", " +d.data.request);
            initialize();
            $('#modal').closeModal();
            
        }, function (err) {
            alert("Error Al Modificar El Cliente");
            console.log("Some Error Occured "+ JSON.stringify(err));
        });
   }; 
    //Confirmar Para Eliminar
    $scope.showconfirm = function () {
         $scope.Persona = this.persona;
         if(confirm("Desea Eliminar El Cliente:" +$scope.Persona.descripcion)){
             alert("Implemente el método para eliminar" );
             //Invocar Servicio de Eliminación
             //Actualizar datos de $scope.Contacts 
         }
     };
});