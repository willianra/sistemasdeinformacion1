<?php 
session_start();
//this is the bitacora

require_once "../modelos/PersonalEmpresa.php";
$personalempresa=new personalEmpresa();//instanciando al modelo categoria
 //reciviendo del formulario si existe estos objetos
$personalsucursalid=isset($_POST["personalsucursalid"])? limpiarCadena($_POST["personalsucursalid"]):"";
$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
		date_default_timezone_set('America/La_Paz');
	    require_once "../modelos/Bitacora.php";
		$bitacora = new Bitacora(); 
 
switch ($_GET["op"]){//operaciones q son enviados desde un js
	case 'guardaryeditar':
		if (empty($personalsucursalid)){

			$rspta=$personalempresa->insertar($idpersona,$idsucursal);
			if($rspta){
				echo "Personal Empresa creado";
				$bitacora->insertar(intval($_SESSION['idusuario']),"Se creo nuevo Personal Empresa :".date('Y-m-d H:i:s'));
			}else{
				echo  "Personal Empresa no se pudo crear";
			}				         
		}else {
			$rspta=$personalempresa->editar($personalsucursalid,$idpersona,$idsucursal );
			if($rspta){
				echo "Personal Empresa editado";
				$bitacora->insertar(intval($_SESSION['idusuario']),"edito un Personal Empresa :".date('Y-m-d H:i:s'));
			}else{
				echo  "Personal Empresa no se pudo editar";
			}			
		}
	break;

	case 'desactivar':
		$rspta=$personalempresa->desactivar($personalsucursalid);
			if($rspta){
				echo "Personal Empresa desactivado";
				$bitacora->insertar(intval($_SESSION['idusuario']),"desactivo un Personal Empresa :".date('Y-m-d H:i:s'));
			}else{
				echo  "Personal Empresa no se pudo desactivar";
			}
 		break;
	break;

	case 'activar':
		$rspta=$personalempresa->activar($personalsucursalid);
			if($rspta){
				echo "Personal Empresa activado";
				$bitacora->insertar(intval($_SESSION['idusuario']),"activo un Personal Empresa :".date('Y-m-d H:i:s'));
			}else{
				echo  "Personal Empresa no se pudo activar";
			}
          //...........................
 		break;
	break;

	case 'mostrar':
		$rspta=$personalempresa->mostrar($personalsucursalid); 
 		echo json_encode($rspta);
 		break;
	break;

	case 'listar':
		$rspta=$personalempresa->listar();
 		//Vamos a declarar un array
 		$data= Array();

 		while ($reg=$rspta->fetch_object()){
 			$data[]=array(
 				//campos del la tabla categoria
 				"0"=>($reg->estado)?'<button class="btn btn-warning" onclick="mostrar('.$reg->personalsucursalid.')">Editar</button>'.
 					' <button class="btn btn-danger" onclick="desactivar('.$reg->personalsucursalid.')">Desactivar</button>':
 					'<button class="btn btn-warning" onclick="mostrar('.$reg->personalsucursalid.')">Editar</button>'.
 					' <button class="btn btn-primary" onclick="activar('.$reg->personalsucursalid.')">Activar</button>',
 				"1"=>$reg->idpersona,
 				"2"=>$reg->idsucursal,
 				"3"=>($reg->estado)?'<span class="label bg-green">Activado</span>':
 				'<span class="label bg-red">Desactivado</span>'
 				);
 		}
 		$results = array(
 			"sEcho"=>1, //Información para el datatables
 			"iTotalRecords"=>count($data), //enviamos el total registros al datatable
 			"iTotalDisplayRecords"=>count($data), //enviamos el total registros a visualizar
 			"aaData"=>$data);
 		echo json_encode($results);//array de resultados

	break;

	case "selectTipo1":
		require_once "../modelos/Persona.php";
		$persona = new Persona();

		$rspta = $persona->select();

		while ($reg = $rspta->fetch_object())
				{
					echo '<option value=' . $reg->personaid . '>' . $reg->personaid . '</option>';
				}
	break;

	case "selectTipo2":
		require_once "../modelos/Almacen.php";
		$planificacion = new Almacen();

		$rspta = $planificacion->select();

		while ($reg = $rspta->fetch_object())
				{
					
					echo '<option value=' . $reg->almacenid . '>'.$reg->almacenid. '</option>';
				}
	break;

}
?>