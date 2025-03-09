<?php

namespace App\Legacy;



class Products extends General
  {

  function __construct()

  {

    parent::__construct();

    parent::ValidateSession($_SESSION['user_id']);

  }


  	public function GetProductsList()

  	{

  		$getproductslistjson = json_decode($this->GetProducts());

      echo '<div class="table-responsive" id="areas_list">
   
      <table class="table user-list footable table-hover" data-paging-size="12" data-paging-position="right" data-paging="true" data-filtering="true" data-sorting="true">
        <thead>
          <tr>
            <th>Referencia</th>
            <th>Nombre</th>
            <th>Existencia</th>
            <th data-type="html">Acciones</th>
          </tr>
        </thead>
        <tbody>';

  		foreach ($getproductslistjson as $row)
  		{

  			echo '
  			<tr>
  				<td>
  					<h2>'.$row->product_reference.'</h2>
  				</td>
  				<td>
  					<h2>'.$row->product_name.'</h2>    
  				</td>
  				<td>
  					<h2>'.number_format($row->product_in_stock).'</h2>    
  				</td>
  				<td style="width: 20%;">
            <form class="edit_form" style="display: inline-block; float: left" method="POST" onsubmit="event.preventDefault();">
              <input type="hidden" name="form_id" value="editoptions"></input>
              <input type="hidden" name="product_reference" value="'.trim($row->product_reference).'"></input>
            
                <a class="table-link edit" style="cursor:pointer" onclick="$(this).submit()">
                  <span class="fa-stack">
                    <i class="fa fa-square fa-stack-2x"></i>
                    <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                  </span>
                </a>
            </form>
  					<a href="#" class="table-link danger">
  						<span class="fa-stack">
  							<i class="fa fa-square fa-stack-2x"></i>
  							<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
  						</span>
  					</a>
  				</td>
  			</tr>';
  		}

      echo '</tbody>
        </table>
        <ul class="pagination pull-right hide-if-no-paging"></ul>
      </div>';

  	}

  	public function EditproductsOptions()

  	{

   		$editproductsoptionssql = $this->con->prepare("SELECT * FROM ".$this->user_database.".products 
  			WHERE company_id = :company_id AND TRIM(product_reference) = TRIM(:product_reference)");

  		$editproductsoptionssql->bindParam(':product_reference', $_POST['product_reference']); 

  		$editproductsoptionssql->bindParam(':company_id', $this->catalogue_company);

  		$editproductsoptionssql->execute();

  		$editproductsoptionssql = $editproductsoptionssql->fetchAll(\PDO::FETCH_ASSOC);

  		$editproductsoptionssqljson = json_decode(json_encode($editproductsoptionssql));

  		$row = $editproductsoptionssqljson[0];

   		echo '<input type="hidden" name="form_id" id="form_id" value="submitedit">

      <div class="row">

      <div class="col-md-6 col-sm-6">

  			<div class="form-group">

  				<label for="product_code" class="control-label col-sm-4">Código del producto:</label>

  				<div class="col-sm-8">

  					<input class="form-control input-sm" type="text" name="product_code" id="product_code" value="'.$row->product_code.'" readonly>

  				</div>

  			</div>

  			<div class="form-group">

  				<label for="product_reference" class="control-label col-sm-4">Referencia del producto:</label>

  				<div class="col-sm-8">

  					<input class="form-control input-sm" type="text" name="product_reference" id="product_reference" value="'.$row->product_reference.'" readonly>

  				</div>

  			</div>

  			<div class="form-group">

  				<label for="product_name" class="control-label col-sm-4">Nombre del producto:</label>

  				<div class="col-sm-8">

  					<input class="form-control input-sm" type="text" name="product_name" id="product_name" value="'.$row->product_name.'" maxlength="100">

  				</div>

  			</div>

  			<div class="form-group">

  				<label for="brand_id" class="control-label col-sm-4">Marca:</label>

  				<div class="col-sm-8">

  					<select class="form-control input-sm" type="number" name="brand_id" id="brand_id">
  						<option value="0"></option>';

  						foreach (json_decode($this->GetBrands()) as $brandrow)

  						{

  							echo '<option value="'.$brandrow->brand_id.'"'.$this->GetComparison($brandrow->brand_id, $row->brand_id).'>'.$brandrow->brand_name.'</option>';

  						} 

  						echo '</select>

  					</div>

  				</div>

  				<div class="form-group">

  					<label for="unit_id" class="control-label col-sm-4">Unidad:</label>

  					<div class="col-sm-8">

  						<select class="form-control input-sm" type="number" name="unit_id" id="unit_id">
  							<option value="0"></option>';

  							foreach (json_decode($this->GetUnits()) as $unitsrow)

  							{

  								echo '<option value="'.$unitsrow->unit_id.'"'.$this->GetComparison($unitsrow->unit_id, $row->unit_id).'>'.$unitsrow->unit_name.'</option>';

  							} 

  							echo '</select>

  						</div>

  					</div>

  					<div class="form-group">

  						<label for="classification_code" class="control-label col-sm-4">Clasificación:</label>

  						<div class="col-sm-8">

  							<select class="form-control input-sm" type="number" name="classification_code" id="classification_code">
  								<option value="0"></option>';

  								foreach (json_decode($this->GetClassifications()) as $classrow)

  								{

  									echo '<option value="'.$classrow->classification_code.'"'.$this->GetComparison($classrow->classification_code, $row->classification_code).'>'.$classrow->classification_name.'</option>';

  								} 

  								echo '</select>

  							</div>

  						</div>

  						<div class="form-group">

  							<label for="unit_id" class="control-label col-sm-4">Subclasificación:</label>

  							<div class="col-sm-8">

  								<select class="form-control input-sm" type="number" name="subclassification_code" id="subclassification_code">
  									<option value="0"></option>';

                    foreach (json_decode($this->GetClassifications()) as $classrow) 

                    {

                      echo '<optgroup label="'.$classrow->classification_name. ' - ' .$classrow->classification_name.'">';

                      foreach (json_decode($this->GetSubclassifications($classrow->classification_code)) as $subrow) 

                        {
                        
                          echo '<option value="'.$subrow->subclassification_code.'"'.$this->GetComparison($subrow->subclassification_code, $row->subclassification_code).'>'.$subrow->subclassification_code.' - '.$subrow->subclassification_name.'</option>';

                        }

                      echo '</optgroup>';
                                                
                    } 

  									echo '</select>

  								</div>

  							</div>

              <div class="form-group">

                <label for="product_type_code" class="control-label col-sm-4">Tipo de producto:</label>

                <div class="col-sm-8">

                  <select class="form-control input-sm" type="number" name="product_type_code" id="product_type_code">
                    <option value="0"></option>';

                    foreach (json_decode($this->GetProductTypes()) as $prodtyperow)

                    {

                      echo '<option value="'.$prodtyperow->product_type_code.'" '.$this->GetComparison($prodtyperow->product_type_code, $row->product_type_code).'>'.$prodtyperow->product_type_name.'</option>';

                    } 

                    echo '</select>

                  </div>

                </div>

              <div class="form-group">

                <label for="family_code" class="control-label col-sm-4">Familia del producto:</label>

                <div class="col-sm-8">

                  <select class="form-control input-sm" type="number" name="family_code" id="family_code">
                    <option value="0"></option>';

                    foreach (json_decode($this->GetProductFamilies()) as $prodfamrow)

                    {

                      echo '<option value="'.$prodfamrow->family_code.'" '.$this->GetComparison($prodfamrow->family_code, $row->family_code).'>'.$prodfamrow->family_name.'</option>';

                    } 

                    echo '</select>

                  </div>

                </div>

              <div class="form-group">

                <label for="group_code" class="control-label col-sm-4">Grupo del producto:</label>

                <div class="col-sm-8">

                  <select class="form-control input-sm" type="number" name="group_code" id="group_code">
                    <option value="0"></option>';

                    foreach (json_decode($this->GetProductGroups()) as $prodgrprow)

                    {

                      echo '<option value="'.$prodgrprow->group_code.'"'.$this->GetComparison($prodgrprow->group_code, $row->group_code).'>'.$prodgrprow->group_name.'</option>';

                    } 

                    echo '</select>

                  </div>

                </div>

  							<div class="form-group">

  								<label for="product_in_stock" class="control-label col-sm-4">Existencia en el almacén:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_in_stock" id="product_in_stock" value="'.$row->product_in_stock.'">

  								</div>

  							</div>

                </div>

                <div class="col-md-6 col-sm-6">

  							<div class="form-group">

  								<label for="product_packaging" class="control-label col-sm-4">Empaques:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_packaging" id="product_packaging" value="'.$row->product_packaging.'">

  								</div>

  							</div>

  							<div class="form-group">

  								<label for="product_first_price" class="control-label col-sm-4">Primer precio:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_first_price" id="product_first_price" value="'.$row->product_first_price.'">

  								</div>

  							</div>

  							<div class="form-group">

  								<label for="product_second_price" class="control-label col-sm-4">Segundo precio:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_second_price" id="product_second_price" value="'.$row->product_second_price.'">

  								</div>

  							</div>

  							<div class="form-group">

  								<label for="product_third_price" class="control-label col-sm-4">Tercer precio:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_third_price" id="product_third_price" value="'.$row->product_third_price.'">

  								</div>

  							</div>

  							<div class="form-group">

  								<label for="product_fourth_price" class="control-label col-sm-4">Cuarto precio:</label>

  								<div class="col-sm-8">

  									<input class="form-control input-sm" type="number" name="product_fourth_price" id="product_fourth_price" value="'.$row->product_fourth_price.'">

  								</div>

  							</div>

                 <div class="form-group">

                  <label for="product_format" class="control-label col-sm-4">Formato del producto:</label>

                  <div class="col-sm-8">

                    <select class="form-control input-sm" type="number" name="product_format" id="product_format">

                      <option value="0"';if ($row->product_format == 0) echo 'selected'; echo'>Todos</option>
                      
                      <option value="1"';if ($row->product_format == 1) echo 'selected'; echo'>Regular</option>

                      <option value="2"';if ($row->product_format == 2) echo 'selected'; echo'>Kit</option>

                      <option value="3"';if ($row->product_format == 3) echo 'selected'; echo'>Combo</option>

                    </select>

                  </div>

                </div>

                <div class="form-group">

                  <label for="product_tax_percent" class="control-label col-sm-4">Porciento de Impuesto:</label>

                  <div class="col-sm-8">

                    <input class="form-control input-sm" type="number" name="product_tax_percent" id="product_tax_percent" value="'.$row->product_tax_percent.'">

                  </div>

                </div>

                <div class="form-group">

                  <div class="col-md-offset-2">
                    <div class="checkbox-nice">

                      <input type="checkbox" name="product_pays_tax" id="product_pays_tax" value="S"'.$this->GetCheckBoxComparison($row->product_pays_tax, "S").'>
                      <label for="product_pays_tax">El producto paga impuestos.</label>

                    </div>
                  </div>

                </div>

   							<div class="form-group">

  								<div class="col-md-offset-2">
  									<div class="checkbox-nice">

  										<input type="checkbox" name="product_accepts_discount" id="product_accepts_discount" value="S" '.$this->GetCheckBoxComparison($row->product_accepts_discount, "S").'>
  										<label for="product_accepts_discount">El producto acepta descuentos.</label>

  									</div>
  								</div>

  							</div>

  							<div class="form-group">

  								<div class="col-md-offset-2">
  									<div class="checkbox-nice">

  										<input type="checkbox" name="product_offer" id="product_offer" value="1" '.$this->GetCheckBoxComparison($row->product_offer, "1").'>
  										<label for="product_offer">El producto está en oferta.</label>

  									</div>
  								</div>

  							</div>

                </div>

                </div>

  						';

  					}

  					public function EditGeneral() {

   						$productseditsubmitsql = $this->con->prepare("UPDATE  
  							".$this->user_database.".products SET 
							  product_reference = :product_reference, 
							  product_name = :product_name,
							  brand_id = :brand_id,
							  unit_id = :unit_id,
							  classification_code = :classification_code,
							  subclassification_code = :subclassification_code,
							  product_pays_tax = :product_pays_tax,
							  product_tax_percent = :product_tax_percent,
							  product_in_stock = :product_in_stock,
							  product_last_update_date = :product_last_update_date,
							  product_packaging = :product_packaging,
							  product_first_price = :product_first_price,
							  product_second_price = :product_second_price,
							  product_third_price = :product_third_price,
							  product_fourth_price = :product_fourth_price,
							  product_type_code = :product_type_code,
							  family_code = :family_code,
							  group_code = :group_code,
							  product_offer = :product_offer,
							  product_accepts_discount = :product_accepts_discount,
							  product_format = :product_format
  							WHERE product_code = :product_code AND company_id = :company_id
  							");

  						$productseditsubmitsql->bindParam(':company_id', $this->catalogue_company);
  						$productseditsubmitsql->bindParam(':product_code', $_POST['product_code']);
   						$productseditsubmitsql->bindParam(':product_reference', $_POST['product_reference']);
   						$productseditsubmitsql->bindParam(':product_name', $_POST['product_name']);
   						$productseditsubmitsql->bindParam(':brand_id', $_POST['brand_id']);
   						$productseditsubmitsql->bindParam(':unit_id', $_POST['unit_id']);
   						$productseditsubmitsql->bindParam(':classification_code', $_POST['classification_code']);
   						$productseditsubmitsql->bindParam(':subclassification_code', $_POST['subclassification_code']);
   						$productseditsubmitsql->bindParam(':product_pays_tax', $_POST['product_pays_tax']);
   						$productseditsubmitsql->bindParam(':product_tax_percent', $_POST['product_tax_percent']);
   						$productseditsubmitsql->bindParam(':product_in_stock', $_POST['product_in_stock']);
   						$productseditsubmitsql->bindParam(':product_last_update_date', $_POST['product_last_update_date']);
   						$productseditsubmitsql->bindParam(':product_packaging', $_POST['product_packaging']);
   						$productseditsubmitsql->bindParam(':product_first_price', $_POST['product_first_price']);
   						$productseditsubmitsql->bindParam(':product_second_price', $_POST['product_second_price']);
   						$productseditsubmitsql->bindParam(':product_third_price', $_POST['product_third_price']);
   						$productseditsubmitsql->bindParam(':product_fourth_price', $_POST['product_fourth_price']);
   						$productseditsubmitsql->bindParam(':product_type_code', $_POST['product_type_code']);
   						$productseditsubmitsql->bindParam(':family_code', $_POST['family_code']);
   						$productseditsubmitsql->bindParam(':group_code', $_POST['group_code']);
   						$productseditsubmitsql->bindParam(':product_offer', $_POST['product_offer']);
   						$productseditsubmitsql->bindParam(':product_accepts_discount', $_POST['product_accepts_discount']);
   						$productseditsubmitsql->bindParam(':product_format', $_POST['product_format']);

  						$productseditsubmitsql->execute();

  						echo '<div class="text-center"><b>Los cambios han sido realizados correctamente!.</b></div>';

  					}

public function CreateGeneral() {

	$verifyproducts = $this->con->prepare("SELECT 
    product_code 
    FROM ".$this->user_database.".products 
    WHERE product_code = :product_code 
    AND company_id = :company_id");
	$verifyproducts->bindParam (':product_code', $_POST['product_code']);
	$verifyproducts->bindParam(':company_id', $this->catalogue_company);
	$verifyproducts->execute();
	$verifyproducts = $verifyproducts->rowCount();

	if ($verifyproducts > 0) {

		echo '<div class="text-center"><b>El producto '.$_POST['product_code'].' ya existe en la compañía '.$this->catalogue_company.'!</b></div>';
	}

	else {

		$_POST['product_last_update_date'] = gmdate('Y-m-d');

		if ($_POST['product_pays_tax'] == NULL)
			$product_pays_tax = 'N';
    else
      $product_pays_tax = $_POST['product_pays_tax'];

		if ($_POST['product_accepts_discount'] == NULL)
			$product_accepts_discount = 'N';
    else 
      $product_accepts_discount = $_POST['product_accepts_discount'];

		if (!isset($_POST['product_packaging']))
			$product_accepts_discount = "0.00";

		if (!isset($_POST['product_offer']))
			$product_offer = "0.00";

		$this->con->beginTransaction();

		try{

			$productseditsubmitsql = $this->con->prepare("INSERT INTO
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

				$productseditsubmitsql->bindParam(':company_id', $this->catalogue_company);

				$productseditsubmitsql->bindParam(':product_code', $_POST['product_code']);

				$productseditsubmitsql->bindParam(':product_reference', $_POST['product_reference']);
				
				$productseditsubmitsql->bindParam(':product_name', $_POST['product_name']);
				
				$productseditsubmitsql->bindParam(':brand_id', $_POST['brand_id']);
				
				$productseditsubmitsql->bindParam(':unit_id', $_POST['unit_id']);
				
				$productseditsubmitsql->bindParam(':classification_code', $_POST['classification_code']);
				
				$productseditsubmitsql->bindParam(':subclassification_code', $_POST['subclassification_code']);
				
				$productseditsubmitsql->bindParam(':product_pays_tax', $product_pays_tax);
				
				$productseditsubmitsql->bindParam(':product_tax_percent', $_POST['product_tax_percent']);
				
				$productseditsubmitsql->bindParam(':product_in_stock', $_POST['product_in_stock']);
				
				$productseditsubmitsql->bindParam(':product_last_update_date', $_POST['product_last_update_date']);
				
				$productseditsubmitsql->bindParam(':product_packaging', $_POST['product_packaging']);
				
				$productseditsubmitsql->bindParam(':product_first_price', $_POST['product_first_price']);
				
				$productseditsubmitsql->bindParam(':product_second_price', $_POST['product_second_price']);
				
				$productseditsubmitsql->bindParam(':product_third_price', $_POST['product_third_price']);
				
				$productseditsubmitsql->bindParam(':product_fourth_price', $_POST['product_fourth_price']);
				
				$productseditsubmitsql->bindParam(':product_type_code', $_POST['product_type_code']);
				
				$productseditsubmitsql->bindParam(':family_code', $_POST['family_code']);
				
				$productseditsubmitsql->bindParam(':group_code', $_POST['group_code']);
				
				$productseditsubmitsql->bindParam(':product_offer', $product_offer);
				
				$productseditsubmitsql->bindParam(':product_accepts_discount', $product_accepts_discount);
				
				$productseditsubmitsql->bindParam(':product_format', $_POST['product_format']);

				$productseditsubmitsql->execute();

			 $this->con->commit();

        echo '<div class="text-center"><b>El producto ha sido creado con exito!</b></div>';

		}

		catch(\Exception $e){

			echo $e->getMessage();
			$this->con->rollBack();

		}

	}

}
}

if (isset($_POST['form_id'])){

  if ($_POST['form_id'] == 'getvalues'){

        $getvalues = new Products;
        $getvalues->GetProductsList();

  }

	if ($_POST['form_id'] == 'editoptions') {

		$editoptions = new Products;
		$editoptions->EditproductsOptions();

	}

	if ($_POST['form_id'] == 'submitedit') {

		$editoptions = new Products;
		$editoptions->EditGeneral();

	}

	if ($_POST['form_id'] == 'submitcreate') {
		$createoptions = new Products;
		$createoptions->CreateGeneral();
	}

}


?>