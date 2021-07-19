//ie 8
String.prototype.trim = function() {return this.replace(/^\s+|\s+$/g, '');}
//ie 8
Object.keys=Object.keys||function(o,k,r){r=[];for(k in o)r.hasOwnProperty.call(o,k)&&r.push(k);return r}