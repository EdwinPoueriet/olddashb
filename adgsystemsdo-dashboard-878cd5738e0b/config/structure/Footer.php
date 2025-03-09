<?php

namespace App\Legacy;

class Footer extends General 
{
	
	function __construct()

	{

		parent::__construct();

		parent::ValidateSession();

	}

    static  function Initialize()
	{
		echo '<footer id="footer-bar" class="row">
								<p id="footer-copyright" class="col-xs-12">
									ADGSystems
								</p>
							</footer>

						</div>
					</div>
				</div>
				<div id="config-tool" class="closed">
				<a id="config-tool-cog">
					<i class="fa fa-cog"></i>
				</a>
				<div id="config-tool-options">
					<h4>Color de plantilla</h4>
					<ul id="skin-colors" class="clearfix">
						<li>
							<a class="skin-changer" data-skin="" data-toggle="tooltip" title="Por defecto" style="background-color: #34495e;">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-white" data-toggle="tooltip" title="Blanco/Verde" style="background-color: #2ecc71;">
							</a>
						</li>
						<li>
							<a class="skin-changer blue-gradient" data-skin="theme-blue-gradient" data-toggle="tooltip" title="Degradado">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-turquoise" data-toggle="tooltip" title="Verde" style="background-color: #1abc9c;">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-amethyst" data-toggle="tooltip" title="Violeta" style="background-color: #9b59b6;">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-blue" data-toggle="tooltip" title="Azul" style="background-color: #2980b9;">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-red" data-toggle="tooltip" title="Rojo" style="background-color: #e74c3c;">
							</a>
						</li>
						<li>
							<a class="skin-changer" data-skin="theme-whbl" data-toggle="tooltip" title="Blanco/Azul" style="background-color: #3498db;">
							</a>
						</li>
					</ul>
				</div>
				</div>';
	}

	function Modal($startdate, $enddate, $filters)
	
	{

	echo '<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													
													<div class="modal-dialog">
														
														<div class="modal-content">

															<form role="form" id="filters" method="post" action="" name="filters">

																<div class="modal-header">
																	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
																	<h4 class="modal-title">Filtros</h4>
																</div>
																<div class="modal-body">
																	

																	<input type="hidden" id="startrange" name="startrange" value="'.$startdate.'">

																	<input type="hidden" id="endrange" name="endrange" value="'.$enddate.'">';

                                                                     if ($filters['dayfilter'] == 1)
                                                                         $this->FilterDay();
																	if ($filters['datepicker'] == 1)
																		$this->DatePicker($startdate);

																	if ($filters["sellers"] == 1)
																		$this->FilterSellers();

																	if ($filters["collectors"] == 1)
																		$this->FilterCollectors();

																	if ($filters['products'] == 1)
																		$this->FilterProducts(); 

																	if ($filters['producttypes'] == 1)
																		$this->FilterProductTypes(); 

																	if ($filters['routes'] == 1)
																		$this->FilterRoutes(); 

																	if ($filters['areas'] == 1)
																		$this->FilterAreas(); 

																	if ($filters['brands'] == 1)
																		$this->FilterBrands();

																	if ($filters['classifications'] == 1)
																		$this->FilterClassifications();

																	if ($filters['subclassifications'] == 1)
																		$this->FilterSubclassifications();

																	if ($filters['families'] ==1)
																		$this->FilterFamilies();

																	if ($filters['warehouses'] == 1)
																		$this->FilterWarehouses(); 

																	if ($filters['costcenters'] == 1)
																		$this->FilterCostcenters();

																	if ($filters['excludeseller'] == 1)
																		$this->ExcludeSeller();

																	if ($filters['didntbuy'] == 1)
																		$this->DidntBuy();

																	echo '
																</div>
																	<div class="modal-footer">
																		<button type="submit" class="btn btn-primary">Aceptar</button>
																	</div>
																</form>
															</div>
														</div>
													</div>';
}


    function FilterDay()

    {

        echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="day">Días de Visita</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
						<select style="width: 100%" class="sel2Multi" id="day" multiple name="days[]">
						<option value="all"';

        if (isset($_POST["days"]))

            if (in_array("all", $_POST["days"], true))
                echo 'selected';

        echo'>Todos</option>';

        foreach (json_decode($this->GetDays()) as $row)

        {

            echo '<option value="'.$row->day_id.'"';

            if (isset($_POST["days"]))
                if (in_array($row->day_id, $_POST["days"], true))
                    echo 'selected';

            echo'>'.$row->day_name.'</option>';

        }

        echo	'</select>
						</div>
					</div>
				</div>
				';
    }


	function FilterSellers()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="sellers">Vendedores</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<select style="width: 100%" class="sel2Multi" id="sellers" multiple name="sellers[]">
						<option value="all"';
								
								if (isset($_POST["sellers"]))  

								if (in_array("all", $_POST["sellers"], true))
									echo 'selected';
						
								echo'>Todos</option>';

								foreach (json_decode($this->GetSellers()) as $row) 

								{

									echo '<option value="'.$row->seller_code.'"';
									
									if (isset($_POST["sellers"]))  

									if (in_array($row->seller_code, $_POST["sellers"], true))
										echo 'selected';
							
									echo'>'.$row->seller_code.' - '.$row->seller_name.'</option>';
																						
								} 

				echo	'</select>
						</div>
					</div>
				</div>
				';
	}

	function FilterCollectors()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="collectors">Cobradores</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-user"></i></span>
						<select style="width: 100%" class="sel2Multi" id="collectors" multiple name="collectors[]">
						';
							foreach (json_decode($this->GetCollectors()) as $row) 

							{

								echo '<option value="'.trim($row->collector_code).'"';
								
								if (isset($_POST["collectors"]))  

								if (in_array(trim($row->collector_code), 
								$_POST["collectors"], true))
									echo 'selected';
						
								echo'>'.$row->collector_code.' - '.$row->collector_name.'</option>';
																					
							} 



				echo	'</select>
						</div>
					</div>
				</div>
				';
	}

	function FilterProducts()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="products">Productos</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-cube"></i></span>
						<select style="width: 100%" class="sel2Multi" id="products" multiple name="products[]">
						<option value="all"';
								
								if (isset($_POST["products"]))  

								if (in_array("all", $_POST["products"], true))
									echo 'selected';
						
								echo'>Todos</option>';

							foreach (json_decode($this->GetProducts()) as $row) 

							{

								echo '<option value="'.$row->product_reference.'"';
								
								if (isset($_POST["products"]))  

								if (in_array($row->product_reference, $_POST["products"], true))
									echo 'selected';
						
								echo'>'.$row->product_reference.' - '.$row->product_name.'</option>';
																					
							} 



				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterProductTypes()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="producttypes">Tipos de producto</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-cubes"></i></span>
						<select style="width: 100%" class="sel2Multi" id="producttypes" multiple name="producttypes[]">';

							foreach (json_decode($this->GetProductTypes()) as $row) 

							{

								echo '<option value="'.$row->product_type_code.'"';
								
								if (isset($_POST["producttypes"]))  

								if (in_array($row->product_type_code, $_POST["producttypes"], true))
									echo 'selected';
						
								echo'>'.$row->product_type_code.' - '.$row->product_type_name.'</option>';
																					
							} 



				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterRoutes()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="routes">Rutas</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-car"></i></span>
						<select style="width: 100%" class="sel2Multi" id="routes" multiple name="routes[]">';

							foreach (json_decode($this->GetRoutes()) as $row) 

							{

								echo '<option value="'.$row->route_code.'"';
								
								if (isset($_POST["routes"]))  

								if (in_array($row->route_code, $_POST["routes"], true))
									echo 'selected';
						
								echo'>'.$row->route_code.' - '.$row->route_name.'</option>';
																					
							} 



				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterAreas()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="areas">Zonas</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-map-marker"></i></span>
						<select style="width: 100%" class="sel2Multi" id="areas" multiple name="areas[]">';

							foreach (json_decode($this->GetAreas()) as $row) 

							{

								echo '<option value="'.$row->area_code.'"';
								
								if (isset($_POST["areas"]))  

								if (in_array($row->area_code, $_POST["areas"], true))
									echo 'selected';
						
								echo'>'.$row->area_code.' - '.$row->area_name.'</option>';
																					
							} 

				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterBrands()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="brands">Marcas</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-tag"></i></span>
						<select style="width: 100%" class="sel2Multi" id="brands" multiple name="brands[]">';

							foreach (json_decode($this->GetBrands()) as $row) 

							{

								echo '<option value="'.$row->brand_id.'"';
								
								if (isset($_POST["brands"]))  

								if (in_array($row->brand_id, $_POST["brands"], true))
									echo 'selected';
						
								echo'>'.$row->brand_id.' - '.$row->brand_name.'</option>';
																					
							} 

				echo	'</select>
						</div>
					</div>
				</div>
				';
	
	}

	function FilterClassifications()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="classifications">Clasificaciones</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa fa-sort-amount-asc"></i></span>
						<select style="width: 100%" class="sel2Multi" id="classifications" multiple name="classifications[]">';

							foreach (json_decode($this->GetClassifications()) as $row) 

							{

								echo '<option value="'.$row->classification_code.'"';
								
								if (isset($_POST["classifications"]))  

								if (in_array($row->classification_code, $_POST["classifications"], true))
									echo 'selected';
						
								echo'>'.$row->classification_code.' - '.$row->classification_name.'</option>';
																					
							} 

				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterSubclassifications()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="subclassifications">Subclasificaciones</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa fa-sort-amount-desc"></i></span>
						<select style="width: 100%" class="sel2Multi" id="subclassifications" multiple name="subclassifications[]">';

							foreach (json_decode($this->GetClassifications()) as $classrow) 

							{

								echo '<optgroup label="'.$classrow->classification_name. ' - ' .$classrow->classification_name.'">';

								foreach (json_decode($this->GetSubclassifications($classrow->classification_code)) as $row) 

									{
									
										echo '<option value="'.$row->subclassification_code.'"';
									
										if (isset($_POST["subclassifications"]))  

										if (in_array($row->subclassification_code, $_POST["subclassifications"], true))
										echo 'selected';
							
										echo'>'.$row->subclassification_code.' - '.$row->subclassification_name.'</option>';

									}

								echo '</optgroup>';
																					
							} 

				echo	'</select>
						</div>
					</div>
				</div>

				';
	
	}

	function FilterFamilies()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="families">Familias</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-tags"></i></span>
						<select style="width: 100%" class="sel2Multi" id="families" multiple name="families[]">';

								foreach (json_decode($this->GetProductFamilies()) as $row) 

									{
									
										echo '<option value="'.$row->family_code.'"';
									
										if (isset($_POST["families"]))  

										if (in_array($row->family_code, $_POST["families"], true))
										echo 'selected';
							
										echo'>'.$row->family_code.' - '.$row->family_name.'</option>';

									}

				echo	'</select>
						</div>
					</div>
				</div>

				';


	}

	function FilterWarehouses()

	{

		echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="warehouses">Almacenes</label>
						<div class="input-group">		
							<span class="input-group-addon"><i class="fa fa-building"></i></span>
							<select style="width: 100%" class="sel2Multi" id="warehouses" multiple name="warehouses[]">';

							foreach (json_decode($this->GetWarehouses()) as $row) 

							{

								echo '<option value="'.$row->warehouse_code.'"';
								
								if (isset($_POST["warehouses"]))  

								if (in_array($row->warehouse_code, $_POST["warehouses"], true))
									echo 'selected';
						
								echo'>'.$row->warehouse_code.' - '.$row->warehouse_name.'</option>';
																					
							} 

					echo	'</select>
						</div>
					</div>
				</div>
				';
	
	}

	function FilterCostcenters()

	{
		echo '<div class="row">
					<div class="form-group col-md-12">
					<label for="costcenters">Centros de costo</label>
					<div class="input-group">		
						<span class="input-group-addon"><i class="fa fa-home"></i></span>					
						<select style="width: 100%" class="sel2Multi" id="costcenters" multiple name="costcenters[]">';

							foreach (json_decode($this->GetCostCenters()) as $row) 

							{

								echo '<option value="'.$row->cost_center_code.'"';
								
								if (isset($_POST["costcenters"]))  

								if (in_array($row->cost_center_code, $_POST["costcenters"], true))
									echo 'selected';
						
								echo'>'.$row->cost_center_code.' - '.$row->cost_center_name.'</option>';
																					
							} 

				echo	'</select>
					</div>
				</div>
			</div>

				';

	}

	function DatePicker($startdate)

	{

			echo '<div class="row">
					<div class="form-group col-md-12">
						<label for="datepickerDate">Fecha</label>
						<div class="input-group">
							<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
							<input type="text" class="form-control" id="datepicker" name="startrange" value="'.$startdate.'">
						</div>
					</div>
				</div>';

	}

	function ExcludeSeller()

	{
			echo '<div class="form-group col-sm-offset-4">
				<div class="checkbox-nice">          									
				<input type="checkbox" id="excludeseller" name="excludeseller"';

				(isset($_POST["excludeseller"]) == TRUE ? $exclution_condition = 'checked = "checked">' : $exclution_condition = '>');
				echo $exclution_condition;

				echo'<label for="excludeseller">Excluir vendedor</label>
						</div>
				</div>';

	}

	function ExcludeCollector()

	{
			echo '<div class="form-group col-sm-offset-4">
				<div class="checkbox-nice">          									
				<input type="checkbox" id="excludecollector" name="excludecollector"';

				(isset($_POST["excludecollector"]) == TRUE ? $exclution_condition = 'checked = "checked">' : $exclution_condition = '>');
				echo $exclution_condition;

				echo'<label for="excludecollector">Excluir vendedor</label>
						</div>
				</div>';

	}

	function DidntBuy()

	{
			echo '
				<div class="form-group col-sm-offset-4">
				<div class="checkbox-nice">          									
				<input type="checkbox" id="didntbuy" name="didntbuy"';

				(isset($_POST["didntbuy"]) == TRUE ? $didntbuy_condition = 'checked = "checked">' : $didntbuy_condition = '>');
				echo $didntbuy_condition;

				echo'<label for="didntbuy">Clientes que no compraron</label>
						</div>
				</div> ';

	}


}

?>