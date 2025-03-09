<template>
  <div class="row" style="padding: 20px">
    <div class="col-xs-12">

      <div class="main-box" >
        <header class="main-box-header clearfix"  >
          <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Filtros</h2>
        </header>
        <hr style="margin-top:0; margin-bottom: 5px">
        <div class="main-box-body ">

          <div class="alert alert-info ">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <i class="fa fa-info-circle fa-fw fa-lg"></i>
            <strong >Reporte de Ventas - Duración por Orden, Cliente y Vendedor.</strong>
            <ul>
              <li>
                <i class="fa fa-map-marker"></i>: Este icono significa que la orden fue tomada en la ubicación del cliente.
              </li>
            </ul>

          </div>
          <form action="/reports/ventasduracion" method="post" target="_blank">
            <div class="row">
              <div class="col-xs-6">
                <div class="form-group">
                  <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                    <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                  </div>
                  <label> Vendedores </label>
                  <multiselect
                      :options="sellersList"
                      :multiple="true"
                      :close-on-select="false"
                      :hide-selected="true"
                      placeholder="Seleccione Vendedores"
                      label="seller_name"
                      track-by="seller_code"
                      v-model="selectedSellers"
                  ></multiselect>
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group" >
                  <label>Fecha</label>
                  <div class="filter-range-container" style="margin-top:3px;" >
                    <div class="input-group" >
                      <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                      <input type="text" autocomplete="off" class=" form-control" id="duracion-date-input" name="date">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-3">
                <div class="form-group">
                  <label>Ordenar Por</label>
                  <select name="order_by" class="form-control" v-model="orden" >
                    <option value="seller">Vendedor</option>
                    <option value="customer">Cliente</option>
                    <option value="duracion">Duración</option>
                    <option value="monto">Monto</option>
                  </select>
                </div>
              </div>
              <div class="col-xs-3">
                <div class="form-group">
                  <label>Método</label>
                  <select name="order_method" class="form-control" v-model="orden_method" >
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-xs-12">
                <div style="display: block; margin-top: 15px" >
                  <button type="submit" class="btn btn-success">
                    <i class="fa fa-print fa-fw"></i>
                    Imprimir
                  </button>
                </div>
              </div>
            </div>
            <template v-for="seller in selectedSellers">
              <input type="hidden" name="sellers[]" :value="seller.seller_code">
            </template>
          </form>
        </div>
      </div>

    </div>
  </div>

</template>
<!--<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>-->
<script>
  export default {
    data () {
      return {
        vendedores: sellersList,
        vendedores_inactivos: sellersListInactive,
          showInactiveSellers: false,
        orden: 'seller',
        orden_method: 'desc',
        selectedSellers: {seller_name: 'Todos', seller_code: "todos"}
      }
    },
    computed: {
        sellersList () {
            if(this.showInactiveSellers) {
                return this.vendedores.concat(this.vendedores_inactivos)
            } else {
                return this.vendedores;
            }
        }
    },
    mounted () {
      let date = $('#duracion-date-input');
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
        this.fecha = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
      });
    }


  }
</script>
