<?php

namespace App\Controllers;

class ReportsController extends BaseController
{
    public function cobros()
    {
        return $this->view('reports.cobrosReport', [

        ]);
    }
    public function ventas()
    {
        return $this->view('reports.ventasReport', [
        ]);
    }
    public function cuentacobrar()
    {
        return $this->view('reports.cuentacobrar', [
        ]);
    }
    public function visitas()
    {
        return $this->view('reports.visitasReport', [
        ]);
    }

    public function ventasDuracion()
    {
        return $this->view('reports.ventasDuracionReport', []);
    }

    public function horasTrabajadas () {
        return $this->view('reports.horasTrabajadasReport', []);
    }

    public function visitNoVentas(){
        return $this->view('reports.visitasNoVentas', []);
    }

    public function devoluciones()
    {
        return $this->view('reports.devolucionesReport', [
        ]);
    }

    public function cobrosadelanto()
    {
        return $this->view('reports.cobrosAdelantoReport', [
        ]);
    }

    public function depositos()
    {
        return $this->view('reports.depositosReport', [
        ]);
    }

}