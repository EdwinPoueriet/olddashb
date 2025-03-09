<template>
  <div>
    <div class="row">
      <div class=" col-lg-4 col-md-12">
        <div class="main-box" >
          <div class="box-header">
            <span class="title">Tipo de Reporte</span>
          </div>
          <div class="main-box-body boxi" style="padding: 20px; padding-right: 35px;padding-left: 35px">
            <vertical-menu
                default='cobros'
                :items="menuItems"
                @selected="handleMenuSelected">
            </vertical-menu>
          </div>
        </div>
      </div>
      <div class="col-lg-8 col-md-12">
        <div class="main-box" >
          <div class="box-header">
            <span class="title">Construir Reporte</span>
          </div>
          <div class="main-box-body boxi ">
            <div v-if="currentReport == ''">
              <div class="alert alert-info fade in" style="margin-top: 20px">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <i class="fa fa-info-circle fa-fw fa-lg"></i>
                <strong>Nota</strong>
                Seleccione el tipo de reporte que quiere generar utilizando el menú del panel izquierdo.
              </div>
            </div>
            <div v-if="currentReport == 'cobros'">
              <cobros @updateTableData="updateTable" @loading="loadingEvent"></cobros>
            </div>
            <div v-if="currentReport == 'cobros_adelanto'">
              <cobros-adelanto @updateTableData="updateTable" @loading="loadingEvent"></cobros-adelanto>
            </div>
            <div v-if="currentReport == 'ventas'">
              <ventas @disableTable="disableTheTable"  @updateTableData="updateTable" @loading="loadingEvent"></ventas>
            </div>
            <div v-if="currentReport == 'depositos'">
              <depositos @disableTable="disableTheTable" @updateTableData="updateTable" @loading="loadingEvent"></depositos>
            </div>
            <div v-if="currentReport == 'devoluciones'">
              <devoluciones @updateTableData="updateTable" @loading="loadingEvent"></devoluciones>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="!tableDisabled">
      <div class="col-xs-12">
        <div class="main-box" >
          <div class="box-header">
            <span class="title">Reporte de {{currentItem.text}}</span>
          </div>
          <div class="main-box-body tablewrapper" style="padding-top: 25px" >
            <template v-if="tableData.data && tableData.data.length > 0 && !loading">
              <v-client-table :data="tableData.data" :columns="tableData.columns" :options="tableData.options">
                <template slot="status" scope="props">
                    <span v-if="props.row.status == '1'"><i class="fa fa-check"></i></span>
                  </template>
                  <template slot="cash_amount" scope="props">
                    <div style="text-align: right">
                      {{props.row['cash_amount']}}
                    </div>
                  </template>
                  <template  slot="check_amount" scope="props">
                    <div style="text-align: right">
                      {{props.row['check_amount']}}
                    </div>
                  </template>
                  <template  slot="receipt_income_amount" scope="props">
                    <div style="text-align: right">
                      {{props.row['receipt_income_amount']}}
                    </div>
                  </template>

                  <template  slot="Efectivo" scope="props">
                    <div style="text-align: right">
                      {{props.row.Efectivo}}
                    </div>
                  </template>
                  <template  slot="Cheques" scope="props">
                    <div style="text-align: right">
                      {{props.row.Cheques}}
                    </div>
                  </template>
                  <template  slot="Cheques Futuristas" scope="props">
                    <div style="text-align: right">
                      {{props.row['Cheques Futuristas']}}
                    </div>
                  </template>
                  <template  slot="Total" scope="props">
                    <div style="text-align: right">
                      {{props.row['Total']}}
                    </div>
                  </template>

                <template  slot="total" scope="props">
                  <div style="text-align: right">
                    {{props.row['total']}}
                  </div>
                </template>
                <template  slot="order_gross_amount" scope="props">
                  <div style="text-align: right">
                    {{props.row.order_gross_amount}}
                  </div>
                </template>
                <template  slot="order_discount_amount" scope="props">
                  <div style="text-align: right">
                    {{props.row.order_discount_amount}}
                  </div>
                </template>
                <template  slot="order_tax_amount" scope="props">
                  <div style="text-align: right">
                    {{props.row.order_tax_amount}}
                  </div>
                </template>
              </v-client-table>
            </template>
            <template v-else>
              <div style="text-align: center" v-if="!loading">
                No hay resultados para los criterios especificados
              </div>
            </template>
            <template v-if="loading">
              <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i> </div>
            </template>
          </div>
        </div>
      </div>

    </div>

  </div>

</template>

<script>
  import Cobros from './Cobros'
  import Ventas from './Ventas'
  import CobrosAdelanto from './CobrosAdelanto'
  import Devoluciones from './Devoluciones'
  import Depositos from './Depositos'
  import Vue from 'vue'

  export default  {
    name: 'report',
    data () {
      return {
        currentReport: 'cobros',
        loading:false,
        tableDisabled:false,
        menuItems: [
          {name: 'cobros', text: 'Cobros', icon: 'fa-money'},
          {name: 'cobros_adelanto', text: 'Cobros de Adelanto', icon: 'fa-money'},
          {name: 'ventas', text: 'Ventas', icon: 'fa-shopping-cart'},
          {name: 'depositos', text: 'Depósitos de Factura', icon: 'fa-credit-card'},
          {name: 'devoluciones', text: 'Devoluciones', icon: 'fa-reply'}
        ],
        tableData: {
          columns: [],
          data: [],
          options: {

          }
        }
      }
    },
    computed: {
      currentItem () {
        return this.menuItems.find(item => item.name === this.currentReport )
      }
    },
    methods: {
      disableTheTable (val) {
        this.tableDisabled = val;
      },
      loadingEvent (val) {
        this.loading = val;
      },
      updateTable (data) {

        this.clearTable();
        this.tableData.columns = data['columns'];
        this.tableData.data= data['data'];
        if (data['sorting']) {
          this.tableData.options.customSorting = data['sorting']
        }
        if (data['sortable']) {
          this.tableData.options.sortable = data['sortable'];
        }
        if (data['dateColumns']) {
          this.tableData.options.dateColumns = data['dateColumns'];
        }
        if (data['dateFormat']) {
          this.tableData.options.dateFormat = data['dateFormat'];
        }
        if (data['toMomentFormat']) {
          this.tableData.options.toMomentFormat = data['toMomentFormat'];
        }
        if (data['headings']) {
          this.tableData.options.headings = data['headings']
        }
        if (data['filterable']) {
          this.tableData.options.filterable = data['filterable'];
        }

      },
      clearTable () {
        this.tableData.columns = [];
        this.tableData.data = [];
        this.tableData.options = {
          texts:{
            count:'Mostrando desde {from} hasta {to} de {count} registros|{count} registros|Un registro',
            filter:'Filtrar resultados:  ',
            filterPlaceholder:'Parametro de busqueda',
            limit:'Registros:',
            noResults:'No hay registros que cumplan con el criterio especficiado.',
            page:'Pagina:', // for dropdown pagination
            filterBy: 'Filtrar por {column}', // Placeholder for search fields when filtering by column
            defaultOption:'Seleccionar {column}' // default option for list filters
          }
        }
      },
      handleMenuSelected (sel) {
        this.tableDisabled = false;
        this.clearTable();
        this.currentReport = sel;

      },
    },
    components: {Cobros,CobrosAdelanto,Ventas,Devoluciones,Depositos}
  }

</script>

<style>

  .tablewrapper td {
    font-size: 12px !important;
    font-weight: 500 !important;
  }

  .box-header{
    background-color: #2C3E50;
    color: white;
    padding: 14px;
    text-align: center;
  }

  .box-header > .title {
    font-weight: 600;
    font-size: 17px;
  }
</style>
