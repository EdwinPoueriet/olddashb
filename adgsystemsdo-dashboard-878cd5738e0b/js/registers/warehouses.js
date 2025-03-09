
		function warehouselist(){

			location.reload();
			
		}

		$('.edit').on('click', function(){

			$.ajax({

				method: 'POST',

				url: 'ajax/warehouses.php',

				data: $('#'+this.id).serialize(),

				complete: function(response){

					$('#warehouse_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();

				},

				error: function (response){

					alert('Hubo un error al enviar la solicitud');

				}

			});

		});

		function warehouseedit(){

			$.ajax({

				method: 'POST',

				url: 'ajax/warehouses.php',

				data: $("#warehouseeditsubmit").serialize(),

				complete: function(response){

					$('#response').html(response.responseText);

				},

				error:  function(response){

					alert('');

				}

			});

		}; 

		function createlist(){

			$.ajax({

				method: 'POST',

				url: 'ajax/warehouses.php',

				data: $("#createlist").serialize(),

				complete: function(response){
					$('#warehouse_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();

				},

				error:  function(response){

					alert('');

				}

			});

		}; 

		function createsubmit(){

			$.ajax({

				method: 'POST',

				url: 'ajax/warehouses.php',

				data: $("#createform").serialize(),

				complete: function(response){

					$('#content').html(response.responseText);

				},

				error:  function(response){

					alert('');

				}

			});

		}; 