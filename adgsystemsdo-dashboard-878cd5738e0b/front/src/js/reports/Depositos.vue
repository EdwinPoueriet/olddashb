<template>
  <div class="row" style="padding: 20px">
    <div class="col-sm-12">
      <form :action="'/legacy/print/depositos'+tipo+''" method="post" target="_blank">
        <input type="hidden"  name="order_by" v-model="orden" >
        <input type="hidden"  name="order_method" v-model="orden_method" >

        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label>Tipo de Reporte</label>
              <div class="filter-value-container" style="margin-top:3px;">
                <select name="tipo" class="form-control" v-model="tipo">
                  <option value="compacto">Resumido</option>
                  <option value="detallado">Detallado</option>
                </select>
              </div>
            </div>

          </div>
          <div class="col-xs-4">
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
          <div class="col-xs-4">
            <div class="form-group">
              <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
              </div>
              <label>Vendedor</label>
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



        <div class="row" v-if="showPrintOptions" style=" background-color: #ebedee; border-radius: 10px; margin-top: 5px;padding: 10px">
          <h4>Opciones de Impresión</h4>
          <div class="row">
            <div class="col-xs-4">
              <div class="form-group">
                <label>Filtrar Compañia</label>
                <div class="filter-value-container" style="margin-top:3px;">
                  <select name="company" class="form-control" v-model="selectedCompany" >
                    <option value="consolidado">Reporte Consolidado</option>
                    <option v-for="company in companies" :value="company.company_id">{{ company.company_id }} - {{ company.company_name }}</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-xs-4">
              <div class="form-group" v-if="selectedCompany == 'consolidado'">
                <label>Vista en Reporte</label>
                <div class="filter-value-container" style="margin-top:3px;">
                  <select name="consolidado_group" class="form-control" v-model="consolidado_group" >
                    <option value="consolidado">Agrupados en Misma Lista</option>
                    <option value="separado">Separados por Compañia</option>
                  </select>
                </div>
              </div>

            </div>

          </div>
          <div class="row" >
            <div class="col-xs-12">
              <button type="submit" class="btn btn-success"><i class="fa fa-print fa-fw"></i> Imprimir</button>
              <button type="submit" class="btn btn-default" @click="showPrintOptions = false">Volver</button>
            </div>
          </div>


        </div>

      </form>
    </div>

  </div>
</template>

<script>
  import { EventBus } from './bus';

  import {sortNumber} from '../common'
  const mapping = {
    vendedor: 'seller_code',
    cantidad: 'COUNT',
    Total: 'TOTAL'
  }
  export default {
    props: ['sorting'],
    data () {
      return {
        showPrintOptions: false,
        hideGenerar: false,
          showInactiveSellers: false,
          vendedores_inactivos: sellersListInactive,
        orden: 'seller_code',
        orden_method: 'asc',
        tipo: 'compacto',
        companies: companyList,
        vendedores: sellersList,
        rango: moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        consolidado_group: 'consolidado',
        selectedCompany: 'consolidado',
        vendedor: 'todos'
      }
    },
    created() {
      EventBus.$on('showPrintOptions', () => {

        this.showPrintOptions = !this.showPrintOptions;

      });
    },
    methods: {
      generarClicked() {
        this.fetchReportData();
      },
      fetchReportData () {
        this.$emit('loading', true)
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
            this.$emit('loading', false)
            this.$emit('updateTableData', data, this.tipo, this.consolidado_group)

          }
        });
      }
    },
    watch: {
      sorting (value) {
        if (value && value.field && value.order) {
          this.orden_method = value.order;
          if (mapping[value.field])
            this.orden = mapping[value.field];
          else
            this.orden = value.field;
        }
      },
      tipo (val) {
        if(val === 'compacto'){
          this.hideGenerar = false;
          this.orden = 'seller_code';
          this.$emit('disableTable',false)
        }else{
          this.hideGenerar = true;
          this.$emit('disableTable',true)
          this.orden = 'date';
        }
        this.generarClicked();
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
        this.generarClicked();
      });
      this.generarClicked()

    }
  }
</script>