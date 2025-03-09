<?php
use App\Legacy\Header;
use App\Legacy\Navigation;
use App\Legacy\Footer;

$header = new Header;
$header->newInitialize(0, "Reportes del SDM Dashboard");
?>
@yield('styles')
<?php
$header->endNewInitialize();
$navigation = new Navigation;
$navigation->Initialize();


?>
@yield('content')

@yield('scripts')

<?php
Footer::Initialize();
\App\Legacy\Scripts::Initialize();

echo '</body>
</html>';
?>

