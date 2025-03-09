
		function userlist(){

			location.reload();
			
		}

		$('.edit').on('click', function(){

			$.ajax({

				method: 'POST',

				url: 'legacy/ajax/Users',

				data: $('#'+this.id).serialize(),

				complete: function(response){

			

					$('#user_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();
					$('.sel2Multi').select2({
			//	placeholder: 'Select a Country',
			allowClear: true
		});

				},

				error: function (response){

					alert('Hubo un error al enviar la solicitud');

				}

			});

		});

		function useredit(){
			var valid = true;
            var nombre_usu = $('#user_name').val();

            var bluetooh_usu = $('#user_bluetooh_address').val();
            var printer_usu = $('#user_printer').val();

            if (!nombre_usu.trim()){
                $('#label-user').css('color', 'Red');
                valid = false;
            }

            if (bluetooh_usu){
                if (printer_usu == 0){
                	$('#user_printer').css('color', 'red');
                    valid = false;
                }
            }

            var cliente, recibos, pedidos, factura, visitas;
           cliente =$('#user_sync_customer').prop('checked');
			recibos =$('#user_sync_income').prop('checked');
			pedidos=$('#user_sync_order').prop('checked');
			factura =$('#user_sync_invoice').prop('checked');
            visitas =$('#user_sync_visit').prop('checked');
			
			if (cliente || recibos || pedidos || factura || visitas){

                $('#check-checkbox').css('color', 'black');

			}else{
                valid = false;

                $('#check-checkbox').css('color', 'red');



			}

            if (valid){
                $('#label-user').css('color', 'Black');
                $.ajax({
                    method: 'POST',
                    url: 'legacy/ajax/Users',
                    data: $("#sellereditsubmit").serialize(),
                    complete: function(response){
                        $('#response').html(response.responseText);

                        toastr.options.closeButton = true;
                        toastr.success('Datos editados con exito.', {timeOut: 5000});
                    },
                    error:  function(response){
                        toastr.options.closeButton = true;
                        toastr.error('Error al enviar los datos.', 'Aviso', {timeOut: 5000});
                    }
                });

			}





		}; 

		function create(){

			$.ajax({

				method: 'POST',

				url: 'legacy/ajax/Users',

				data: $("#createlist").serialize(),

				complete: function(response){
					$('#user_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();
					$('.sel2Multi').select2({
	  //	placeholder: 'Select a Country',
	  allowClear: true
	});

				},

				error:  function(response){
					console.log(response)
					alert('');

				}

			});

		}; 

		function createsubmit(){

			$.ajax({

				method: 'POST',

				url: 'legacy/ajax/Users',

				data: $("#createform").serialize(),

				complete: function(response){

					$('#content').html(response.responseText);

				},

				error:  function(response){

				console.log(response)

				}

			});

		}; 
