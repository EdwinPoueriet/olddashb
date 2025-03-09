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
                <form :action="'/legacy/print/cobros'+tipo+''" method="post" target="_blank">
                  <template v-if="showPrint" >

                    <input type="hidden" :value="rango" name="rango">
                    <input type="hidden"  name="order_by" v-model="orden" >
                    <input type="hidden"  name="order_method" v-model="orden_method" >
                    <input type="hidden" name="cobrador"  v-model="cobrador" >
                    <div class="col-sm-3">
                      <div class="filter-value-container  form-inline "  >
                        <label>Filtrar Compañia</label>
                        <select name="company"  class="form-control" v-model="selectedCompany" >
                          <option value="consolidado">Reporte Consolidado</option>
                          <option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-3">


                      <div  v-if="selectedCompany == 'consolidado'" class="filter-value-container  form-inline"  >
                        <label>Vista en Reporte</label>
                        <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
                          <option value="consolidado">Agrupados en Misma Lista</option>
                          <option value="separado">Separados por Compañia</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="filter-value-container ">
                        <label>Mostrar Montos</label>
                        <select name="montos" class="form-control" v-model="montos">
                          <option value="todos">Todos los Montos</option>
                          <option value="cheques">Sólo Cheques</option>
                          <option value="futuristas">Sólo Cheques Futuristas</option>
                          <option value="efectivo">Sólo Efectivo</option>
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

                <button v-if="!showPrint" @click="showPrint = true" style="margin-left: 5px"   class="btn btn-success pull-right">
                  <i class="fa fa-print fa-fw"></i>
                  Imprimir
                </button>
              </div>
            </div>
          </div>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body ">
            <div class="row">
              <div class="col-xs-12">
                <div class="col-sm-4">
                  <div class="form-group">
                    <label>Tipos de Reportes</label>
                    <div class="filter-value-container" style="margin-top:3px;">
                      <select @change="generarClicked()" name="tipo" class="form-control" v-model="tipo">
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
                        <input autocomplete="off" type="text" class=" form-control" id="date-input" name="rango">
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
            </div>
            <div class="row" style="background: antiquewhite;">
              <div class="col-sm-2">
                <span class="total-header">Total Transacciones:</span>{{ totales.cobros }}

              </div>
              <div class="col-sm-2">
                <span class="total-header">Total Efectivo:</span> RD${{formatMoney(totales.efectivo)}}

              </div>
              <div class="col-sm-3">
                <span class="total-header">Total Cheques:</span> RD${{formatMoney(totales.cheques)}}

              </div>
              <div class="col-sm-3">
                <span class="total-header">Total Cheques Futuristas:</span> RD${{formatMoney(totales.futuristas)}}

              </div>
              <div class="col-sm-2">
                <span class="total-header">Total General:</span> RD${{formatMoney(totales.total)}}

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
          <div class="main-box-body">
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
  const mapping = {
    Efectivo: 'EFECTIVO',
    Cheques: 'CHEQUES',
    Cobrador: 'collector_code',
    customer: 'customer_code',
    collector: 'collector_code',
    'Cheques Futuristas': 'CHEQUEFUT',
    Total: 'TOTAL'
  }

  export default {


    data () {
      return {
        currentSorting: {
          field: null,
          order: null
        },
        totales: {
          efectivo: 0,
          cheques: 0,
          futuristas:0,
          total: 0,
          cobros: 0
        },
        showPrint: false,
        orden: 'collector_code',
        orden_method: 'asc',
        tipo: 'detallado',
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        cobradores: collectorsList,
        companies: companyList,
        cobrador: 'todos',
        montos: 'todos',
        selectedCompany: 'consolidado',
        consolidado_group: 'consolidado',
        loading: false
      }
    },
    methods: {
      generarClicked() {
        this.fetchReportData();
      },

      fetchReportData () {
        this.loading =true;
        $.ajax({
          method: 'POST',
          url: '/reports/cobros',
          data: {
            order_by: this.orden,
            order_method : this.orden_method,
            tipo: this.tipo,
            rango: this.rango,
            company: this.selectedCompany,
            consolidado_group: this.consolidado_group,
            cobrador: this.cobrador,
            montos: this.montos
          },
          success:  data => {

            if (!(data instanceof Object)) {
              data = JSON.parse(data);
            }

            this.loading =false;
            this.updateTable( data, this.tipo, this.consolidado_group);
          }

        });
      },
      formatMoney: formatMoney,
      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },
      updateTable (data, tipo, agrupacion) {
        let result = null;
        let realdata = null;
        if (tipo === 'detallado') {
          realdata = data;
          result = this.loadDetallado(realdata, agrupacion)
        } else {
          realdata = data.data
          result = this.loadResumido(realdata, agrupacion)
        }

        this.totales = this.calcularTotales(tipo, realdata)
        this.updateTable2(result.options,result.cantidad)
      },
      updateTable2 (options, cantidad) {
        this.cantidad = cantidad;
        const grid =  $("#jsGrid");
        grid.jsGrid("destroy");
        grid.jsGrid(
                {...options,
                  width: '100%',
                  onRefreshed: () => {
                    const sorting = grid.jsGrid("getSorting");
                    this.setSortingParams(sorting)
                  },

                }
        );
        grid.jsGrid("option", "filtering", false);
      },

      calcularTotales (tipo, data) {

        const totales = {
          efectivo: 0,
          cheques: 0,
          futuristas:0,
          total: 0,
          cobros: 0
        }
        if (tipo === 'detallado') {
          totales.cobros = data.length;
          data.forEach(val => {
            totales.efectivo += moneyToNumber(val.cash_amount);
            totales.cheques += moneyToNumber(val.check_amount);
            totales.futuristas += moneyToNumber(val.futuristic_check_amount);
            totales.total += moneyToNumber(val.receipt_income_amount);
          })
        } else {
          data.forEach(val => {
            totales.efectivo += moneyToNumber(val.Efectivo);
            totales.cheques += moneyToNumber(val.Cheques);
            totales.cobros += parseInt(val.cantidad)
            totales.futuristas += moneyToNumber(val['Cheques Futuristas']);
            totales.total += moneyToNumber(val.Total);
          })
        }
        return totales;

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
                        val.receipt_income_date_time.toLowerCase().includes(filter.receipt_income_date_time.toLowerCase()) &&
                        // val.receipt_income_reference.toLowerCase().includes(filter.receipt_income_reference .toLowerCase()) &&
                        val.receipt_code.toLowerCase().includes(filter.receipt_code.toLowerCase())
              })
            }
          },

          fields: [

            { name: "Compañia", type: "date", width: 60,  filtering: true, visible: agrupacion === 'separado'},

            { name: "receipt_income_date_time",title:'Fecha', type: "text", width: 80,  filtering: true,
              itemTemplate:function (item) {
                return moment(item).format('DD-MM-YYYY h:mm A')
              }},

            { name: "collector",title:'Cob.', type: "text", width: 100,  filtering: true},

            { name: "customer",title:'Clientes', type: "text", width: 160,  filtering: true},

            // { name: "receipt_income_reference",title:'No. Recibo', type: "text", width: 50,  filtering: true, sorter: "money"},

            { name: "receipt_code",title:'Código ERP', type: "text", width: 80,  filtering: true},
            { name: "receipt_income_reference",title:'No. Recibo', type: "text", width: 80,  filtering: true,},

            { name: "cash_amount", type: "text", width: 80, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Efectivo')},

            { name:'check_amount', type: "text", width: 80, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Cheques') },

            { name:'futuristic_check_amount', type: "text", width: 80, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Cheques Fut.') },

            { name: "discount_amount", type: "text", width: 80, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Descuentos')
            },

            { name: "receipt_income_amount", type: "text", width: 80, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Total')
            },



            { name: "in_location", type: "text", width: 60, align: "right", sorting: false,  headerTemplate: function () {
                return '<i style="font-size: 16px" class="fa fa-map-marker"></i>';

              }
            },
            { name: "view_location", type: "view_location", width: 80, align: "right", sorting: false,
              title: 'Ver Loc.'
            },
            {
              title: 'Detalle', name: 'receipt_income_code', type: 'receipt_detail', width: 80, filtering: false, sorting: false
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

      loadResumido (data) {

        let options = {}
        let cantidad = 0;
        cantidad = data.length
        options =  {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
//              const toSearch = filter.Cobrador.toLowerCase().split('&&');
//              console.log(toSearch)
//              return data.filter(val => {
//                for (let i=0;i<toSearch.length;i++) {
//                  if (val.Cobrador.toLowerCase().includes(toSearch[i])){
//                    return true
//                  }
//                }
//              })
              return data.filter(val => val.Cobrador.toLowerCase().includes(filter.Cobrador.toLowerCase()))
            }
          },
          fields: [
            { name: "Cobrador", type: "text", width: 200,  filtering: true,

            },
            { name: "cantidad",title:'Cantidad', type: "text", width: 50,  filtering: false},

            { name: "Efectivo", type: "text", width: 100, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Efectivo')},
            { name: "Cheques", type: "text", width: 100, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Cheques') },
            { name: "Cheques Futuristas", type: "text", width: 100, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Cheques Futuristas')},
            { name: "Total", type: "text", width: 100, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Total')
            }, { type: "control" ,  headerTemplate: function() {
                return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, false);
              },     searchButtonTooltip: "Buscar",  clearFilterButtonTooltip: "Limpiar Filtros",
              inserting:false,editing:false,filtering:true,editButton: false,deleteButton:false
            }
          ]
        }
        return {options,cantidad}
      }

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