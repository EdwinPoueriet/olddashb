<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box">
                    <div class="main-box-header clearfix"  >
                        <div class="row">
                            <div class="col-sm-2">
                                <h2 style="color: #03a9f4; font-weight: 500" class="">Filtros</h2>
                            </div>
                            <div class="col-sm-10">
                                <form :action="'/legacy/print/rancheras'+tipo+''" method="post" target="_blank">
                                    <!--<template  v-if="showPrint"  >-->
                                    <input type="hidden" :value="rango" name="rango">
                                    <input type="hidden" :value="vendedor" name="seller_code">
                                    <!--<div class="col-sm-5">-->
                                    <!--<div class="filter-value-container form-inline"  >-->
                                    <!--<label>Filtrar Compañia</label>-->
                                    <!--<select name="company"  class="form-control" v-model="selectedCompany" >-->
                                    <!--<option value="consolidado">Reporte Consolidado</option>-->
                                    <!--<option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>-->
                                    <!--</select>-->
                                    <!--</div>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-5">-->
                                    <!--<div  v-if="selectedCompany == 'consolidado'" class="filter-value-container form-inline" >-->
                                    <!--<label>Vista en Reporte</label>-->
                                    <!--<select name="consolidado_group" class="form-control"  v-model="consolidado_group">-->
                                    <!--<option value="consolidado">Agrupados en Misma Lista</option>-->
                                    <!--<option value="separado">Separados por Compañia</option>-->
                                    <!--</select>-->
                                    <!--</div>-->
                                    <!--<i @click="showPrint = false" style="position: absolute; right: 10px; top: 10px;" class="fa fa-eye-slash"></i>-->
                                    <!--</div>-->
                                    <!--<div class="col-sm-2">-->

                                    <button style="margin-left: 5px" type="submit"  class="btn btn-success pull-right">
                                        <i class="fa fa-print fa-fw"></i>
                                        Imprimir
                                    </button>
                                    <!--</div>-->

                                    <!--</template>-->
                                </form>
                                <!--<button style="margin-left: 5px" type="submit"  class="btn btn-success pull-right">-->
                                <!--<i class="fa fa-print fa-fw"></i>-->
                                <!--Imprimir-->
                                <!--</button>-->
                                <!--<button  v-if="!showPrint"  @click="showPrint = true" style="margin-left: 5px"   class="btn btn-success pull-right">-->
                                <!--<i class="fa fa-print fa-fw"></i>-->
                                <!--Imprimir-->
                                <!--</button>-->
                            </div>
                        </div>
                        <hr style="margin-top:0; margin-bottom: 5px">
                        <div class="main-box-body ">
                            <div class="row" style="padding: 20px">
                                <div class="col-xs-12">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Tipos de Reportes</label>
                                                <div class="filter-value-container" style="margin-top:3px;">
                                                    <select name="tipo" class="form-control" v-model="tipo">
                                                        <option value="detallado">Detallado</option>
                                                        <!--<option value="compacto">Resumido</option>-->
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group" >
                                                <label>Rango de Fecha</label>
                                                <div class="filter-range-container" style="margin-top:3px;" >
                                                    <div class="input-group" >
                                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                        <input autocomplete="off" type="text" class=" form-control" id="date-input"  name="rango">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                                                    <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                                                </div>
                                                <label>Vendedores</label>
                                                <div class="filter-value-container" style="margin-top:3px;">
                                                    <select name="vendedor" @change="generarClicked()"  class="form-control" v-model="vendedor" >
                                                        <option value="todos">Todos</option>

                                                        <option v-for="vendedor in vendedores"
                                                                :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>

                                                        <option v-for="vendedor in vendedores_inactivos"
                                                                v-if="showInactiveSellers"
                                                                style="background-color: #f2dede"
                                                                :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--<div class="row" style="background: antiquewhite;">-->
                                    <!--<div class="col-sm-3">-->
                                    <!--<span class="total-header">Total Transacciones:</span> {{ totales.ventas }}-->

                                    <!--</div>-->
                                    <!--<div class="col-sm-3">-->
                                    <!--<span class="total-header">Total Bruto:</span> RD${{formatMoney(totales.bruto)}}-->

                                    <!--</div>-->
                                    <!--<div class="col-sm-3">-->
                                    <!--<span class="total-header">Total Descuentos:</span> RD${{formatMoney(totales.descuento)}}-->

                                    <!--</div>-->
                                    <!--<div class="col-sm-3">-->
                                    <!--<span class="total-header">Total Impuestos:</span> RD${{formatMoney(totales.impuestos)}}-->

                                    <!--</div>-->
                                    <!--<div class="col-sm-3">-->
                                    <!--<span class="total-header">Total General:</span> RD${{formatMoney(totales.total)}}-->

                                    <!--</div>-->


                                    <!--</div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box" style="padding: 20px">
                    <template v-if="loading">
                        <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i></div>
                    </template>
                    <template v-show="loading">
                        <table id="tabletest" class="">
                            <thead>
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Vendedores</th>
                                <th scope="col">Clientes</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Impuesto</th>
                                <th scope="col">Descuento</th>
                                <th scope="col">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in data">
                                <td>{{ item.invoice_date_time }}</td>
                                <td>{{ item.seller_code }} - {{ item.seller_name }}</td>
                                <td>{{ item.customer_code }} - {{ item.customer_name }}</td>
                                <td>{{ item.invoice_code }}</td>
                                <td>{{ item.invoice_tax_amount }}</td>
                                <td>{{ item.invoice_discount_amount }}</td>
                                <td>{{ item.total }}</td>
                            </tr>

                            </tbody>
                        </table>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>
<style>

</style>
<script>


  var table =  $('#tabletest').DataTable();

  export default {
    name: 'Ranchera',
    data () {
      return {
        currentSorting: {
          field: null,
          order: null

        },
        companies: companyList,
        showPrint: false,
        count: 0,
        hideTableOp: false,
        totales :{
          bruto: 0,
          descuento: 0,
          impuestos:0,
          total: 0,
          ventas: 0
        },
        loading: false,
        showInactiveSellers: false,
        vendedores_inactivos: sellersListInactive,

        tipo: 'detallado',
        vendedores: sellersList,
        data: [],


        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        selectedCompany: 'consolidado',
        consolidado_group:'consolidado',
        vendedor: 'todos'
      }
    },
    methods: {
      generarClicked(){
        this.loading = true;
        $.ajax({
          url: '/reports/facturas/rancheras',
          method: 'POST',
          data: {
            seller: this.vendedor,
            rango: this.rango,
            // customer: this.selectedCustomer,
          },
          success: (data) => {
            table.destroy();
            this.data = data



          },
          complete: ()=>{

            setTimeout(()=>{
              this.loading = false;
              table =  $('#tabletest').DataTable({
                // "ordering": false,
                'iDisplayLength': 25,
                "info":     false


              });



            }, 1000)




          }
        })

      },
      setupBasico () {
        let date = $('#date-input');
        date.daterangepicker({
          "applyClass": "btn-primary",
          startDate: moment(),
          ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Ultimos 7 dias':[moment().subtract(6, 'days'), moment()],
            'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          locale: {
            format: 'YYYY-MM-DD',
            applyLabel: 'Aceptar',
            cancelLabel: 'Borrar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
          }
        });
        date.on('apply.daterangepicker', (ev, picker) => {
          this.rango = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
          this.generarClicked();
        });
      }
    },
    mounted () {
      this.setupBasico();

      this.generarClicked()
    }
  }

</script>