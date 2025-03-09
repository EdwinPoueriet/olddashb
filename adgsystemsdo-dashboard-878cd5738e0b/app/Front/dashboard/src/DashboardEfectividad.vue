<template>
  <div v-if="!loading && dashData">
    <widgets-efectividad :data="widgetsData"></widgets-efectividad>
    <div class="row">
      <div class="col-xs-12">
        <div class="main-box">
          <div class="main-box-body clearfix">
            <div class="row">
              <div class=" col-xs-12 " >
                <div class="main-box match-height">
                  <header class="main-box-header clearfix">
                    <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Gráfico de Efectividad por Vendedor</h2>
                  </header>
                  <hr style="margin-top:0">
                  <div class="main-box-body clearfix" >
                    <efectividad-graph :data="graphData"></efectividad-graph>
                    <div style="text-align: center" >
                      <p class="help-block">Coloque el cursor sobre un punto en el gráfico para ver mas detalles.
                        También puede hacer click en la leyenda del gráfico para ocultar data</p>
                    </div>
                    <div class="bottom-button">
                      <span @click="showRaw = true;" v-show="!showRaw">Ver data cruda</span>
                      <span @click="showRaw = false;" v-show="showRaw">Ocultar data cruda</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div v-if="showRaw" class="row">
              <div class="col-sm-12">
                <table class="table table-striped table-bordered table-condensed">
                  <thead>
                  <tr>
                    <th>Vendedor</th>
                    <th>Total Visitas</th>
                    <th>Efectivas</th>
                    <th>No Efectivas</th>
                    <th>Efectividad Indiv.</th>
                    <th>Efectividad Colect.</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr @mouseover="selectedSeller = seller" @mouseleave="selectedSeller = null" class="selectable" v-for="seller in dashData.efectividadGlobal">
                    <td>{{seller.seller_code}} - {{seller.seller_name}}</td>
                    <td>{{seller.totals.total}}</td>
                    <td>{{seller.totals.efectivas}}</td>
                    <td>{{seller.totals.noefectivas}}</td>
                    <td>{{formatEfectividad(seller.totals.efectividad)}} %</td>
                    <td>{{formatEfectividad(seller.totals.efectivas / totalEfectivas)}} %</td>
                  </tr>
                  <tr>
                    <td v-show="!dashData.efectividadGlobal || dashData.efectividadGlobal.length == 0" colspan="5" style="text-align: center">
                      No hay data para los filtros aplicados.
                    </td>
                  </tr>
                  </tbody>
                  <tfoot>
                  <tr class="totales">
                    <td>Totales</td>
                    <td>{{totalVisitas}}</td>
                    <td>{{totalEfectivas}}</td>
                    <td>{{totalNoEfectivas}}</td>
                    <td>{{formatEfectividad(totalEfectividad)}} %</td>
                    <td></td>
                  </tr>
                  </tfoot>
                </table>
              </div>
              <!--<div class="col-sm-5">-->
              <!--<div style="text-align: center">-->
              <!--<h4 v-if="selectedSeller">Vendedor {{selectedSeller.seller_code}} - {{selectedSeller.seller_name}}</h4>-->
              <!--<h4 v-else>Efectividad Global</h4>-->
              <!--</div>-->
              <!--<efectividad-donut v-if="dashData" :data="donutData"></efectividad-donut>-->
              <!--</div>-->
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<style scoped>
  .table tbody > tr > td {
    font-size: 0.875em;
  }
  .bottom-button {
    position: absolute;
    bottom: 15px;
    right: 10px;
    cursor:pointer;
    color: #03a9f4;
    font-weight: 500;
  }

  .bottom-button:hover {
    color: #038dcd;
  }

  .totales td {
    font-weight: 600;
  }
  .selectable:hover {
    cursor: pointer;
    background-color: #efefef;
  }

</style>
<script>
import EfectividadGraph from './EfectividadGraph.vue'
import WidgetsEfectividad from './WidgetsEfectividad.vue'
  export default {
    components: {EfectividadGraph,WidgetsEfectividad},
    props: ['baseData'],
    data () {
      return {
        dashData: null,
        showRaw: false,
        loading: false,
        lastQueryString: null,
        selectedSeller: null
      }
    },
      computed: {
          widgetsData () {
              return this.dashData ? this.dashData.efectividadPeriodos : null;
          },
          graphData () {
              const total = this.totalEfectivas;
            return Object.keys(this.dashData.efectividadGlobal).map(val => {
                  const seller = this.dashData.efectividadGlobal[val];
                  return {
                      seller_code: seller.seller_code,
                      seller_name:seller.seller_name,
                      efectivas: seller.totals.efectivas,
                      noefectivas: seller.totals.noefectivas,
                      efectividad: this.formatEfectividad(seller.totals.efectividad),
                      efectividad_global: this.formatEfectividad(seller.totals.efectivas / total)
                  }
              })
          },
          totalVisitas () {
              return this.sumValues('total');
          },
          totalEfectivas () {
              return  this.sumValues('efectivas');
          },
          totalNoEfectivas () {
              return   this.sumValues('noefectivas');
        },
        totalEfectividad () {
            return   this.totalEfectivas / this.totalVisitas
        },
          totalEfectividadColect () {
              return this.sumValues('efectividad_global')
          },
        hasData () {
            return this.dashData && this.dashData.efectividadGlobal && this.dashData.efectividadGlobal.length !== 0;
        }
  },
    methods: {
        sumValues (key) {
            if (this.hasData) {
                let res = 0;
                Object.keys(this.dashData.efectividadGlobal).forEach(val => {
                    res += this.dashData.efectividadGlobal[val].totals[key];
                })
                return res;
            }
            return 0;
        },
      formatEfectividad (val) {
          return parseFloat(val*100).toFixed(2)
      },
      fetchEfectividadData (params) {
        if ((this.lastQueryString && this.lastQueryString !== params) ||
        this.lastQueryString === null
      ) {
          //Event
          this.loading = true;
          this.$emit('loading',true)
          $.ajax ({
            method: 'GET',
            url: params ? '/dashboard/fetchefectividaddata?get='+params : '/dashboard/fetchefectividaddata',
            success: (data) => {
              let dashdata = null;
              if (!(data instanceof Object)) {
                dashdata = JSON.parse(data);
              } else {
                dashdata = data;
              }

              this.dashData =  $.extend(JSON.parse(dashdata.dashData), this.baseData);
              
              this.sellerMultiOptions = this.dashData.lists.sellers;
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