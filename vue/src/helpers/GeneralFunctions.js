// This is your plugin object. It can be exported to be used anywhere.
import $$ from "jquery";
import moment from "moment-timezone";

const MyPlugin = {
    // The install method is all that needs to exist on the plugin object.
    // It takes the global Vue object as well as user-defined options.

    install(Vue, axios, $, $store, $router) {
        // We call Vue.mixin() here to inject functionality into all components.

        Vue.prototype.$formatNumber = (num, prefix) => {
            num = Math.round(parseFloat(num) * Math.pow(10, 2)) / Math.pow(10, 2)
            prefix = prefix || '';
            num += '';
            var splitStr = num.split('.');
            var splitLeft = splitStr[0];
            var splitRight = splitStr.length > 1 ? '.' + splitStr[1] : '.00';
            splitRight = splitRight + '00';
            splitRight = splitRight.substr(0, 3);
            var regx = /(\d+)(\d{3})/;

            while (regx.test(splitLeft)) {
                splitLeft = splitLeft.replace(regx, '$1' + ',' + '$2');
            }
            return prefix + splitLeft + splitRight;
        }

        Vue.prototype.$close_session = () => {
            $store.dispatch("logout");
            $router.push({ name: "login" });
        }

        // FUNCION PARA QUE EL CAMPO INPUT SOLO ACEPTE NUMEROS
        Vue.prototype.$onlyNums = (event, obj, cmp) => {
            /*
                @ event = corresponde al target del input
                @ obj   = corresponde al obj del cual se va recibir el parametro a evaluar, ejemp: objEmpresa
                @ cmp   = corresponde al atributo del objeto al que se va validar, ejemp: objEmpresa.Cantidad, se recibe como string 'Cantidad'
            */
            var valor = event.target;
            if ((valor.value.match(/[^0-9]/))) {
                valor.value = '';
                obj[cmp] = '';
                //valor.focus();
            }
        }

        // FUNCION PARA QUE EL CAMPO INPUT SOLO ACEPTE NUMEROS Y CON PUNTO DECIMAL
        Vue.prototype.$number_decimal = (event, obj, cmp) => {
            /*
                @ event = corresponde al target del input
                @ obj   = corresponde al obj del cual se va recibir el parametro a evaluar, ejemp: objEmpresa
                @ cmp   = corresponde al atributo del objeto al que se va validar, ejemp: objEmpresa.Cantidad, se recibe como string 'Cantidad'
            */
            var valor = event.target;
            if (!(valor.value.match(/^-?[0-9]+([\.][0-9]*)?$/))) {
                valor.value = '';
                obj[cmp] = '';
                //valor.focus();
            }
        }

        // FUNCION PARA VALIDAR QUE EL CAMPO DEL INPUT NO PUEDA EXCEDER EL NUMERO DEL VALOR MAX ESTABLECIDO
        Vue.prototype.$validaCant = (event, obj, cmp, valmax) => {
            /*
                @ event = corresponde al target del input
                @ obj   = corresponde al obj del cual se va recibir el parametro a evaluar, ejemp: objEmpresa
                @ cmp   = corresponde al atributo del objeto al que se va validar, ejemp: objEmpresa.Cantidad, se recibe como string 'Cantidad'
                @ valmax= corresponde a la cantidad que servira de limite, ejemp: 10
            */
            var valor = event.target;
            if (valor.value > valmax) {
                valor.value = valmax;
                obj[cmp] = valmax;
            }
        }

        // FUNCION PARA GUARDAR EN UNA SESION LOS FILTROS EFECTUADOS Y AL REGRESAR A ESA VISTA NO SE PIERDAN
        Vue.prototype.$saveFilters = function(action, nivel = 1) {
            /*
                @ action    = condicional para indicar si va colocar o recibir parametros, valores: SET, GET
                @ nivel     = condicional para indicar q nivel de listado es 1,2,3 es decir List padre-hijo-nieto
            */
            if (action == 'SET') {
                sessionStorage.setItem('sFiltros' + nivel, JSON.stringify(this.Filtro));
            } else {
                let SesionFiltros = JSON.parse(sessionStorage.getItem('sFiltros' + nivel));
                this.Filtro = SesionFiltros;
            }
        }

        // FUNCION PARA PREVISUALIZAR LA IMAGEN QUE SE CARGA EN UN INPUT FILE DE TIPO IMAGEN-PREVIEW
        Vue.prototype.$uploadImagePreview = function(event, valid, custom) {
            /*
                @ event = corresponde al target del input
                @ valid = corresponde al configurativo de helpers para validar tipo de archivo y peso
                @ custom= corresponde a los parametros de los campos a leer, se recibe como un array de string, ejemp Array('campo de la imagen tmp','div donde se muestra')
            */

            let ImagenTmp = custom[0];
            let IdDiv = custom[1];

            const file2 = event.target;
            const image2 = file2.files[0];

            if (image2 != undefined) {
                let FileSize = image2.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    file2.type = 'text'
                    file2.type = 'file';
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image2.name)) {
                    this.$toast.info(valid.MsgFormato);
                    file2.type = 'text'
                    file2.type = 'file';
                    return false;
                }

                const readerizado = new FileReader();

                readerizado.readAsDataURL(image2);
                readerizado.onload = r => {
                    this[ImagenTmp] = r.target.result;
                    this.$readURL(this[ImagenTmp], IdDiv);
                }
            } else {
                this[ImagenTmp] = '';
                this.$readURL(this[ImagenTmp], IdDiv);
            }
        }

        Vue.prototype.$readURL = function(ImgTmp, IdDiv) {
            let IdCmp = IdDiv;
            let Colocar = ImgTmp;

            $$('#' + IdCmp).css('background-image', 'url(' + Colocar + ')');
            $$('#' + IdCmp).hide();
            $$('#' + IdCmp).fadeIn(650);
        }

        Vue.prototype.$uploadImagePreviewArray = function(event, valid, item, custom) {
            /*
                @ event = corresponde al target del input
                @ valid = corresponde al configurativo de helpers para validar tipo de archivo y peso
                @ item  = corresponde al array del cual se van a leer los elementos del custom
                @ custom= corresponde a los parametros de los campos a leer, se recibe como un array de string, ejemp
                Array (
                    'Nombre de la variable que contiene el nombre del archivo',
                    'Nombre de la variable de la imagen tmp',
                    'Nombre de la variable que contiene la ruta de imagen'
                    'Si existe (input file alargados), es el nombre de la variable q contendra el nombre visible'
                )
            */

            let Imagen = custom[0];
            let ImagenTmp = custom[1];
            let RutaImagen = custom[2];
            let NameFile = (custom[3])?custom[3]:'';

            let textBase = valid.NameFile;
            const arch  = event.target;
            const image = arch.files[0];
            const ImageOrg = (item[Imagen]!='')?this[RutaImagen]+item[Imagen]:'';

            if (image != undefined) {
                let FileSize = image.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    if(NameFile!=''){
                        item[NameFile] = textBase
                    };
                    item[ImagenTmp] = ImageOrg;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    if(NameFile!=''){
                        item[NameFile] = textBase;
                    }
                    item[ImagenTmp] = ImageOrg;
                    return false;
                }

                if(NameFile!=''){
                    item[NameFile] = image.name;
                }
                const reader = new FileReader();

                reader.readAsDataURL(image);
                reader.onload = e => {
                    item[ImagenTmp] = e.target.result;
                }
            } else {
                if(NameFile!=''){
                    item[NameFile] = textBase;
                }
                item[ImagenTmp] = ImageOrg;
            }
        }

        Vue.prototype.$previewImage = function(event, valid) {
            let textBase = valid.NameFile;
            const arch = event.target;
            const image = arch.files[0];

            if (image != undefined) {
                let FileSize = image.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    this.NameFile = textBase;
                    this.ImagenTmp = this.RutaFile;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    this.NameFile = textBase;
                    this.ImagenTmp = this.RutaFile;
                    return false;
                }

                this.NameFile = image.name;
                const reader = new FileReader();

                reader.readAsDataURL(image);
                reader.onload = e => {
                    this.ImagenTmp = e.target.result;
                }
            } else {
                this.NameFile = textBase;
                this.ImagenTmp = this.RutaFile;
            }
        }

        Vue.prototype.$previewImageArray = function(event, valid, item) {
            let textBase = valid.NameFile;
            const arch = event.target;
            const image = arch.files[0];
            const ImageOrg = this.RutaFile + item.Imagen;

            if (image != undefined) {
                let FileSize = image.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    item.NameFile = textBase;
                    item.ImagenTmp = ImageOrg;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    item.NameFile = textBase;
                    item.ImagenTmp = ImageOrg;
                    return false;
                }

                item.NameFile = image.name;
                const reader = new FileReader();

                reader.readAsDataURL(image);
                reader.onload = e => {
                    item.ImagenTmp = e.target.result;
                }
            } else {
                item.NameFile = textBase;
                item.ImagenTmp = ImageOrg;
            }
        }

        Vue.prototype.$previewImageCustom = function(event, valid, custom) {
            let Imagen = custom[0];
            let NameFile = custom[1];
            let ImagenTmp = custom[2];

            let textBase = valid.NameFile;
            const arch = event.target;
            const image = arch.files[0];
            const ImageOrg = this[Imagen];

            if (image != undefined) {
                let FileSize = image.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    this[NameFile] = textBase;
                    this[ImagenTmp] = ImageOrg;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    this[NameFile] = textBase;
                    this[ImagenTmp] = ImageOrg;
                    return false;
                }

                this[NameFile] = image.name;
                const reader = new FileReader();

                reader.readAsDataURL(image);
                reader.onload = e => {
                    this[ImagenTmp] = e.target.result;
                }
            } else {
                this[NameFile] = textBase;
                this[ImagenTmp] = ImageOrg;
            }
        }

        Vue.prototype.$previewImageArrayCustom = function(event, valid, item, custom) {
            let Imagen = custom[0];
            let NameFile = custom[1];
            let ImagenTmp = custom[2];

            let textBase = valid.NameFile;
            const arch = event.target;
            const image = arch.files[0];
            const ImageOrg = this.RutaFile + item[Imagen];

            if (image != undefined) {
                let FileSize = image.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    item[NameFile] = textBase;
                    item[ImagenTmp] = ImageOrg;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(image.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    item[NameFile] = textBase;
                    item[ImagenTmp] = ImageOrg;
                    return false;
                }

                item[NameFile] = image.name;
                const reader = new FileReader();

                reader.readAsDataURL(image);
                reader.onload = e => {
                    item[ImagenTmp] = e.target.result;
                }
            } else {
                item[NameFile] = textBase;
                item[ImagenTmp] = ImageOrg;
            }
        }

        Vue.prototype.$uploadFileArrayCustom = function(event, valid, item, custom) {
            let Archivo = custom[0];
            let NameFile = custom[1];

            let textBase = valid.NameFile;
            const arch = event.target;
            const fileArch = arch.files[0];

            if (fileArch != undefined) {
                let FileSize = fileArch.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    item[NameFile] = textBase;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(fileArch.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    item[NameFile] = textBase;
                    return false;
                }

                item[NameFile] = fileArch.name;
            } else {
                item[NameFile] = textBase;
            }
        }

        Vue.prototype.$uploadFileCustom = function(event, valid, custom) {
            let Imagen = custom[0];
            let NameFile = custom[1];

            let textBase = valid.NameFile;
            const arch = event.target;
            const fileArch = arch.files[0];

            if (fileArch != undefined) {
                let FileSize = fileArch.size / 1024 / 1024; // in MB
                if (FileSize > valid.Peso) {
                    this.$toast.info(valid.MsgPeso);
                    arch.type = 'text'
                    arch.type = 'file';
                    this[NameFile] = textBase;
                    return false;
                }

                let allowedExtensions = valid.Formatos;
                if (!allowedExtensions.exec(fileArch.name)) {
                    this.$toast.info(valid.MsgFormato);
                    arch.type = 'text'
                    arch.type = 'file';
                    this[NameFile] = textBase;
                    return false;
                }
                //console.log(this[NameFile])
                this[NameFile] = fileArch.name;
            } else {
                this[NameFile] = textBase;
            }
        }

        Vue.prototype.$setStartItem = function () {
            let nPag = this.Filtro.Pagina;
            let nItems = this.Filtro.Entrada;
            this.counterField = 0;
            if (nPag !== 0){
                if(nPag === 1){
                    this.counterField = 1;

                }else if(nPag > 1) {
                    let pag = (parseInt(nPag) - 1);
                    this.counterField = ((pag * parseInt(nItems)) + 1);
                }
            }
        }

        Vue.prototype.$getNumItem = function (indx) {
            if(parseInt(indx) === 0){
                return this.counterField;
            }else {
                return (parseInt(this.counterField) + indx);
            }
        }

        Vue.prototype.$getCleanDate = function (date,needHours = true) {
            if(date !== ''){
                let orDate = moment(date);
                if(needHours){
                    return orDate.tz('America/Mexico_City').format('DD-MM-YYYY (h:mm a)');
                }else {
                    return orDate.tz('America/Mexico_City').format('DD-MM-YYYY');
                }

            }else {
                return ''
            }
        }

        Vue.prototype.$limitCharacters = function (text, limite = 50) {
            if(text !== ''){
                let points = text.length > limite ? '...' : '';
                return text.substr(0,limite)+points;
            }else {
                return ''
            }
        }

		Vue.prototype.$numberTo = function (num) {
			//value = value.toFixed(0);
			let fixed = 0;
			if (num === null) {
				return null;
			} // terminate early
			if (num === 0) {
				return "0";
			} // terminate early
			fixed = !fixed || fixed < 0 ? 0 : fixed; // number of decimal places to show
			var b = num.toPrecision(2).split("e"), // get power
				k = b.length === 1 ? 0 : Math.floor(Math.min(b[1].slice(1), 14) / 3), // floor at decimals, ceiling at trillions
				c =
					k < 1
						? num.toFixed(0 + fixed)
						: (num / Math.pow(10, k * 3)).toFixed(1 + fixed), // divide by power
				d = c < 0 ? c : Math.abs(c), // enforce -0 is 0
				e = d + ["", " K", " M", " B", " T"][k]; // append power
			return e;
		}


    }
}

export default MyPlugin;
