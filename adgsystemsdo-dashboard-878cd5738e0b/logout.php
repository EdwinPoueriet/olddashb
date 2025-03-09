<?php
if(session_destroy()) // Destruye la sesion
{
header("Location: /");
}
?>
