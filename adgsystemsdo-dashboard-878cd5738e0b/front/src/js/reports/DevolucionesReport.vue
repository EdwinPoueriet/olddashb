<template>
  <div>

    <div class="row">
      <div class="col-xs-12">
        <div class="main-box" style="">
          <div class="main-box-header clearfix"  >
            <div class="row">
              <div class="col-sm-2">
                <h2 style="color: #03a9f4; font-weight: 500" >Filtros</h2>
              </div>
              <div class="col-sm-10">
                <form :action="'/legacy/print/devolucionesdetallado'" method="post" target="_blank">
                  <template  v-if="showPrint" >

                    <input type="hidden" :value="rango" name="rango">
                    <input type="hidden" :value="vendedor" name="vendedor">
                    <div class="col-sm-5">
                      <div class="filter-value-container form-inline" >
                        <label>Filtrar Compañia</label>
                        <select name="company"  class="form-control" v-model="selectedCompany" >
                          <option value="consolidado">Reporte Consolidado</option>
                          <option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-sm-5">
                      <div  class="filter-value-container form-inline" >
                        <label>Vista en Reporte</label>
                        <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
                          <option value="0">Sin agrupación</option>
                          <option value="vendedor">Agrupado por vendedor</option>
                          <!--<option value="separado"></option>-->
                        </select>
                      </div>
                      <i @click="showPrint = false" style="position: absolute; right: 10px; top: 10px;" class="fa fa-eye-slash"></i>
                    </div>
                    <div class="col-sm-2">

                      <button  type="submit"  class="btn btn-success pull-right">
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
                <div class="row" style="text-align: right">
                  <span  style="    background: antiquewhite;">
                  <span class="total-header">Total General:</span>  RD${{ formatMoney(totales.total) }}
                  </span>
                </div>
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
  // import devoluciones from './Devoluciones.vue'
  import {baseOptions, totalRow} from './grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../common'
  require('./gridsetup')

  export default {
    // components: {wrapper, devoluciones},
    data () {
      return {
        hideTableOp: false,
        showPrint: false,
        loading: false,
        orden: 'order_date',
        orden_method: 'desc',
        showInactiveSellers: false,
        vendedores_inactivos: sellersListInactive,
        vendedores: sellersList,
        companies: companyList,
        consolidado_group:'0',
        selectedCompany: 'consolidado',
        vendedor: 'todos',
        tipo: 'detallado',
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        currentSorting: {
          field: null,
          order: null
        },

        totales :{
          total: 0
        }

      }
    },
    watch : {
      currentSorting (value) {
        if (value && value.field && value.order) {
          this.orden_method = value.order;
          if (mapping[value.field])
            this.orden = mapping[value.field];
          else
            this.orden = value.field;
        }
      },
    },
    methods: {

      generarClicked() {
        this.fetchReportData();

      },

      fetchReportData() {
        this.loading = true;

        this.$emit('loading', true)
        $.ajax({
          method: 'POST',
          url: '/reports/devoluciones',
          data: {
            order_by: this.orden,
            order_method: this.orden_method,
            tipo: this.tipo,
            rango: this.rango,
            company: this.selectedCompany,
            consolidado_group: this.consolidado_group,
            vendedor: this.vendedor
          },
          success: data => {

            if (!(data instanceof Object)) {
              data = JSON.parse(data);
            }
            this.updateTable(data, this.consolidado_group);

          },
          complete: () => {
            this.loading = false;

          },

        });
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
                    this.$emit('sortingChanged',sorting)
                  },

                }
        );
        grid.jsGrid("option", "filtering", false);
      },

      hideTable(val) {
        this.hideTableOp = val;
      },
      formatMoney: formatMoney,

      setSortingParams(sorting) {
        this.currentSorting = sorting;
      },

      updateTable(data, agrupacion) {

        const result = this.loadDetallado(data, agrupacion)
        this.updateTable2(result.options, result.cantidad)
        this.totales = this.calcularTotales(data)

      },

      loadDetallado(data, agrupacion) {
        let options = {}
        let cantidad = 0;

        cantidad = data.length
        options = {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
              return data.filter(val => {
                return val.seller.toLowerCase().includes(filter.seller.toLowerCase()) &&
                        val.customer.toLowerCase().includes(filter.customer.toLowerCase()) &&
                        val.return_id.toLowerCase().includes(filter.return_id.toLowerCase()) &&
                        val.date.toLowerCase().includes(filter.date.toLowerCase())
              })
            }
          },

          fields: [
            {name: "return_id", title: 'No. Devol.', type: "text", width: 50, filtering: true, sorter: "money"},

            {name: "Compañia", type: "date", width: 60, filtering: true, visible: agrupacion === 'separado'},

            {
              name: "date", title: 'Fecha', type: "text", width: 60, filtering: true,
              itemTemplate: function (item) {

                if (moment(item, 'YYYY-MM-DD H:mm:ss').format('YYYY-MM-DD H:mm:ss') === item) {
                  return moment(item).format('DD-MM-YYYY h:mm A')

                }
                return moment(item).format('DD-MM-YYYY')

                // return moment(item).format('DD-MM-YYYY h:mm A')
              }
            },
            {name: "seller", title: 'Vendedor', type: "text", width: 150, filtering: true},

            {name: "customer", title: 'Cliente', type: "text", width: 180, filtering: true},


            {name: "invoice_code", title: 'Código Factura.', type: "text", width: 70, filtering: true, sorter: "money"},

            {name: "reason", title: 'Razón.', type: "text", width: 100, sorting: false, filtering: false},

            {
              name: "Total", type: "text", width: 60, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Total')
            },
            {
              title: 'Detalle', name: 'return_id', type: 'return_detail', width: 60, filtering: false, sorting: false
            },
            {
              type: "control", headerTemplate: function () {
                return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, false);
              }, searchButtonTooltip: "Buscar", clearFilterButtonTooltip: "Limpiar Filtros",
              inserting: false, editing: false, filtering: true, editButton: false, deleteButton: false
            }
          ]
        }
        return {options, cantidad}
      },

      calcularTotales(data) {

        const totales = {
          total: 0
        }
        data.forEach(val => {
          totales.total += moneyToNumber(val.Total);
        })

        return totales;

      },

    },
    mounted() {
      const date = $('#date-input');
      date.daterangepicker({
        "applyClass": "btn-primary",
        startDate: moment(),
        ranges: {
          'Hoy': [moment(), moment()],
          'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Ultimos 7 dias': [moment().subtract(6, 'days'), moment()],
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