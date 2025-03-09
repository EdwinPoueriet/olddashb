<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box" style="padding-top: 20px">
                    <div class="main-box-header clearfix"  >
                        <div class="row">
                            <div class="col-sm-2">
                                <h2 style="color: #03a9f4; font-weight: 500">Filtros</h2>
                            </div>
                            <div class="col-sm-10">
                                <form :action="'/legacy/print/devolucionesdetallado'" method="post" target="_blank">
                                    <template  v-if="showPrint"  >
                                        <!--<i @click="showPrint = false" class="fa fa-eye-slash"></i>-->
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
                        <div class="row" style="padding: 20px">
                            <div class="col-xs-12">


                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label>Tipos de Reportes</label>
                                            <div class="filter-value-container" style="margin-top:3px;">
                                                <select @change="generarClicked()" name="tipo" class="form-control" v-model="tipo">
                                                    <option value="detallado">Detallado</option>
                                                    <option value="resumido">Resumido</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
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
                                    <div class="col-sm-3">
                                        <div class="form-group" >
                                            <label>Rango de Fecha de Vnc</label> <input type="checkbox"  v-model="isFacVnc" name="isFacVnc" >
                                            <div class="filter-range-container" style="margin-top:3px;" >
                                                <div class="input-group" >
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                    <input autocomplete="off"  type="text" class="form-control"  id="date" :disabled="!isFacVnc" name="rango_vnc">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
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
                  <span class="total-header">Total General:</span> {{ formatMoney(totales.total) }}
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
    .jsgrid-cell {
        overflow: hidden;
    }

</style>
<script>

  import {baseOptions, totalRow} from './grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../common'
  require('./gridsetup')

  export default {
    // components: {wrapper, cuentacobrar: Cuentacobrar},
    data () {
      return {
        showInactiveSellers: false,
        tipo: 'detallado',
        showPrint: false,
        loading: false,
        vendedores_inactivos: sellersListInactive,
        currentSorting: {
          field: null,
          order: null

        },
        vendedores: sellersList,
        isFac: true,
        isFacVnc: false,
        hideTableOp: false,
        totales :{
          total: 0
        },
        companies: companyList,
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        rango_vnc: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        selectedCompany: 'consolidado',
        consolidado_group:'consolidado',
        vendedor: 'todos'
      }
    },
    methods: {
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

      generarClicked() {
        this.fetchReportData();
      },
      fetchReportData () {
        if(!this.isFac && !this.isFacVnc){
          toastr.warning("Seleccioné un rango de fecha.")
          return
        }
        this.loading = true;
        $.ajax({
          method: 'POST',
          url: '/reports/cuentacobrar',
          data: {
            order_by: this.orden,
            order_method : this.orden_method,
            tipo: this.tipo,
            rango:  this.isFac ? this.rango : null,
            rango_vnc: this.isFacVnc ? this.rango_vnc : null,
            company: this.selectedCompany,
            consolidado_group: this.consolidado_group,
            vendedor: this.vendedor,
            // primera: primera,
          },
          success:  data => {

            if (!(data instanceof Object)) {
              data = JSON.parse(data);

            }
            // this.$emit('loading', false)
            this.updateTable(data, this.tipo, this.consolidado_group)
          },
          complete: ()=> {
            this.loading = false;
          }
        });
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

        let datevnc = $('#date');
        datevnc.daterangepicker({
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
        datevnc.on('apply.daterangepicker', (ev, picker) => {
          this.rango_vnc = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
          this.generarClicked();
        });
      },

      hideTable (val) {
        this.hideTableOp = val;
      },
      formatMoney: formatMoney,

      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },

      updateTable (data, type, agrupacion) {
        let result = null;
        let realdata = null;

        if(type == "detallado"){
          realdata = data;
          result = this.loadDetallado(realdata, agrupacion)

        }
        if(type == "resumido"){

          realdata = data.data
          result = this.loadResumido(realdata, agrupacion)

        }

        this.totales = this.calcularTotales(type, realdata)
        this.updateTable2(result.options,result.cantidad)


      },

      loadResumido (data) {
        console.log(data)
        let options = {}
        let cantidad = 0;
        cantidad = data.length
        options =  {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
              return data.filter(val => val.Vendedor.toLowerCase().includes(filter.Vendedor.toLowerCase()))
            }
          },
          fields: [
            { name: "Vendedor", type: "text", width: 200,  filtering: true,

            },

            {  name: "CuentasPorCobrar",title:'Cuentas Por Cobrar.', type: "text", width: 100, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Cuentas Por Cobrar')},
            { name: "Total", type: "text", width: 100, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Total') },
            { type: "control" ,  headerTemplate: function() {
                return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, false);
              },     searchButtonTooltip: "Buscar",  clearFilterButtonTooltip: "Limpiar Filtros",
              inserting:false,editing:false,filtering:true,editButton: false,deleteButton:false
            }
          ]
        }
        return {options,cantidad}
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
                return  val.seller.toLowerCase().includes(filter.seller.toLowerCase()) &&
                  val.customer.toLowerCase().includes(filter.customer.toLowerCase()) &&
                  val.invoice_code.toLowerCase().includes(filter.invoice_code.toLowerCase())
              })
            }
          },

          fields: [

            { name: "Compañia", type: "text", width: 60,  filtering: true, visible: agrupacion === 'separado'},

            { name: "invoice_date",title:'Fecha', type: "text", width: 90,  filtering: true,
              itemTemplate:function (item) {
                return moment(item).format('DD-MM-YYYY')
              }
            },

            { name: "invoice_expiration_date",title:'Fecha Vnc.', type: "text", width: 90,  filtering: true,
              itemTemplate:function (item) {
                return moment(item).format('DD-MM-YYYY')
              }
            },

            { name: "seller",title:'Vendedor', type: "text", width: 150,  filtering: true},

            { name: "customer",title:'Cliente', type: "text", width: 180,  filtering: true},

            { name: "invoice_code",title:'No. Orden', type: "text", width: 60,  filtering: true, sorter: "money"},

            { name: "invoice_balance", type: "text", width: 60, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Bruto')},

            { type: "control" ,  headerTemplate: function() {
                return this._createOnOffSwitchButton("filtering", this.searchModeButtonClass, false);
              },     searchButtonTooltip: "Buscar",  clearFilterButtonTooltip: "Limpiar Filtros",
              inserting:false,editing:false,filtering:true,editButton: false,deleteButton:false
            }
          ]
        }
        return {options,cantidad}
      },

      calcularTotales (type, data) {

        const totales = {
          total: 0
        }

        if(type == "resumido"){
          data.forEach(val => {
            totales.total += moneyToNumber(val.Total);
          })

        }else{
          data.forEach(val => {
            totales.total += moneyToNumber(val.invoice_balance);
          })

        }



        return totales;

      },

    },
    mounted () {
      this.setupBasico();
      $('.boxi').matchHeight();
      this.generarClicked(true)
    }
  }
</script>