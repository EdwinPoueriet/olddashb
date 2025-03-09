<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box" >
                    <div class="main-box-header clearfix"  >
                        <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Filtros</h2>
                        <form action="/legacy/print/noVentas" method="POST" target="_blank">
                            <input type="hidden" :value="selectedSeller" name="sellers">
                            <input type="hidden" :value="selectedCustomer" name="customer">
                            <input type="hidden" :value="rango" name="rango">
                            <button  type="submit" class="btn btn-success pull-right" style="margin-left: 5px;"><i  class="fa fa-print fa-fw"></i>
                                Imprimir Reporte
                            </button>
                        </form>
                    </div>
                    <hr style="margin-top:0; margin-bottom: 5px">
                    <div class="main-box-body ">

                        <div class="row" style="padding: 10px">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Rango de Fecha</label>
                                    <div class="filter-range-container" style="margin-top:3px;" >
                                        <div class="input-group" >
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input  type="text" class="form-control" id="date-input" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                                        <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                                    </div>
                                    <label>Relacionado vendedor</label>
                                    <div class="filter-value-container" style="margin-top:3px;">
                                        <select name="vendedor" @change="getData()"  class="form-control" v-model="selectedSeller" >
                                            <option value="0">Todos</option>

                                            <option v-for="vendedor in vendedores"
                                                    :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Relacionado a Cliente</label>
                                    <div class="filter-value-container" style="margin-top:3px;">
                                        <select @change="getData()" style="margin-top: 3px;" v-model="selectedCustomer" class="form-control" >
                                            <option value="0">Todos</option>
                                            <option v-for="item in customers" :value="item.customer_code">{{ item.customer_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row" style="text-align: right">
                  <span  style="    background: antiquewhite;">
                  <span class="total-header">Total Visitas No Ventas:</span> {{ total }}
                  </span>
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

<script>

  import {baseOptions, totalRow} from '../grid'
  import {sortNumber, moneyToNumber, headerRight ,formatMoney} from './../../common'
  require('../gridsetup')

  export default {
    name: "Visit",
    data(){
      return {
        currentSorting: {
          field: null,
          order: null

        },
        selectedSeller: '0',
        selectedCustomer: '0',
        vendedores_inactivos: sellersList,
        vendedores: sellersList,
        customers: customers,
        rango:  moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        showInactiveSellers: false,
        loading: false,
        total: 0,


      }
    },
    methods: {
      setSortingParams (sorting) {
        this.currentSorting = sorting;
      },
      getData(){
        this.loading = true;
        $.ajax({
          url: '/reports/visitas/no/ventas',
          method: 'POST',
          data: {
            "sellers": this.selectedSeller,
            "customer": this.selectedCustomer,
            "rango": this.rango
          },
          success: (data)=> {
            if (!(data instanceof Object)) {
              data = JSON.parse(data);
            }
            this.setData(data);
            this.total = data.length
            // console.log(data);
          },
          complete: ()=>{
            this.loading = false;
          }
        });

      },
      setData(data) {
        let result = null;
        let realdata = null;

        realdata = data;
        result = this.loadDetallado(realdata)

        // this.totales = this.calcularTotales(realdata)
        this.updateTable(result.options,result.cantidad)

      },
      updateTable (options, cantidad) {
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
                  val.customer.toLowerCase().includes(filter.customer.toLowerCase())

              })
            }
          },

          fields: [

            // { name: "Compañia", type: "date", width: 60,  filtering: true, visible: agrupacion === 'separado'},

            { name: "visit_date_time",title:'Fecha', type: "text", width: 90,  filtering: true,
              itemTemplate:function (item) {
                return moment(item).format('DD-MM-YYYY h:mm A')
              }},
            { name: "visit_id",title:'ID Transacción', type: "text", width: 80,  filtering: true},
            { name: "seller",title:'Vendedor', type: "text", width: 160,  filtering: true},

            { name: "customer",title:'Cliente', type: "text", width: 180,  filtering: true},


            // {
            //   title: 'Transacción', name: 'transaction_name',type: "text", width: 60, filtering: true
            // },
            {
              title: 'Nota', name: 'visit_note',type: "text", width: 60, filtering: true
            },
            {
              title: 'Razón',  name: 'visit_type_name',type: "text", width: 60, filtering: true
            },
            { name: "in_location", type: "text", width: 50, align: "right", sorting: false,
              headerTemplate: function () {
                return '<i style="font-size: 16px" class="fa fa-map-marker"></i>';

              }
            },
            { name: "view_location", type: "view_location", width: 50, align: "right", sorting: false,
              title: 'Ver loc.'
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
          this.getData();

        });
      }
    },
    mounted () {
      this.getData();
      this.setupBasico();

    }
  }
</script>

<style scoped>
    .total-header {
        font-weight: 600;
    }

</style>
