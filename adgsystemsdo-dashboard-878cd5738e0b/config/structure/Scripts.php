<?php

namespace App\Legacy;

class Scripts 
{
	
	static function Initialize()
	{
		echo '<!-- global scripts -->
			<script src="js/demo-skin-changer.js"></script> <!-- only for demo -->
			
			<script src="js/jquery.js"></script>
			<script src="js/bootstrap.js"></script>
			<script src="js/jquery.nanoscroller.min.js"></script>
			
			<script src="js/demo.js"></script> 
			<!-- only for demo -->
			
			<!-- this page specific scripts -->
			<script src="js/jquery.sparkline.min.js"></script>
			<script src="js/moment.min.js"></script>
			<script src="js/jquery-jvectormap-1.2.2.min.js"></script>
			<script src="js/jquery-jvectormap-world-merc-en.js"></script>
			<script src="js/gdp-data.js"></script>
			<script src="js/skycons.js"></script>

			<script src="js/d3.min.js"></script>
			<script src="js/xcharts.js"></script>

			<script src="js/jquery.knob.js"></script>
			<script src="js/raphael-min.js"></script>
			<script src="js/morris.js"></script>

			<script src="js/moment.min.js"></script>
			<script src="js/daterangepicker.js"></script>
			<script src="js/bootstrap-datepicker.js"></script>

			<script src="js/select2.min.js"></script>
<script src="js/footable.min.js"></script>
		<!--	<script src="js/footable.js"></script>
			<script src="js/footable.sort.js"></script>
			<script src="js/footable.paginate.js"></script>
			<script src="js/footable.filter.js"></script>-->
			
			<script src="js/wizard.js"></script>

			<script src="js/scripts.js"></script>
			<script src="js/pace.min.js"></script>
			<script src="/js/toastr.min.js"></script>

			<script type="text/javascript">
					$(".footable").footable();
					$(".sel2Multi").select2({
				//	placeholder: "Select a Country",
				allowClear: true
				});
			</script>
			<script>
				$("#datepicker").datepicker({
				    format: "yyyy-mm-dd",
				});
			</script>
';
	}
}


?>