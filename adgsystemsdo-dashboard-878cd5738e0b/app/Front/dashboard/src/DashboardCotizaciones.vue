<template>
  <div v-if="dashData && !loading">
    <widgets :data="dashData.widgets"></widgets>
    <cotizacionessellersgraph
            :sellers="dashData.lists.sellers"
            :data="dashData.graphs.quotationsGraphData"></cotizacionessellersgraph>
    <div class="row">
      <div class="col-lg-5 col-md-12">
        <div class="main-box match-height2" id="step7">
          <header class="main-box-header clearfix"  >
            <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Top Clientes</h2>
            <div class="header-tools">
            </div>
          </header>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body clearfix">
            <topclientes :data="dashData.top.clientes" :cotizaciones="true"></topclientes>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
        <div class="main-box match-height2"  id="step8">
          <header class="main-box-header clearfix">
            <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Top Marcas</h2>
            <div class="header-tools">
              <span @click="marcasSwitch = true" style="color: #00aced ; cursor: pointer;"><i class="fa fa-exchange"></i></span>
            </div>
          </header>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body clearfix">
            <topmarcas :switch="marcasSwitch" @switched="toggleMarcas"  :data="dashData.top.marcas"></topmarcas>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
        <div class="main-box match-height2"  id="step9">
          <header class="main-box-header clearfix">
            <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Top Productos</h2>
            <div class="header-tools">
              <span @click="productosSwitch = true" style="color: #00aced ; cursor: pointer;"><i class="fa fa-exchange"></i></span>
            </div>
          </header>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body clearfix">
            <topproductos :switch="productosSwitch" @switched="toggleProductos" :data="dashData.top.productos"></topproductos>
          </div>
        </div>
      </div>
    </div>
    <div class="row" v-if="dashData.graphs.quotationsTotalesGraph.length > 1">
      <div class="main-box " id="step10">
        <header class="main-box-header clearfix">
          <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Cotizaciones Totales del Periodo</h2>
          <div class="header-tools">
          </div>
        </header>
        <hr style="margin-top:0; margin-bottom: 5px">
        <div class="main-box-body clearfix">
          <cotizacionesgraph :data="dashData.graphs.quotationsTotalesGraph"></cotizacionesgraph>
          <p class="help-block">Coloque el cursor sobre un punto en el gráfico para ver mas detalles.
            También puede hacer click en la leyenda del gráfico para ocultar data</p>
        </div>
      </div>
    </div>

  </div>
</template>


<script>

    import widgets from './WidgetsCotizaciones.vue'
    import cotizacionesgraph from './CotizacionesGraph.vue'
    import cotizacionessellersgraph from './SellerQuotationsGraph'
    import topmarcas from './TopMarcasCotizacione.vue'
    import topclientes from './TopClientes.vue'
    import topproductos from './TopProductosCotizaciones.vue'
    import store from 'store'
    export default {
        props: ['baseData'],
        components: {
            widgets,cotizacionessellersgraph, topmarcas,topclientes,topproductos,cotizacionesgraph
        },
        data () {
            return {
                dashData: null,
                loading: false,
                lastQueryString: null,
                marcasSwitch: false,
                productosSwitch: false,
            }
        },
        methods: {
            toggleMarcas (){
                this.marcasSwitch = false;
            },
            toggleProductos (){
                this.productosSwitch = false;
            },
            fetchCotizacionesData (params) {
                if (
                    (this.lastQueryString && this.lastQueryString !== params) ||
                    this.lastQueryString === null
                ) {

                    //Event
                    this.loading = true;
                    this.$emit('loading',true)
                    $.ajax ({
                        method: 'GET',
                        url: params ? '/dashboard/fetchcotizacionesdata?get='+params : '/dashboard/fetchcotizacionesdata',
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
            },
            setStore (key,val) {
                store.set(key,val)
            },
            checkStore (key) {
                return store.get(key)
            }
        }
    }


</script>