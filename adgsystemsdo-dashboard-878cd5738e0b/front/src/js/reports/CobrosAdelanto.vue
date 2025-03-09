<template>
  <div class="row" style="padding: 20px">
    <div class="col-sm-12">

      <form :action="'/legacy/print/cobrosadelanto'" method="post" target="_blank">
        <input type="hidden"  name="order_by" v-model="orden" >
        <input type="hidden"  name="order_method" v-model="orden_method" >

        <div class="row">
          <div class="col-xs-4">
            <div class="form-group">
              <label>Tipo de Reporte</label>
              <div class="filter-value-container" style="margin-top:3px;">
                <select @change="generarClicked()" name="tipo" class="form-control" v-model="tipo">
                  <option value="detallado">Detallado</option>
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
                  <input autocomplete="off" type="text" class=" form-control" id="date-input" name="rango">
                </div>
              </div>
            </div>
          </div>
          <div class="col-xs-4">
            <div class="form-group">
              <label>Cobrador</label>
              <div class="filter-value-container" style="margin-top:3px;">
                <select @change="generarClicked()" :disabled="tipo !== 'detallado'" name="cobrador" class="form-control" v-model="cobrador" >
                  <option value="todos">Todos</option>
                  <option v-for="cobrador in cobradores" :value="cobrador.collector_code">{{ cobrador.collector_code }} -  {{ cobrador.collector_name }}</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!--<div class="row">-->
          <!--<div class="col-xs-6">-->
            <!--<button type="button" @click="generarClicked()" class="btn btn-success">-->
              <!--<i class="fa fa-gear fa-fw"></i>-->
              <!--Generar Reporte-->
            <!--</button>-->
            <!--<button style="margin-left: 5px" v-show="!showPrintOptions" type="button" @click="showPrintOptions = true" class="btn btn-success">-->
              <!--<i class="fa fa-print fa-fw"></i>-->
              <!--Imprimir Reporte-->
            <!--</button>-->
          <!--</div>-->
        <!--</div>-->

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
                  <select name="consolidado_group" class="form-control"  v-model="consolidado_group">
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
              <button type="button" class="btn btn-default" @click="showPrintOptions = false">Volver</button>
            </div>
          </div>
        </div>
      </form>
    </div>

  </div>
</template>

<script>
  import {sortNumber} from '../common'
  import { EventBus } from './bus';

  const mapping = {
    customer: 'customer_code',
    seller: 'seller_code'
  }
  export default {
    props: ['sorting'],
    data () {
      return {
        showPrintOptions: false,
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
    created() {
      EventBus.$on('showPrintOptions', () => {

        this.showPrintOptions = !this.showPrintOptions;

      });
    },
    watch : {
      sorting (value) {
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
      fetchReportData () {
        this.$emit('loading', true)
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
            this.$emit('updateTableData', data, this.tipo, this.consolidado_group)
          },
          complete: () => {
            this.$emit('loading', false)
          }
        });
      }
    },

  }

</script>