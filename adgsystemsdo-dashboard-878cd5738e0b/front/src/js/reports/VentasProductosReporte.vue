<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <form :action="'/legacy/print/ventasporproductos'" method="post" target="_blank">
                    <div class="main-box" >
                        <div class="main-box-header clearfix">
                            <div class="row">
                                <div class="col-sm-2">
                                    <h2 style="color: #03a9f4; font-weight: 500">Filtros</h2>
                                </div>
                                <div class="col-sm-10">
                                    <template  v-if="showPrint"  >
                                        <!--<i @click="showPrint = false" class="fa fa-eye-slash"></i>-->
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


                                            <div  class="filter-value-container form-inline"  >
                                                <label>Vista en Reporte</label>
                                                <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
                                                    <option value="0">Sin agrupación</option>
                                                    <option value="vendedor">Agrupado por vendedor</option>
                                                    <option value="cliente">Agrupado por cliente</option>

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
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                                                <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                                            </div>
                                            <label>Vendedores</label>
                                            <div class="filter-value-container" style="margin-top:3px;">
                                                <select name="seller_code" @change="generarClicked" class="form-control" v-model="vendedor" >
                                                    <option value="Todos">Todos</option>

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
                                    <div class="col-xs-4">
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

                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Clientes</label>
                                            <div class="filter-value-container" style="margin-top:3px;">
                                                <select @change="generarClicked" name="customer_code" class="form-control" v-model="cliente" >
                                                    <option value="Todos">Todos</option>
                                                    <option v-for="item in customers" :value="item.customer_code">{{ item.customer_name }}</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div >
                                <p>
                                    <a class="" data-toggle="collapse" href="#multiCollapseExample1" role="button" aria-expanded="false" aria-controls="multiCollapseExample1">Otros filtros</a>
                                </p>
                                <div class="row">

                                    <div class="collapse multi-collapse" id="multiCollapseExample1">
                                        <div  class="col-xs-12">
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Tipos de Ventas</label>
                                                    <div class="filter-value-container" style="margin-top:3px;">
                                                        <select @change="generarClicked" name="order_type" class="form-control" v-model="tipo">
                                                            <option value="Todos">Todos</option>
                                                            <option value="1">Credito</option>
                                                            <option value="2">Al Contado</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label>Almacénes</label>
                                                    <div class="filter-value-container" style="margin-top:3px;">
                                                        <select @change="generarClicked" name="warehouse" class="form-control"  v-model="almacen" >
                                                            <option value="Todas">Todos</option>
                                                            <option v-for="item in warehouses" :value="item.warehouse_code">{{ item.warehouse_code }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Clasificaciones</label>
                                                    <div class="filter-value-container" style="margin-top:3px;">
                                                        <select @change="generarClicked" name="classification_code" class="form-control" v-model="clasificacion" >
                                                            <option value="Todas">Todas</option>
                                                            <option v-for="item in classifications" :value="item.classification_code">{{ item.classification_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Familia</label>
                                                    <div class="filter-value-container" style="margin-top:3px;">
                                                        <select @change="generarClicked" name="family_code" class="form-control" v-model="familia" >
                                                            <option value="Todas">Todas</option>
                                                            <option  v-for="item in families" :value="item.family_code">{{ item.family_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-xs-4">
                                                <div class="form-group">
                                                    <label>Sub-Clasificaciones</label>
                                                    <div class="filter-value-container" style="margin-top:3px;" >
                                                        <select @change="generarClicked" name="subclassification_code" class="form-control"  v-model="subclasificacion">
                                                            <option value="Todas">Todas</option>
                                                            <option v-for="item in subclassifications" :value="item.subclassification_code">{{ item.subclassification_name }}</option>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label>Marcas</label>
                                                    <div class="filter-value-container" style="margin-top:3px;">
                                                        <select @change="generarClicked" name="brand_id" class="form-control" v-model="marcat">
                                                            <option value="Todas">Todas</option>
                                                            <option v-for="item in brands" :value="item.brand_id">{{ item.brand_name }}</option>
                                                        </select>
                                                    </div>
                                                </div>

                                            </div>

                                        </div>
                                    </div>
                                </div>



                            </div>
                            <table class="table">
                                <tr style="background: antiquewhite;">
                                    <td><span class="total-header">Total Productos:</span></td>
                                    <td><span class="total-value">{{ totales.productos }}</span></td>
                                    <td><span class="total-header">Total Bruto:</span></td>
                                    <td><span class="total-value">RD${{formatMoney(totales.descuento)}}</span></td>
                                    <td><span class="total-header">Total Descuentos:</span></td>
                                    <td><span class="total-value">RD${{formatMoney(totales.descuento)}}</span></td>
                                    <td><span class="total-header">Total Neto:</span></td>
                                    <td><span class="total-value">RD${{formatMoney(totales.neto)}}</span></td>
                                    <td><span class="total-header">Total General:</span></td>
                                    <td><span class="total-value">RD${{formatMoney(totales.bruto)}}</span></td>
                                </tr>
                            </table>
                        </div>


                    </div>
                </form>
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

<script>

  import {baseOptions, totalRow} from './grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../common'

  export default {
    name: "VentasProductosReporte",

    data(){
      return{
        showInactiveSellers: false,
        vendedores_inactivos: sellersListInactive,
        vendedores: sellersList,
        warehouses: warehouses,
        families: families,
        brands: brands,
        classifications: classifications,
        subclassifications: subclassifications,
        customers: customers,

        companies: companyList,
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        showPrint: false,
        loading: false,
        currentSorting: {
          field: null,
          order: null

        },
        count: 0,
        hideTableOp: false,
        totales :{
          productos: 0,
          descuento: 0,
          neto: 0,
          bruto: 0
        },
        selectedCompany: 'consolidado',
        consolidado_group:'0',
        tipo: 'Todos',
        vendedor: 'Todos',
        cliente: 'Todos',
        almacen: 'Todas',
        clasificacion: 'Todas',
        subclasificacion: 'Todas',
        familia: 'Todas',
        marcat: 'Todas'
      }
    },
    methods: {


      generarClicked() {
        this.fetchReportData();
      },
      fetchReportData () {
        this.loading = true;
        $.ajax({
          method: 'POST',
          url: '/reports/ventas/productos',
          data: {
            order_type: this.tipo,
            seller_code: this.vendedor,
            customer_code: this.cliente,
            classification_code : this.clasificacion,
            subclassification_code: this.subclasificacion,
            warehouse: this.almacen,
            rango: this.rango,
            family_code: this.familia,
            brand_id: this.marcat,
            company: this.selectedCompany,
            consolidado_group: 0,
          },
          success:  data => {
            console.log(data);
            if (!(data instanceof Object)) {
              data = JSON.parse(data);
            }
            // this.$emit('loading', false)
            this.updateTable(data);
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
          this.generarClicked()
        });
      },

      hideTable (val) {
        this.hideTableOp = val;
      },
      formatMoney: formatMoney,

      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },

      updateTable (data) {


        let result = null;
        let realdata = null;
        realdata = data;
        result = this.loadDetallado(realdata);


        this.totales = this.calcularTotales(realdata)
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
              this.$emit('sortingChanged',sorting)
            },

          }
        );
        grid.jsGrid("option", "filtering", false);
      },

      loadDetallado (data) {
        console.log(data.length)


        let options = {}
        let cantidad = 0;

        cantidad = data.length
        options =  {
          ...baseOptions,
          controller: {
            loadData: function (filter) {
              return data.filter(val =>  {
                return  val.Referencia.toLowerCase().includes(filter.Referencia.toLowerCase()) &&
                  val.Codigo.toLowerCase().includes(filter.Codigo.toLowerCase()) &&
                  val.Nombre.toLowerCase().includes(filter.Nombre.toLowerCase())
              })
            }
          },

          fields: [


            // { name: "Compañia", type: "date", width: 60,  filtering: true, visible: agrupacion === 'separado'},

            // { name: "order_date_time",title:'Fecha', type: "text", width: 90,  filtering: true,
            //   itemTemplate:function (item) {
            //     return moment(item).format('DD-MM-YYYY h:mm A')
            //   }},

            { name: "Codigo",title:'Codigo', type: "text", width: 60,  filtering: true},

            { name: "Referencia",title:'Referencia', type: "text", width: 60,  filtering: true},

            { name: "Nombre",title:'Nombre', type: "text", width: 120,  filtering: true, sorter: "money"},

            { name: "Cantidad",title:'Cantidad', type: "text" , width: 60,  filtering: true, sorter: "money"},



            { name: "Descuento", type: "text", width: 60, filtering: false, align: "right", sorter: "money",
              headerTemplate: headerRight('Descuento')},

            { name:'Neto', type: "text", width: 60, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Neto') },

            { name:'Bruto', type: "text", width: 60, filtering: false,align: "right", sorter: "money",
              headerTemplate: headerRight('Bruto') },

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
          productos: 0,
          descuento: 0,
          neto: 0,
          bruto: 0
        }


        totales.productos = data.length
        data.forEach(val => {
          totales.bruto += moneyToNumber(val.Bruto);
          totales.descuento += moneyToNumber(val.Descuento);
          totales.neto += moneyToNumber(val.Neto);
        })


        return totales;

      },

    },
    mounted () {
      this.generarClicked();
      this.setupBasico();
      $('.boxi').matchHeight();
      // this.generarClicked()
    }

  }
</script>

<style scoped>
    .total-header {
        font-weight: 600;
    }

</style>