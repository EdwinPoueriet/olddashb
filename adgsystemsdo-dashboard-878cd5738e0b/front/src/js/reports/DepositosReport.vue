<template>
  <div>

    <div class="alert alert-warning" v-if="alert" role="alert">
      Para ver el detallado imprima el reporte.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true" @click="alert = false">&times;</span>
      </button>
    </div>
    <div class="row" style="padding: 20px">
      <div class="col-xs-12">
        <div class="main-box" >
          <div class="main-box-header clearfix"  >
            <div class="row">
              <div class="col-sm-2">
                <h2 style="color: #03a9f4; font-weight: 500">Filtros</h2>
              </div>
              <div class="col-sm-10">
                <form :action="'/legacy/print/depositos'+tipo+''"method="post" target="_blank">
                  <template  v-if="showPrint" >
                    <!--<i @click="showPrint = false" class="fa fa-eye-slash"></i>-->
                    <input type="hidden"  name="order_by" v-model="orden" >
                    <input type="hidden"  name="order_method" v-model="orden_method" >
                    <input type="hidden" :value="rango" name="rango">
                    <input type="hidden" :value="vendedor" name="vendedor">
                    <div class="col-sm-5">

                      <div class="filter-value-container form-inline"  >
                        <label>Filtrar Compañia</label>
                        <select name="company"  class="form-control" v-model="selectedCompany" >
                          <option value="consolidado">Reporte Consolidado</option>
                          <option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div  v-if="selectedCompany == 'consolidado'" class="filter-value-container form-inline" >
                        <label>Vista en Reporte</label>
                        <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
                          <option value="consolidado">Agrupados en Misma Lista</option>
                          <option value="separado">Separados por Compañia</option>
                        </select>
                      </div>
                      <i @click="showPrint = false" style="position: absolute; right: 10px; top: 10px;" class="fa fa-eye-slash"></i>
                    </div>
                    <div class="col-sm-2">
                      <button style="margin-left: 5px" type="submit"  class="btn btn-success pull-right">
                        <i class="fa fa-print fa-fw"></i>
                        Imprimir
                      </button>
                    </div>
                  </template>
                </form>
                <button  v-if="!showPrint"  @click="showPrint = true" style="margin-left: 5px"   class="btn btn-success pull-right">
                  <i class="fa fa-print fa-fw"></i>
                  Imprimir
                </button>
              </div>
            </div>
          </div>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body ">
            <div class="row">
              <div class="col-sm-4">
                <div class="form-group">
                  <label>Tipos de Reportes</label>
                  <div class="filter-value-container" style="margin-top:3px;">
                    <select name="tipo" class="form-control" v-model="tipo">
                      <option value="compacto">Resumido</option>
                      <option value="detallado">Detallado</option>
                    </select>
                  </div>
                </div>

              </div>
              <div class="col-sm-4">
                <div class="form-group" >
                  <label>Rango de Fecha</label>
                  <div class="filter-range-container" style="margin-top:3px; " >
                    <div class="input-group" >
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input autocomplete="off"  type="text" class=" form-control" id="date-input" name="rango">
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
                    <select @change="generarClicked()" name="vendedor" class="form-control" v-model="vendedor" >
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
            <table class="table">
              <tr style="    background: antiquewhite;">
                <td><span class="total-header">Total Cantidad:</span></td>
                <td><span class="total-value">{{ totales.cantidad }}</span></td>
                <td><span class="total-header">Total:</span></td>
                <td><span class="total-value">{{formatMoney(totales.total)}}</span></td>

              </tr>
            </table>
          </div>

        </div>


      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="main-box" style="padding-top: 20px">
          <template v-if="loading">
            <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i></div>
          </template>
          <div  class="main-box-body">
            <div class="tablewrapper">
              <div id="jsGrid"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<style>
  .total-header {
    font-weight: 600;
  }
</style>
<script>
  import {baseOptions, totalRow} from './grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../common'
  require('./gridsetup')

  export default {


    data () {
      return {
        hideTableOpt: false,
        currentSorting: {
          field: null,
          order: null
        },
        showPrint: false,
        loading: true,
        showInactiveSellers: false,
        vendedores_inactivos: sellersListInactive,
        companies: companyList,
        vendedores: sellersList,
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        consolidado_group: 'consolidado',
        selectedCompany: 'consolidado',
        vendedor: 'todos',
        tipo: 'compacto',
        orden: 'seller_code',
        orden_method: 'asc',
        totales : {
          cantidad: 0,
          total: 0
        },
        alert: false
      }
    },
    watch: {

      tipo (val) {
        if(val === 'compacto'){

          this.orden = 'seller_code';

        }else{

          this.orden = 'date';
        }
        this.generarClicked();

      }
    },
    methods: {
      generarClicked() {

        if (this.tipo != 'detallado'){
          this.fetchReportData();
          return
        }
        this.alert = true


      },
      fetchReportData () {
        this.loading = true;
        $.ajax({
          method: 'POST',
          url: '/reports/depositos',
          data: {
            order_by: this.orden,
            order_method : this.orden_method,
            tipo: this.tipo,
            rango: this.rango,
            company: this.selectedCompany,
            consolidado_group: this.consolidado_group,
            vendedor: this.vendedor
          },
          success:  data => {
            if (!(data instanceof Object) ) {
              data = JSON.parse(data);
            }
            this.loading = false;
            this.updateTable(data, this.tipo, this.consolidado_group)

          }
        });
      },
      hideTable (val) {
        this.hideTableOpt = val;
      },
      formatMoney: formatMoney,
      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },

      updateTable (data, tipo, agrupacion) {
        let result = null;
        if (tipo !== 'detallado') {
          result = this.loadResumido(data, agrupacion)
        }
        this.totales = this.calcularTotales(tipo, data)
        this.updateTable2(result.options,result.cantidad)
      },
      updateTable2(options, cantidad) {
        this.cantidad = cantidad;
        const grid =  $("#jsGrid");
        grid.jsGrid("destroy");
        grid.jsGrid(
                {...options,
                  width: '100%',
                  onRefreshed: () => {
                    const sorting = grid.jsGrid("getSorting");
                    this.setSortingParams(sorting);
                  },

                }
        );
        grid.jsGrid("option", "filtering", false);
      },

      calcularTotales (tipo, data) {

        const totales = {
          total: 0,
          cantidad: 0
        }

        if (tipo !== 'detallado') {
          data.forEach(val => {
            totales.cantidad += parseInt(val.cantidad);
            totales.total += moneyToNumber(val.Total);
          })
        }
        return totales;
      },

      loadResumido (data) {
        let options = {}
        let cantidad = 0;
        cantidad = data.length
        options =  {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
              return data.filter(val => val.vendedor.toLowerCase().includes(filter.vendedor.toLowerCase()))
            }
          },
          fields: [
            { name: "vendedor",title:'Vendedor', type: "text", width: 130,  filtering: true},

            { name: "cantidad",title:'Cantidad', type: "text", width: 50,  filtering: false},

            { name: "Total", type: "text", width: 100, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Total')
            },
            { type: "control" ,  headerTemplate: function() {
                return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, false);
              },     searchButtonTooltip: "Buscar",  clearFilterButtonTooltip: "Limpiar Filtros",
              inserting:false,editing:false,filtering:true,editButton: false,deleteButton:false
            }
          ]
        }
        return {options,cantidad}
      }

    },
    mounted() {
      $('.boxi').matchHeight();
      const date =$('#date-input');
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
      this.generarClicked()

    }

  }
</script>