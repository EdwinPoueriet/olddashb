
		function countrylist(){

			location.reload();
			
		}

		$('.edit').on('click', function(){

			$.ajax({

				method: 'POST',

				url: '/legacy/ajax/countries',

				data: $('#'+this.id).serialize(),

				complete: function(response){

					$('#country_list').hide();
					$('#content').html(response.responseText);
					$('#back').show();

				},

				error: function (response){

					alert('Hubo un error al enviar la solicitud');

				}

			});

		});

		function countryedit(){

			$.ajax({

				method: 'POST',

				url: '/legacy/ajax/countries',

				data: $("#countryeditsubmit").serialize(),

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

				url: '/legacy/ajax/countries',

				data: $("#createlist").serialize(),

				complete: function(response){
					$('#country_list').hide();
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

				url: '/legacy/ajax/countries',

				data: $("#createform").serialize(),

				complete: function(response){

					$('#content').html(response.responseText);

				},

				error:  function(response){

					alert('');

				}

			});

		}; 
