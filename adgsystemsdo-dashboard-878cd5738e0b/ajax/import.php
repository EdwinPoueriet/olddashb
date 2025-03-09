<?php
namespace App\Legacy;


class Import extends General

{

	function __construct()

	{

		parent::__construct();

		parent::ValidateSession($_SESSION['user_id']);

	}
	

	public function Initialize(){

	echo 					'<tr>
                              <td>
                                <h2>Centros de Costo</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';


                                $this->CostCenters();

	echo 					'</span>  
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="costcenters">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/costcenters_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Almacenes</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Warehouses();

    echo 					'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="warehouses">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/warehouses_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Bancos</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Banks();

    echo 					'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="banks">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/banks_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Vendedores</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Sellers();

    echo 					'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="sellers">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/sellers_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Cobradores</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

								$this->Collectors();

    echo 					'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="collectors">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/collectors_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Zonas</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Areas();

    echo 					'</span>  
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="areas">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/areas_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Rutas</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Routes();

    echo 					'</span>  
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="routes">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/routes_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Países</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Countries();

	echo 					'</span>  
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="countries">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/countries_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Provincias</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Provinces();

	echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="provinces">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/provinces_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Ciudades</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Cities();

    echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="cities">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/cities_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Sectores</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Sectors();

	echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="sectors">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/sectors_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Productos</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Products();

    echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="products">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/products_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Tipos de producto</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->ProductTypes();

	echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="producttypes">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/producttypes_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Marcas</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Brands();

	echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="brands">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/brands_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Clasificaciones</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Classifications();

    echo 						'</span>
                              </td>  
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="classifications">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/classifications_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>                   
                            </tr>
                            <tr>
                              <td>
                                <h2>Subclasificaciones</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Subclassifications();

    echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="subclassifications">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/subclassifications_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <h2>Familias</h2>
                              </td>
                              <td class="text-center"><span class="label label-primary">';

                                $this->Families();

    echo 						'</span>
                              </td>
                              <td class="text-center">
                                <a class="table-link upload" style="cursor:pointer" id="families">
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-upload fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                              <td class="text-center">
                                <a class="table-link" style="cursor:pointer" href="examples/families_examples.csv" download>
                                  <span class="fa-stack">
                                    <i class="fa fa-square fa-stack-2x"></i>
                                    <i class="fa fa-file-excel-o fa-stack-1x fa-inverse"></i>
                                  </span>
                                </a>
                              </td>
                            </tr>';

	}


	public function CostCentersUpload() {

	$file_name = $_FILES["costcenters"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase'])){

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".cost_center 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['costcenters']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$costcenters_array[] = $data; 

				}

				fclose($handle);

			$costcenters_array[0] = ["", "", ""] ;

			$uploaded_costcenters = 0;

			$costcenters_array_total = count($costcenters_array) - 1;

			foreach ($costcenters_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".cost_center 
						(
						company_id,
						cost_center_code,
						cost_center_name
						)
						VALUES 
						(
						:company_id,
						:cost_center_code,
						:cost_center_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':cost_center_code', $column[1]);
					
					$import_sql->bindParam(':cost_center_name', $column[2]);
					
					$import_sql->execute();

					$uploaded_costcenters = $uploaded_costcenters + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {

					$not_uploaded_costcenters_array[] = $column;

				} 

			}

			if ($uploaded_costcenters)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_costcenters . ' de ' . $costcenters_array_total . ' registros
				  </div>';
				}


			if (count($not_uploaded_costcenters_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-check-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										cost_center_code
										</div>
										<div class="col-md-4">
										cost_center_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_costcenters_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		}  else {

			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function WarehousesUpload() {

	$file_name = $_FILES["warehouses"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".warehouses 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['warehouses']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$warehouses_array[] = $data; 

				}

				fclose($handle);

			$warehouses_array[0] = ["", "", ""] ;

			$uploaded_warehouses = 0;

			$warehouses_array_total = count($warehouses_array) - 1;

			foreach ($warehouses_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".warehouses 
						(
						company_id,
						warehouse_code,
						warehouse_name
						)
						VALUES 
						(
						:company_id,
						:warehouse_code,
						:warehouse_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':warehouse_code', $column[1]);
					
					$import_sql->bindParam(':warehouse_name', $column[2]);
					
					$import_sql->execute();

					$uploaded_warehouses = $uploaded_warehouses + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_warehouses_array[] = $column;

				} 

			}

			if ($uploaded_warehouses > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_warehouses . ' de ' . $warehouses_array_total . ' registros
				  </div>';
				}

			if (count($not_uploaded_warehouses_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										warehouse_code
										</div>
										<div class="col-md-4">
										warehouse_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_warehouses_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';


				}

		} else {

			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function BanksUpload() {

	$file_name = $_FILES["banks"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{


			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".banks 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['banks']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$banks_array[] = $data; 

				}

				fclose($handle);

			$banks_array[0] = ["", "", ""] ;

			$uploaded_banks = 0;

			$banks_array_total = count($banks_array) - 1;

			foreach ($banks_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".banks 
						(
						company_id,
						bank_code,
						bank_name
						)
						VALUES 
						(
						:company_id,
						:bank_code,
						:bank_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':bank_code', $column[1]);
					
					$import_sql->bindParam(':bank_name', $column[2]);
					
					$import_sql->execute();

					$uploaded_banks = $uploaded_banks + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_banks_array[] = $column;

				} 

			}

			if ($uploaded_banks > 0)
				
				{

					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_banks . ' de ' . $banks_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_banks_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-check-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										bank_code
										</div>
										<div class="col-md-4">
										bank_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_banks_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';


				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function SellersUpload() {

	$file_name = $_FILES["sellers"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".sellers 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['sellers']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$sellers_array[] = $data; 

				}

				fclose($handle);

			$sellers_array[0] = ["", "", ""] ;

			$uploaded_sellers = 0;

			$sellers_array_total = count($sellers_array) - 1;

			foreach ($sellers_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					if ($column[4] == "")
						$seller_price = 0;
					else 
						$seller_price = $column[4];


					if ($column[5] == "")
						$seller_discount_percent = 0.00;
					else 
						$seller_discount_percent = $column[5];

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".sellers 
						(
						company_id, 
						seller_code, 
						seller_name, 
						seller_phone, 
						seller_price, 
						seller_discount_percent, 
						warehouse_code
						)
						VALUES 
						(
						:company_id, 
						:seller_code, 
						:seller_name, 
						:seller_phone, 
						:seller_price, 
						:seller_discount_percent, 
						:warehouse_code
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':seller_code', $column[1]);
					
					$import_sql->bindParam(':seller_name', $column[2]);
					
					$import_sql->bindParam(':seller_phone', $column[3]);

					$import_sql->bindParam(':seller_price', $seller_price);

					$import_sql->bindParam(':seller_discount_percent', $seller_discount_percent);

					$import_sql->bindParam(':warehouse_code', $column[6]);

					$import_sql->execute();

					$uploaded_sellers = $uploaded_sellers + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_sellers_array[] = $column;

				} 

			}

			if ($uploaded_sellers > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_sellers . ' de ' . $sellers_array_total . ' registros
				  </div>';
				}

			if (count($not_uploaded_sellers_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-check-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										seller_code
										</div>
										<div class="col-md-4">
										seller_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_sellers_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}



	}

	public function CollectorsUpload() {

	$file_name = $_FILES["collectors"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".collectors 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['collectors']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$collectors_array[] = $data; 

				}

				fclose($handle);

			$collectors_array[0] = ["", "", ""] ;

			$uploaded_collectors = 0;

			$collectors_array_total = count($collectors_array) - 1;

			foreach ($collectors_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".collectors 
						(
						company_id,
						collector_code,
						collector_name
						)
						VALUES 
						(
						:company_id,
						:collector_code,
						:collector_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':collector_code', $column[1]);
					
					$import_sql->bindParam(':collector_name', $column[2]);
					
					$import_sql->execute();

					$uploaded_collectors = $uploaded_collectors + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_collectors_array[] = $column;

				} 

			}

			if ($uploaded_collectors > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_collectors . ' de ' . $collectors_array_total . ' registros
				  </div>';
				}

			if (count($not_uploaded_collectors_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-check-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										collector_code
										</div>
										<div class="col-md-4">
										collector_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_collectors_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';


				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function AreasUpload() {

	$file_name = $_FILES["areas"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".areas 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['areas']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$areas_array[] = $data; 

				}

				fclose($handle);

			$areas_array[0] = ["", "", ""] ;

			$uploaded_areas = 0;

			$areas_array_total = count($areas_array) - 1;

			foreach ($areas_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "") && ($column[3] != "")) 
	
				{

					if ($column[4] == "")
						$day_id = 0;
					else 
						$day_id = $column[4];

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".areas 
						(
						company_id,
						area_code,
						area_name,
						seller_code,
						day_id
						)
						VALUES 
						(
						:company_id,
						:area_code,
						:area_name,
						:seller_code,
						:day_id
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':area_code', $column[1]);
					
					$import_sql->bindParam(':area_name', $column[2]);

					$import_sql->bindParam(':seller_code', $column[3]);
					
					$import_sql->bindParam(':day_id', $day_id);
					
					$import_sql->execute();

					$uploaded_areas = $uploaded_areas + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_areas_array[] = $column;

				} 

			}

			if ($uploaded_areas > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_areas . ' de ' . $areas_array_total . ' registros
				  </div>';
				}

			if (count($not_uploaded_areas_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-2">
										company_id 
										</div>
										<div class="col-md-2">
										area_code
										</div>
										<div class="col-md-4">
										area_name
										</div>
										<div class="col-md-2">
										seller_code
										</div>
										<div class="col-md-2">
										day_id
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_areas_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-2">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-2">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-4">
										' . @$column[2] . '
										</div>
										<div class="col-md-2">
										' . @$column[3] . ' 
										</div>
										<div class="col-md-2">
										' . @$column[4] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';


				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function RoutesUpload() {

	$file_name = $_FILES["routes"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['routes']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".sellers 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['routes']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$routes_array[] = $data; 

				}

				fclose($handle);

			$routes_array[0] = ["", "", ""] ;

			$uploaded_routes = 0;

			$routes_array_total = count($routes_array) - 1;

			foreach ($routes_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "") && ($column[3] != "")) 
	
				{

					if ($column[4] == "")
						$route_order = 0;
					else 
						$route_order = $column[4];

					if ($column[5] == "")
						$day_id = 0;
					else 
						$day_id = $column[5];

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".routes 
						(
						company_id,
						route_code,
						route_name,
						seller_code,
						route_order,
						day_id
						)
						VALUES 
						(
						:company_id,
						:route_code,
						:route_name,
						:seller_code,
						:route_order,
						:day_id
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
					
					$import_sql->bindParam(':route_code', $column[1]);
					
					$import_sql->bindParam(':route_name', $column[2]);

					$import_sql->bindParam(':seller_code', $column[3]);

					$import_sql->bindParam(':route_order', $route_order);
					
					$import_sql->bindParam(':day_id', $day_id);
					
					$import_sql->execute();

					$uploaded_routes = $uploaded_routes + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_routes_array[] = $column;

				} 

			}

			if ($uploaded_routes > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_routes . ' de ' . $routes_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_routes_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-3">
										company_id 
										</div>
										<div class="col-md-3">
										route_code
										</div>
										<div class="col-md-3">
										route_name
										</div>
										<div class="col-md-3">
										seller_code
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_routes_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-3">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-3">
										' . @$column[1] . ' 
										</div>
										<div class="col-md-3">
										' . @$column[2] . '
										</div>
										<div class="col-md-3">
										' . @$column[3] . '
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function CountriesUpload() {

	$file_name = $_FILES["countries"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".countries 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['countries']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$countries_array[] = $data; 

				}

				fclose($handle);

			$countries_array[0] = ["", "", ""] ;

			$uploaded_countries = 0;

			$countries_array_total = count($countries_array) - 1;

			foreach ($countries_array as $column) {

				if (($column[0] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".countries 
						(
						country_name
						)
						VALUES 
						(
						:country_name
						)
						");

					$import_sql->bindParam(':country_name', $column[0]);
								
					$import_sql->execute();

					$uploaded_countries = $uploaded_countries + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_countries_array[] = $column;

				} 

			}

			if ($uploaded_countries > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_countries . ' de ' . $countries_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_countries_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-3">
										city_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_countries_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-3">
										'. @$column[0] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function ProvincesUpload() {

	$file_name = $_FILES["provinces"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".provinces 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['provinces']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$provinces_array[] = $data; 

				}

				fclose($handle);

			$provinces_array[0] = ["", "", ""] ;

			$uploaded_provinces = 0;

			$provinces_array_total = count($provinces_array) - 1;

			foreach ($provinces_array as $column) {

				if (($column[0] != "") && ($column[1] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".provinces 
						(
						country_id,
						province_name
						)
						VALUES 
						(
						:country_id,
						:province_name
						)
						");

					$import_sql->bindParam(':country_id', $column[0]);
								
					$import_sql->bindParam(':province_name', $column[1]);

					$import_sql->execute();

					$uploaded_provinces = $uploaded_provinces + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_provinces_array[] = $column;

				} 

			}

			if ($uploaded_provinces > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_provinces . ' de ' . $provinces_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_provinces_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-6">
										country_id 
										</div>
										<div class="col-md-6">
										province_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_provinces_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-6">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-6">
										'. @$column[1] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function CitiesUpload() {

	$file_name = $_FILES["cities"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".cities 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['cities']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$cities_array[] = $data; 

				}

				fclose($handle);

			$cities_array[0] = ["", "", ""] ;

			$uploaded_cities = 0;

			$cities_array_total = count($cities_array) - 1;

			foreach ($cities_array as $column) {

				if (($column[0] != "") && ($column[1] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".cities 
						(
						province_id,
						city_name
						)
						VALUES 
						(
						:province_id,
						:city_name
						)
						");

					$import_sql->bindParam(':province_id', $column[0]);
								
					$import_sql->bindParam(':city_name', $column[1]);

					$import_sql->execute();

					$uploaded_cities = $uploaded_cities + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {

					$not_uploaded_cities_array[] = $column;

				} 

			}

			if ($uploaded_cities > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_cities . ' de ' . $cities_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_cities_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-6">
										province_id 
										</div>
										<div class="col-md-6">
										city_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_cities_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-6">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-6">
										'. @$column[1] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function SectorsUpload() {

	$file_name = $_FILES["sectors"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".sectors 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['sectors']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$sectors_array[] = $data; 

				}

				fclose($handle);

			$sectors_array[0] = ["", "", ""] ;

			$uploaded_sectors = 0;

			$sectors_array_total = count($sectors_array) - 1;

			foreach ($sectors_array as $column) {

				if (($column[0] != "") && ($column[1] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".sectors 
						(
						city_id,
						sector_name
						)
						VALUES 
						(
						:city_id,
						:sector_name
						)
						");

					$import_sql->bindParam(':city_id', $column[0]);
								
					$import_sql->bindParam(':sector_name', $column[1]);

					$import_sql->execute();

					$uploaded_sectors = $uploaded_sectors + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {

					$not_uploaded_sectors_array[] = $column;

				} 

			}

			if ($uploaded_sectors > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_sectors . ' de ' . $sectors_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_sectors_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-6">
										city_id 
										</div>
										<div class="col-md-6">
										sector_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_sectors_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-6">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-6">
										'. @$column[1] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function ProductsUpload() {

	$file_name = $_FILES["products"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

				if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".products 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['products']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$products_array[] = $data; 

				}

				fclose($handle);

			$products_array[0] = ["", "", ""] ;

			$uploaded_products = 0;

			$products_array_total = count($products_array) - 1;

			foreach ($products_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "") && ($column[3] != "") && ($column[4] != "") && ($column[5] != "") && ($column[6] != "") && ($column[7] != "") && ($column[8] != "") && ($column[9] != "") && ($column[10] != "") && ($column[11] != "") && ($column[12] != "") && ($column[13] != "") && ($column[14] != "") && ($column[15] != "") && ($column[16] != "") && ($column[17] != "") && ($column[18] != "") && ($column[19] != "") && ($column[20] != "") && ($column[22] != "")) 
	
				{

					if ($column[21] == NULL)
						$product_accepts_discount = "";
					else 
						$product_accepts_discount = $column[21];

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".products 
						(
						company_id, 
						product_code, 
						product_reference, 
						product_name, 
						brand_id, 
						unit_id, 
						classification_code, 
						subclassification_code, 
						product_pays_tax, 
						product_tax_percent, 
						product_in_stock, 
						product_last_update_date, 
						product_packaging, 
						product_first_price, 
						product_second_price, 
						product_third_price, 
						product_fourth_price,
						product_type_code,
						family_code, 
						group_code,
						product_offer,
						product_accepts_discount,
						product_format 
						)
						VALUES 
						(
						:company_id, 
						:product_code, 
						:product_reference, 
						:product_name, 
						:brand_id, 
						:unit_id, 
						:classification_code, 
						:subclassification_code, 
						:product_pays_tax, 
						:product_tax_percent, 
						:product_in_stock, 
						:product_last_update_date, 
						:product_packaging, 
						:product_first_price, 
						:product_second_price, 
						:product_third_price, 
						:product_fourth_price,
						:product_type_code,
						:family_code, 
						:group_code,
						:product_offer,
						:product_accepts_discount,
						:product_format
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
								
					$import_sql->bindParam(':product_code', $column[1]);

					$import_sql->bindParam(':product_reference', $column[2]);
								
					$import_sql->bindParam(':product_name', $column[3]);

					$import_sql->bindParam(':brand_id', $column[4]);

					$import_sql->bindParam(':unit_id', $column[5]);
								
					$import_sql->bindParam(':classification_code', $column[6]);

					$import_sql->bindParam(':subclassification_code', $column[7]);

					$import_sql->bindParam(':product_pays_tax', $column[8]);
								
					$import_sql->bindParam(':product_tax_percent', $column[9]);

					$import_sql->bindParam(':product_in_stock', $column[10]);

					$import_sql->bindParam(':product_last_update_date', $column[11]);
								
					$import_sql->bindParam(':product_packaging', $column[12]);

					$import_sql->bindParam(':product_first_price', $column[13]);

					$import_sql->bindParam(':product_second_price', $column[14]);

					$import_sql->bindParam(':product_third_price', $column[15]);
								
					$import_sql->bindParam(':product_fourth_price', $column[16]);

					$import_sql->bindParam(':product_type_code', $column[17]);

					$import_sql->bindParam(':family_code', $column[18]);
								
					$import_sql->bindParam(':group_code', $column[19]);

					$import_sql->bindParam(':product_offer', $column[20]);

					$import_sql->bindParam(':product_accepts_discount', $product_accepts_discount);

					$import_sql->bindParam(':product_format', $column[22]);

					$import_sql->execute();

					$uploaded_products = $uploaded_products + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {

					$not_uploaded_products_array[] = $column;

				} 

			}

			if ($uploaded_products > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_products . ' de ' . $products_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_products_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-1">
										company_id 
										</div>
										<div class="col-md-1">
										product_code 
										</div>
										<div class="col-md-1">
										product_reference 
										</div>
										<div class="col-md-1">
										product_name 
										</div>
										<div class="col-md-1">
										brand_id 
										</div>
										<div class="col-md-1">
										unit_id 
										</div>
										<div class="col-md-1">
										classification_code 
										</div>
										<div class="col-md-1">
										subclassification_code 
										</div>
										<div class="col-md-1">
										product_pays_tax 
										</div>
										<div class="col-md-1">
										product_tax_percent 
										</div>
										<div class="col-md-1">
										product_in_stock 
										</div>
										<div class="col-md-1">
										product_last_update_date 
										</div>
										<div class="col-md-1">
										product_packaging 
										</div>
										<div class="col-md-1">
										product_first_price 
										</div>
										<div class="col-md-1">
										product_second_price 
										</div>
										<div class="col-md-1">
										product_third_price 
										</div>
										<div class="col-md-1">
										product_fourth_price 
										</div>
										<div class="col-md-1">
										product_type_code 
										</div>
										<div class="col-md-1">
										family_code 
										</div>
										<div class="col-md-1">
										group_code 
										</div>
										<div class="col-md-1">
										product_offer 
										</div>
										<div class="col-md-1">
										product_format 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_products_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-1">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[1] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[2] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[3] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[5] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[6] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[7] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[8] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[9] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[10] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[12] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[13] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[14] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[15] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[16] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[17] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[18] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[19] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[20] . ' 
										</div>
										<div class="col-md-1">
										'. @$column[22] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function ProductTypesUpload() {

	$file_name = $_FILES["producttypes"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".product_types 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['producttypes']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$producttypes_array[] = $data; 

				}

				fclose($handle);

			$producttypes_array[0] = ["", "", ""] ;

			$uploaded_producttypes = 0;

			$producttypes_array_total = count($producttypes_array) - 1;

			foreach ($producttypes_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".product_types 
						(
						company_id,
						product_type_code,
						product_type_name
						)
						VALUES 
						(
						:company_id,
						:product_type_code,
						:product_type_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
								
					$import_sql->bindParam(':product_type_code', $column[1]);

					$import_sql->bindParam(':product_type_name', $column[2]);

					$import_sql->execute();

					$uploaded_producttypes = $uploaded_producttypes + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {

					$not_uploaded_producttypes_array[] = $column;

				} 

			}

			if ($uploaded_producttypes > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_producttypes . ' de ' . $producttypes_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_producttypes_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										product_type_code 
										</div>
										<div class="col-md-4">
										product_type_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_producttypes_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[1] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[2] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function BrandsUpload() {

	$file_name = $_FILES["brands"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".brands 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['brands']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$brands_array[] = $data; 

				}

				fclose($handle);

			$brands_array[0] = ["", "", ""] ;

			$uploaded_brands = 0;

			$brands_array_total = count($brands_array) - 1;

			foreach ($brands_array as $column) {

				if (($column[0] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".brands 
						(
						brand_name
						)
						VALUES 
						(
						:brand_name
						)
						");

					$import_sql->bindParam(':brand_name', $column[0]);
								
					$import_sql->execute();

					$uploaded_brands = $uploaded_brands + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_brands_array[] = $column;

				} 

			}

			if ($uploaded_brands > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_brands . ' de ' . $brands_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_brands_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-3">
										brand_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_brands_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-3">
										'. @$column[0] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function ClassificationsUpload() {

	$file_name = $_FILES["classifications"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".classifications 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['classifications']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$classifications_array[] = $data; 

				}

				fclose($handle);

			$classifications_array[0] = ["", "", ""] ;

			$uploaded_classifications = 0;

			$classifications_array_total = count($classifications_array) - 1;

			foreach ($classifications_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".classifications 
						(
						company_id,
						classification_code,
						classification_name
						)
						VALUES 
						(
						:company_id,
						:classification_code,
						:classification_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);

					$import_sql->bindParam(':classification_code', $column[1]);

					$import_sql->bindParam(':classification_name', $column[2]);
								
					$import_sql->execute();

					$uploaded_classifications = $uploaded_classifications + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_classifications_array[] = $column;

				} 

			}

			if ($uploaded_classifications > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_classifications . ' de ' . $classifications_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_classifications_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										classification_code 
										</div>
										<div class="col-md-4">
										classification_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_classifications_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[1] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[2] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}

	public function SubclassificationsUpload() {

	$file_name = $_FILES["subclassifications"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".subclassifications 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['subclassifications']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$subclassifications_array[] = $data; 

				}

				fclose($handle);

			$subclassifications_array[0] = ["", "", ""] ;

			$uploaded_subclassifications = 0;

			$subclassifications_array_total = count($subclassifications_array) - 1;

			foreach ($subclassifications_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "") && ($column[3] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".subclassifications 
						(
						company_id,
						subclassification_code,
						classification_code,
						subclassification_name
						)
						VALUES 
						(
						:company_id,
						:subclassification_code,
						:classification_code,
						:subclassification_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);

					$import_sql->bindParam(':classification_code', $column[1]);

					$import_sql->bindParam(':subclassification_code', $column[2]);

					$import_sql->bindParam(':subclassification_name', $column[3]);
								
					$import_sql->execute();

					$uploaded_subclassifications = $uploaded_subclassifications + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_subclassifications_array[] = $column;

				} 

			}

			if ($uploaded_subclassifications > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_subclassifications . ' de ' . $subclassifications_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_subclassifications_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-3">
										company_id 
										</div>
										<div class="col-md-3">
										subclassification_code 
										</div>
										<div class="col-md-3">
										classification_code 
										</div>
										<div class="col-md-3">
										subclassification_name
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_subclassifications_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-3">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-3">
										'. @$column[1] . ' 
										</div>
										<div class="col-md-3">
										'. @$column[2] . ' 
										</div>
										<div class="col-md-3">
										'. @$column[3] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}


	}

	public function FamiliesUpload() {

	$file_name = $_FILES["families"]["name"];

	$file_check = explode(".", $file_name);

	if (strtolower(end($file_check)) == "csv")

		{

			if (isset($_POST['erase']))

			{

				try {

				$delete_sql = $this->con->prepare("DELETE
					FROM ".$this->user_database.".product_families 
				");
				
				$delete_sql->execute();

			} catch (\Exception $e) {

				throw new \Exception("Error procesando la solicitud.", 1);
									
				}

			}

			$file_temp_name = $_FILES['families']['tmp_name'];

			$handle = fopen($file_temp_name, "r");

			while (($data = fgetcsv($handle, 10000, ",")) !== FALSE)

				{

					$families_array[] = $data; 

				}

				fclose($handle);

			$families_array[0] = ["", "", ""] ;

			$uploaded_families = 0;

			$families_array_total = count($families_array) - 1;

			foreach ($families_array as $column) {

				if (($column[0] != "") && ($column[1] != "") && ($column[2] != "")) 
	
				{

					try {

					$import_sql = $this->con->prepare("INSERT INTO
						".$this->user_database.".product_families 
						(
						company_id,
						family_code,
						family_name
						)
						VALUES 
						(
						:company_id,
						:family_code,
						:family_name
						)
						");

					$import_sql->bindParam(':company_id', $column[0]);
								
					$import_sql->bindParam(':family_code', $column[1]);

					$import_sql->bindParam(':family_name', $column[2]);

					$import_sql->execute();

					$uploaded_families = $uploaded_families + 1;

				} catch (\Exception $e) {

					throw new \Exception("Error procesando la solicitud.", 1);
										
					}

				} else {


					$not_uploaded_families_array[] = $column;

				} 

			}

			if ($uploaded_families > 0)

				{
					echo '<div class="alert alert-success">
						<i class="fa fa-check-circle fa-fw fa-lg"></i>
						<strong>Excelente!</strong> Se subieron ' . $uploaded_families . ' de ' . $families_array_total . ' registros
				  </div>';

				}

			if (count($not_uploaded_families_array) > 1)

				{
					echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Las siguientes filas contienen campos vacíos:</strong>
						  <div class="row text-center">
									  <strong>
										<div class="col-md-4">
										company_id 
										</div>
										<div class="col-md-4">
										family_code 
										</div>
										<div class="col-md-4">
										family_name 
										</div>
									  </strong>
						  </div>';
					
						foreach ($not_uploaded_provinces_array as $column) 

						{
							
							echo '<div class="row text-center">
									  <strong>
										<div class="col-md-4">
										'. @$column[0] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[1] . ' 
										</div>
										<div class="col-md-4">
										'. @$column[2] . ' 
										</div>
									  </strong>
								  </div>';

						}

					echo '</div>';

				}

		} else {
			
			echo '<div class="alert alert-danger">
							<i class="fa fa-times-circle fa-fw fa-lg"></i>
								<strong>Error!</strong> El archivo no es CSV.
				  </div>';

		}

	}
}

if (isset($_POST['upload_type'])) {

	$import = new Import;

	if ($_POST['upload_type'] == "initialize")
			$import->Initialize();

	if ($_POST['upload_type'] == "costcenters")
			$import->CostCentersUpload();

	if ($_POST['upload_type'] == "warehouses")
			$import->WarehousesUpload();
		
	if ($_POST['upload_type'] == "banks")
			$import->BanksUpload();

	if ($_POST['upload_type'] == "sellers")
			$import->SellersUpload();

	if ($_POST['upload_type'] == "collectors")
			$import->CollectorsUpload();

	if ($_POST['upload_type'] == "areas")
			$import->AreasUpload();

	if ($_POST['upload_type'] == "routes")
			$import->RoutesUpload();

	if ($_POST['upload_type'] == "countries")
			$import->CountriesUpload();

	if ($_POST['upload_type'] == "provinces")
			$import->ProvincesUpload();

	if ($_POST['upload_type'] == "cities")
			$import->CitiesUpload();

	if ($_POST['upload_type'] == "sectors")
			$import->SectorsUpload();

	if ($_POST['upload_type'] == "products")
			$import->ProductsUpload();

	if ($_POST['upload_type'] == "producttypes")
			$import->ProductTypesUpload();

	if ($_POST['upload_type'] == "brands")
			$import->BrandsUpload();

	if ($_POST['upload_type'] == "classifications")
			$import->ClassificationsUpload();

	if ($_POST['upload_type'] == "subclassifications")
			$import->SubclassificationsUpload();

	if ($_POST['upload_type'] == "families")
			$import->FamiliesUpload();

} 


?>

