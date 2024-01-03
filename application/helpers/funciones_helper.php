<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function returnLink()
{
    $link ='';
    $link='http://192.168.1.166:8080/';

    return $link;
}
function returnLinkPrueba()
{
    $link ='';
    $link='http://192.168.1.166:8080/';

    return $link;
}

function returnLinkFotosServicios()
{
	return 'https://desprosoft.online/services/assets/oldsystemfile/reportefoto/';
}

function SumFechas($Tipo="Mes",$Fecha,$Numero){
    $Fecha = date('d-m-Y', strtotime($Fecha));
    $nuevafecha = strtotime ( '+'.$Numero.'month' , strtotime ( $Fecha ) ) ;
    if ($Tipo == "Anio") {
       $nuevafecha = strtotime ( '+'.$Numero.'year' , strtotime ( $Fecha ) ) ;
    }else if ($Tipo == "Dia") {
       $nuevafecha = strtotime ( '+'.$Numero.'day' , strtotime ( $Fecha ) ) ;
    }
    $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
    return $nuevafecha;
}

function quitarcomas($Numero)
{
   $Nuevo = str_replace(',','',$Numero);
   return $Nuevo;
}

function quitarCaracteres($string)
{
    $string = trim($string);
    $string = str_replace(
        array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
        array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
        $string
    );

    $string = str_replace(
        array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
        array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
        $string
    );

    $string = str_replace(
        array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
        array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
        $string
    );

    $string = str_replace(
        array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
        array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
        $string
    );

    $string = str_replace(
        array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
        array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
        $string
    );

    $string = str_replace(
        array('ñ', 'Ñ', 'ç', 'Ç'),
        array('n', 'N', 'c', 'C',),
        $string
    );

    //Esta parte se encarga de eliminar cualquier caracter extraño
    $string = str_replace(
        array("\"", "¨", "º", "-", "~",
             "#", "@", "|", "!", '"',
             "·", "$", "%", "&", "/",
             "(", ")", "?", "'", "¡",
             "¿", "[", "^", "<code>", "]",
             "+", "}", "{", "¨", "´",
             ">", "< ", ";", ",", ":",
             ".", " "),
        '',
        $string
    );

    return $string;
}

function dateformato($fecha)
{
    //$Fecha = date('Y-m-d', strtotime($fecha));
	$F1 = new DateTime($fecha);
	$Fecha = $F1->format('Y-m-d');
    return $Fecha;
}

function dateformatomx($fecha)
{
    //$Fecha = date('d-m-Y', strtotime($fecha));
	$F1 = new DateTime($fecha);
	$Fecha = $F1->format('Y-m-d');
    return $Fecha;
}

function get_id_contador($campo){
    $CI =& get_instance();
    $CI->load->model("Mempresa");
    $datosObjeto = new Mempresa();
    $datosObjeto->IdEmpresa = 1;
    $datosObjeto->campo = $campo;
    $datosObjeto->get_recovery_contador_empresa();
    $n2 = 1;
    $valor = $datosObjeto->campo + $n2;
    return $valor;
}

function get_update_contador($campo,$nuevonumero){
    $CI =& get_instance();
    $CI->load->model("Mempresa");
    $datosObjeto = new Mempresa();
    $datosObjeto->campo = $campo;
    $datosObjeto->contador = $nuevonumero;
    $valor = $datosObjeto->set_update_contador();
    return $valor;
}

function get_Folio($IdSucursal,$Tipo){
    $CI =& get_instance();
    $CI->load->model("Mfolio");
    $datosObjeto = new Mfolio();
    $datosObjeto->IdSucursal = $IdSucursal;
    $datosObjeto->Tipo = $Tipo;
    $data= $datosObjeto->get_foliovalidacion();

    $valor = $data['data']->Numero + 1;
    $respuesta['Numero']=$valor;
    $respuesta['NumeroAntiguo']=$data['data']->Numero;
    $respuesta['IdFolio']=$data['data']->IdFolio;
    $respuesta['Folio']=$data['data']->Serie.$valor;
    return $respuesta;
}

function get_update_folio($IdFolio,$Numero){
    $CI =& get_instance();
    $CI->load->model("Mfolio");
    $datosObjeto = new Mfolio();
    $datosObjeto->IdFolio = $IdFolio;
    $datosObjeto->Numero = $Numero;
    $valor = $datosObjeto->updateFolio();
    return $valor;
}

function Base64ToPath2($base64,$name,$Id,$Ruta)
{
    //Ruta contampla el id de la empresa IdEmpresa
    //Id es el id de la tabla
    //name nombre de la imagen
	if(!is_dir($Ruta.'/'.$Id))
	{
		mkdir($Ruta.'/'.$Id, 0755); //Directory does not exist, so lets create it.
	}

     //eJEMPLO Ruta:assets/uploads/anomalias/1
     //

	$img = $base64;
	$img = str_replace('data:image/JPG;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = $Ruta.'/'.$Id.'/'.$name. '.png';
	$success = file_put_contents($file, $data);
	//print $success ? $file : 'Unable to save the file.';
}

function Base64ToPathFix($base64,$name,$Ruta)
{
	$img = $base64;
	$img = str_replace('data:image/JPG;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = $Ruta.'/'.$name. '.png';
	$success = file_put_contents($file, $data);

    return $name.'.png';
	//print $success ? $file : 'Unable to save the file.';
}

function CrearRuta($Ruta,$IdEmpresa,$Id='')
{
    //ruta /idempresa/id
    //eJEMPLO:assets/uploads/anomalias/1/23
    //Ruta crear
    if(!is_dir($Ruta.'/'.$IdEmpresa))
    {
    	mkdir($Ruta.'/'.$IdEmpresa, 0755); //Directory does not exist, so lets create it.
    }
    if ($Id!='')
    {
        if(!is_dir($Ruta.'/'.$IdEmpresa.'/'.$Id))
        {
            mkdir($Ruta.'/'.$IdEmpresa.'/'.$Id, 0755); //Directory does not exist, so lets create it.
        }
    }
}

function CrearRutaEmpSuc($Ruta,$IdEmpresa,$IdSucursal='',$Id='')
{
    $rutafinal='';
    //ruta /idempresa/id
    //eJEMPLO:assets/uploads/anomalias/1/23
    //Ruta crear
    if(!is_dir($Ruta.'/'.$IdEmpresa))
    {
    	mkdir($Ruta.'/'.$IdEmpresa, 0755); //Directory does not exist, so lets create it.
        $rutafinal=$Ruta.'/'.$IdEmpresa;
    }

    if ($IdSucursal!='')
    {
        if(!is_dir($Ruta.'/'.$IdEmpresa.'/'.$IdSucursal))
        {
            mkdir($Ruta.'/'.$IdEmpresa.'/'.$IdSucursal, 0755); //Directory does not exist, so lets create it.
        }
        $rutafinal=$Ruta.'/'.$IdEmpresa.'/'.$IdSucursal;
    }
    if ($Id!='')
    {
        if(!is_dir($Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/'.$Id))
        {
        	mkdir($Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/'.$Id, 0755); //Directory does not exist, so lets create it.
        }
        $rutafinal=$Ruta.'/'.$IdEmpresa.'/'.$IdSucursal.'/'.$Id;
    }

    return $rutafinal;
}

function diasRestantes($fecha_final)
{
	if($fecha_final!='')//AAAA-MM-DD
	{
		$fecha_actual = date("Y-m-d");
		$s = strtotime($fecha_final)-strtotime($fecha_actual);
		$d = intval($s/86400);
		$diferencia = $d;
		return $diferencia;
	}
}

function setTimeZone($instancia,$input)
{
    if(!$instancia->validToken($input->request_headers('Authorization'))) {
        return $instancia->set_unauthorized_response();
    }

    $ZonaHoraria = $instancia->getTokenData($input->request_headers('Authorization'))['ZonaHoraria'];

    setlocale(LC_ALL,"es_MX");
	date_default_timezone_set($ZonaHoraria);
	/*setlocale(LC_ALL,"es_MX");
	date_default_timezone_set('America/Mexico_City');*/
}

function setTimeZoneEmpresa($IdSucursal)
{
    $CI =& get_instance();
    $CI->load->model("Mconfiguracion");
    $oMconfiguracion=new Mconfiguracion();
    $oMconfiguracion->IdSucursal=$IdSucursal;
    $oMconfiguracion->RegEstatus='A';
    $response = $oMconfiguracion->get_concepto();

    setlocale(LC_ALL,"es_MX");

    if($response['status']){
        date_default_timezone_set($response['data']->ZonaHoraria);
    }else{
        date_default_timezone_set('America/Mexico_City');
    }
}

function CorreoBienvenida($Cuerpo,$Para,$Asunto)
{
    $config = [
        'protocol'         => 'smtp',
        'smtp_host'     => 'smtp.gmail.com',
        'smtp_user'     => 'actividadescephcis@gmail.com',
        'smtp_pass'     => 'ActUnam18',
        'smtp_crypto'     => 'tls',
        'newline'         => "\r\n",
        'crlf'             => "\r\n",
        'smtp_port'     => 587,
        'charset'         => 'utf-8'
    ];

    $CI =& get_instance();
    $CI->load->library('email',$config);
    $config['mailtype'] = 'html';
    $config['charset'] = 'utf-8';
    $CI->email->initialize($config);
    $subject=$Asunto;

    $message=$Cuerpo;
    /*$message="";
    $subject = "Bienvenido";
    $message .= "Hola: ".$NombreE."gracias por formar parte de happy wallet";
    $message .= "<br>";*/
    $CI->email->from('actividadescephcis@gmail.com', 'CRM');
    $CI->email->reply_to('no-reply@gmail.com');
    $CI->email->to($Para);
    //$CI->email->cc($Copia);
    $CI->email->subject($subject);
    $CI->email->message($message);
    $CI->email->send();
}

function edades($fecha,$tipo=true)
{
    $Fecha = date('Y-m-d', strtotime($fecha));
    $valor='';
    list($anyo,$mes,$dia) = explode("-",$Fecha);
    $anyo_dif  = date("Y") - $anyo;
    $mes_dif = date("m") - $mes;
    $dia_dif   = date("d") - $dia;
    if ($dia_dif < 0 || $mes_dif < 0) $anyo_dif--;

    if ($mes_dif>0)
    {
        if ($tipo==true)
        {
        $valor=$anyo_dif.' Años / '. $mes_dif.' meses';
        }
        else
        {
            $valor=$anyo_dif.' Años';
        }
    }
    else
    {
        $valor=$anyo_dif.' Años';
    }
    return $valor;
}

function  get_tablahtml($header,$detalle){

    $htmlTable='
        <table >
            <thead>
                '.$header.'
            </thead>
            <tbody>'. $detalle .'</tbody>
        </table>    
    ';

    return $htmlTable;
}

function Calcular_Minutos($hora1,$hora2){
    $separar[1]=explode(':',$hora1);
    $separar[2]=explode(':',$hora2);

    $total_minutos_trasncurridos[1] = ($separar[1][0]*60)+$separar[1][1];
    $total_minutos_trasncurridos[2] = ($separar[2][0]*60)+$separar[2][1];
    $total_minutos_trasncurridos = $total_minutos_trasncurridos[1]-$total_minutos_trasncurridos[2];

    if($total_minutos_trasncurridos<=59) return($total_minutos_trasncurridos.'');
    elseif($total_minutos_trasncurridos>59){
        $HORA_TRANSCURRIDA = round($total_minutos_trasncurridos/60);
        if($HORA_TRANSCURRIDA<=9) $HORA_TRANSCURRIDA='0'.$HORA_TRANSCURRIDA;
        $MINUITOS_TRANSCURRIDOS = $total_minutos_trasncurridos%60;
        if($MINUITOS_TRANSCURRIDOS<=9) $MINUITOS_TRANSCURRIDOS='0'.$MINUITOS_TRANSCURRIDOS;
        return ($HORA_TRANSCURRIDA.':'.$MINUITOS_TRANSCURRIDOS.' Horas');

    }
}

function conversorSegundosaHoras($tiempo_en_segundos) {
    $horas = floor($tiempo_en_segundos / 3600);
    $minutos = floor(($tiempo_en_segundos - ($horas * 3600)) / 60);
    $segundos = $tiempo_en_segundos - ($horas * 3600) - ($minutos * 60);

    if ($minutos <10)
    {
        $minutos ='0'.$minutos;
    }

    //return $horas . ':' . $minutos . ":" . $segundos;


    return $horas . ':' . $minutos;
}

function convertirhorasegundo($horamin) {

    $jornal=explode(":",$horamin);
    $horas=(int)$jornal[0];
    $minutos=(int)$jornal[1];

    $HoraMin=$horas*60;
    $MinTotal=$minutos + $HoraMin;

    $SeguntoTotal =$MinTotal*60;

    return $SeguntoTotal;
}

function listaAnios()
{
    $Anio=  date('Y') -2;
    $ArrayLista=array();
    for ($i=0;$i<4;$i++)
    {
        $Anio ++;
        array_push($ArrayLista,$Anio);
    }
    return $ArrayLista;
}
?>
