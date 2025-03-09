<template>
  <div class="row" v-if="!loading && dashData">
    <div class="col-xs-12">
      <div class="row">
        <div class="col-xs-12">
          <ingresoswidgets :data="dashData.widgets"></ingresoswidgets>
        </div>
      </div>
      <collectorsgraph  :collectors="dashData.lists.collectors" :data="dashData.graphs.collectorsGraphData"></collectorsgraph>
      <!---->
      <!--<div class="row">-->
        <!--<div class="col-xs-12">-->
          <!--<div class="main-box ">-->
            <!--<header class="main-box-header clearfix"  >-->
              <!--<h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Top Clientes</h2>-->
              <!--<div class="header-tools">-->
              <!--</div>-->
            <!--</header>-->
            <!--<hr style="margin-top:0; margin-bottom: 5px">-->
            <!--<div class="main-box-body clearfix">-->
              <!--&lt;!&ndash;<topclientescobros :data="dashData.graphs.ingresosTotalGraph"></topclientescobros>&ndash;&gt;-->
            <!--</div>-->
          <!--</div>-->
        <!--</div>-->
      <!--</div>-->

      <div class="row" v-if="dashData.graphs.ingresosTotalGraph.length > 1">
        <div class="col-xs-12">
          <div class="main-box ">
            <header class="main-box-header clearfix"  >
              <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Ingresos Totales del Periodo</h2>
              <div class="header-tools">
              </div>
            </header>
            <hr style="margin-top:0; margin-bottom: 5px">
            <div class="main-box-body clearfix">
              <incomegraph  :data="dashData.graphs.ingresosTotalGraph"></incomegraph>
              <p class="help-block">Coloque el cursor sobre un punto en el gráfico para ver mas detalles.
                También puede hacer click en la leyenda del gráfico para ocultar data</p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
  import incomegraph from './IngresosGraph.vue'
  import collectorsgraph from './CollectorsReceiptsGraph.vue'
  import ingresoswidgets from './IngresosWidgets.vue'
  import topclientescobros from './TopClientesCobros.vue'
  export default {
    props: ['baseData'],
    components: {incomegraph,ingresoswidgets,collectorsgraph},
    data () {
      return {
        dashData: null,
        loading: false,
        lastQueryString: null,
      }
    },
    methods: {
      fetchIngresosData (params) {
        if ((this.lastQueryString && this.lastQueryString !== params) ||
        this.lastQueryString === null
      ) {
          //Event
          this.loading = true;
          this.$emit('loading',true)
          $.ajax ({
            method: 'GET',
            url: params ? '/dashboard/fetchingresosdata?get='+params : '/dashboard/fetchingresosdata',
            success: (data) => {
              let dashdata = null;
              if (!(data instanceof Object)) {
                dashdata = JSON.parse(data);
              } else {
                dashdata = data;
              }
              this.dashData =  $.extend(JSON.parse(dashdata.dashData), this.baseData);
              
//              this.sellerMultiOptions = this.dashData.lists.sellers;

              //Event
              this.$emit('successfullyFetched')
              this.lastQueryString = params;
            },
            error: this.errorOnFetching,
            complete:  () => {
              this.loading = false;
            }
          });
        }
      },
      errorOnFetching (xhr) {
        this.$emit('errorOnFetching', xhr)
      }
    },
    mounted () {

    }

  }
</script>