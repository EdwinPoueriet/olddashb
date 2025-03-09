<template>
  <div>

    <div class="row">
      <div class="col-xs-12">
        <div class="main-box" >
          <div class="main-box-header clearfix"  >
            <div class="row">
              <div class="col-sm-2">
                <h2 style="color: #03a9f4; font-weight: 500">Filtros</h2>
              </div>
              <div class="col-sm-10">
                <form :action="'/legacy/print/cobrosadelanto'" method="post" target="_blank">
                  <template  v-if="showPrint">
                    <!--<i @click="showPrint = false" class="fa fa-eye-slash"></i>-->
                    <input type="hidden" :value="rango" name="rango">
                    <input type="hidden" :value="cobrador" name="cobrador">
                    <input type="hidden"  name="order_by" v-model="orden" >
                    <input type="hidden"  name="order_method" v-model="orden_method" >
                    <div class="col-sm-5">
                      <div class="filter-value-container form-inline">
                        <label>Filtrar Compañia</label>
                        <select name="company"  class="form-control" v-model="selectedCompany" >
                          <option value="consolidado">Reporte Consolidado</option>
                          <option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div  v-if="selectedCompany == 'consolidado'" class="filter-value-container form-inline">
                        <label>Vista en Reporte</label>
                        <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
                          <option value="consolidado">Agrupados en Misma Lista</option>
                          <option value="separado">Separados por Compañia</option>
                        </select>
                      </div>
                      <i @click="showPrint = false" style="position: absolute; right: 10px; top: 10px;" class="fa fa-eye-slash"></i>
                    </div>
                    <div class="col-sm-2">
                      <button type="submit"  class="btn btn-success pull-right">
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
            <div class="row" style="padding: 20px">
              <div class="col-xs-12">
                <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                      <label>Tipos de Reportes</label>
                      <div class="filter-value-container" style="margin-top:3px;">
                        <select name="tipo" class="form-control" v-model="tipo">
                          <option value="detallado">Detallado</option>
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
                      <label>Cobradores</label>
                      <div class="filter-value-container" style="margin-top:3px;">
                        <select @change="generarClicked()" :disabled="tipo !== 'detallado'" name="cobrador" class="form-control" v-model="cobrador" >
                          <option value="todos">Todos</option>
                          <option v-for="cobrador in cobradores" :value="cobrador.collector_code">{{ cobrador.collector_code }} -  {{ cobrador.collector_name }}</option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <table class="table">
                  <tr style="    background: antiquewhite;">
                    <td><span class="total-header">Total Efectivo:</span></td>
                    <td><span class="total-value">RD${{formatMoney(totales.efectivo)}}</span></td>
                    <td><span class="total-header">Total Cheque:</span></td>
                    <td><span class="total-value">RD${{formatMoney(totales.cheque)}}</span></td>
                    <td><span class="total-header">Total Tarjeta:</span></td>
                    <td><span class="total-value">RD${{formatMoney(totales.tarjeta)}}</span></td>
                    <td><span class="total-header">Total General:</span></td>
                    <td><span class="total-value">RD${{formatMoney(totales.total)}}</span></td>
                  </tr>
                </table>
              </div>
            </div>
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
          <div v-show="!hideTableOp" class="main-box-body">
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
  // import wrapper from './ReportWrapper.vue'
  // import cobros from './CobrosAdelanto.vue'
  import {baseOptions, totalRow} from './grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../common'
  require('./gridsetup')

  export default {
    // components: {wrapper, cobros},
    data () {
      return {
        currentSorting: {
          field: null,
          order: null

        },
        loading: false,
        showPrint: false,
        hideTableOp: false,
        totales : {
          efectivo: 0,
          cheque: 0,
          tarjeta:0,
          total: 0
        },
        orden: 'date',
        orden_method: 'desc',
        tipo: 'detallado',
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        companies: companyList,
        cobradores: collectorsList,
        selectedCompany: 'consolidado',
        cobrador: 'todos',
        consolidado_group: 'consolidado'
      }
    },
    methods: {
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
      generarClicked() {
        this.fetchReportData();
      },
      fetchReportData () {
        this.loading = true;
        // this.$emit('loading', true)
        $.ajax({
          method: 'POST',
          url: '/reports/cobrosadelanto',
          data: {
            order_by: this.orden,
            order_method : this.orden_method,
            tipo: this.tipo,
            rango: this.rango,
            company: this.selectedCompany,
            cobrador: this.cobrador,
            consolidado_group: this.consolidado_group
          },
          success:  data => {
            if (!(data instanceof Object) ) {
              data = JSON.parse(data);
            }

            this.updateTable(data, this.consolidado_group);
          },
          complete: () => {
            this.loading = false;
            // this.$emit('loading', false)
          }
        });
      },

      formatMoney: formatMoney,

      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },

      updateTable (data, agrupacion) {
        const result = this.loadDetallado(data, agrupacion)
        this.updateTable2(result.options,result.cantidad)
        this.totales = this.calcularTotales(data)

      },

      loadDetallado (data, agrupacion) {

        let options = {}
        let cantidad = 0;

        cantidad = data.length
        options =  {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
              return data.filter(val =>  {
                return  val.collector.toLowerCase().includes(filter.collector.toLowerCase()) &&
                        val.customer.toLowerCase().includes(filter.customer.toLowerCase()) &&
                        (val.advance_receipt_code ?
                                val.advance_receipt_code.toLowerCase().includes(filter.advance_receipt_code.toLowerCase()) : true) &&
                        val.advance_receipt_id_reference.toLowerCase().includes(filter.advance_receipt_id_reference.toLowerCase())
              })
            }
          },

          fields: [
            { name: "status", headerTemplate: function () {
                return '<i class="fa fa-cloud"></i>'
              }, itemTemplate: function (item) {
                return item === '1' ?  '<i class="fa fa-check"></i>': '';
              }, type: "text", width: 20, filtering: false},

            { name: "Compañia", type: "date", width: 60,  filtering: true, visible: agrupacion === 'separado'},

            { name: "date",title:'Fecha', type: "text", width: 60,  filtering: true,
              itemTemplate:function (item) {
                return moment(item).format('DD-MM-YYYY')
              }},

            { name: "collector",title:'Cobrador', type: "text", width: 150,  filtering: true},

            { name: "customer",title:'Cliente', type: "text", width: 180,  filtering: true},

            { name: "advance_receipt_id_reference",title:'No. Recibo', type: "text", width: 50,  filtering: true, sorter: "money"},

            { name: "advance_receipt_code",title:'Cód. Recibo', type: "text", width: 50,  filtering: true, sorter: "money"},

            { name: "cash_amount", type: "text", width: 60, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Efectivo')},

            { name:'check_amount', type: "text", width: 60, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Cheques') },

            { name:'card_amount', type: "text", width: 60, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Tarjeta') },

            { name: "amount", type: "text", width: 60, filtering: false,align: "right", sorter: "money",
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
      },

      calcularTotales (data) {

        const totales = {
          efectivo: 0,
          cheque: 0,
          tarjeta:0,
          total: 0
        }
        data.forEach(val => {
          totales.efectivo += moneyToNumber(val.cash_amount);
          totales.cheque += moneyToNumber(val.check_amount);
          totales.tarjeta += moneyToNumber(val.card_amount);
          totales.total += moneyToNumber(val.amount);
        })

        return totales;

      },

    },
    mounted () {
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
        this.generarClicked()
      });
      this.generarClicked()
    }
  }
</script>