<template>
  <div v-if="dashData && !loading">
    <widgets :data="dashData.widgets"></widgets>
    <sellersgraph  :sellers="dashData.lists.sellers" :data="dashData.graphs.sellersGraphData"></sellersgraph>
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
            <topclientes :data="dashData.top.clientes"></topclientes>
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
    <div class="row" v-if="dashData.graphs.ventasTotalesGraph.length > 1">
      <div class="main-box " id="step10">
        <header class="main-box-header clearfix">
          <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Ventas Totales del Periodo</h2>
          <div class="header-tools">
          </div>
        </header>
        <hr style="margin-top:0; margin-bottom: 5px">
        <div class="main-box-body clearfix">
          <ventasgraph :data="dashData.graphs.ventasTotalesGraph"></ventasgraph>
          <p class="help-block">Coloque el cursor sobre un punto en el gráfico para ver mas detalles.
            También puede hacer click en la leyenda del gráfico para ocultar data</p>
        </div>
      </div>
    </div>
    <div class="row" v-if="!checkStore('tablas')">
      <div class="col-xs-12">
        <div class="alert alert-info">
          <button @click="setStore('tablas', true)" type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <i class="fa fa-info-circle fa-fw fa-lg"></i>
          ¿Busca la antigua tabla de Pedidos/Recibos de Ingreso/Devoluciones? Esta información ahora esta disponible en la seccion <strong>Reportes</strong>
        </div>
      </div>
    </div>
  </div>
</template>


<script>

  import widgets from './Widgets.vue'
  import sellersgraph from './SellerSalesGraph.vue'
  import ventasgraph from './VentasGraph.vue'
  import topmarcas from './TopMarcas.vue'
  import topclientes from './TopClientes.vue'
  import topproductos from './TopProductos.vue'
  import store from 'store'
  export default {
    props: ['baseData'],
    components: {
      widgets,sellersgraph, topmarcas,topclientes,topproductos,ventasgraph
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
      fetchVentasData (params) {
        if (
          (this.lastQueryString && this.lastQueryString !== params) ||
            this.lastQueryString === null
        ) {

          //Event
          this.loading = true;
          this.$emit('loading',true)
          $.ajax ({
            method: 'GET',
            url: params ? '/dashboard/fetchventasdata?get='+params : '/dashboard/fetchventasdata',
            success: (data) => {
              let dashdata = null;
              if (!(data instanceof Object)) {
             console.log(data)
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