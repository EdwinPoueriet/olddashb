		function sectorlist(){

			location.reload();
			
		}

		$('.edit').on('click', function(){

			$.ajax({

				method: 'POST',

				url: 'ajax/sectors.php',

				data: $('#'+this.id).serialize(),

				complete: function(response){

					$('#sector_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();

				},

				error: function (response){

					alert('Hubo un error al enviar la solicitud');

				}

			});

		});

		function sectoredit(){

			$.ajax({

				method: 'POST',

				url: 'ajax/sectors.php',

				data: $("#sectoreditsubmit").serialize(),

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

				url: 'ajax/sectors.php',

				data: $("#createlist").serialize(),

				complete: function(response){
					$('#sector_list').hide();
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

				url: 'ajax/sectors.php',

				data: $("#createform").serialize(),

				complete: function(response){

					$('#content').html(response.responseText);

				},

				error:  function(response){

					alert('');

				}

			});

		}; 