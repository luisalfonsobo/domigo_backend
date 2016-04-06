
function completZeroFront(cadena, count) {
    if ((String(cadena)).length >= count) { return cadena; } /* No hay que agregar nada */
    var length = (String(cadena)).length; /* Cantidad de caracteres de la cadena */    
    for (var index = 0; index < length; index++)  { cadena = '0' + cadena; }    
    return cadena; /* Cadena con los ceros por delantes establecidos */
}

Object.defineProperty(Date.prototype, 'getDateFormat', {
    enumerable: false,
    
    value: function () {
        var fecha = completZeroFront(this.getFullYear(),4); /* Año */
        fecha += '-' + completZeroFront((this.getMonth() + 1),2); /* Mes */
        fecha += '-' + completZeroFront(this.getDate(),2); /* Dia */
        return fecha; /* Retornando fecha con formato aaaa-mm-dd */
    }
});

Object.defineProperty(Date.prototype, 'getTimeFormat', {
    enumerable: false,
    value: function () {
        var hora = completZeroFront(this.getHours(),2); /* Horas */
        hora += ':' + completZeroFront(this.getMinutes(),2); /* Minutos */
        hora += ':' + completZeroFront(this.getSeconds(),2); /* Segundos */
        return hora; /* Retornando hora con formato hh:mm:ss */
    }
});

Object.defineProperty(Date.prototype, 'getDateTimeFormat', {
    enumerable: false,
    
    value: function () {
        return this.getDateFormat() + ' ' + this.getTimeFormat();
    }
});