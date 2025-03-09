<template>
  <div class="row">
    <div class="col-xs-12">
        <div class="col-lg-4 col-sm-6 col-xs-12"
             v-hint.top="'Cantidad de Cotizaciones y Clientes involucrados en cálculos'">
          <div class="main-box infographic-box" id="step1">
            <i class="fa fa-pie-chart red-bg"></i>
            <div class="row">
              <div  class="col-xs-4" >
                <span class="headline">Clientes</span>
                <span class="value">{{data.clientCount}}</span>
              </div>
              <div class="col-xs-4" >
                <span class="headline">Cotizaciones</span>
                <span class="value" >{{data.quotationCount}}</span>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
          <div class="main-box infographic-box " id="step2">
            <i class="fa fa-shopping-cart green-bg"></i>
            <span class="headline">Monto total cotizado</span>
            <span class="value" id="amount">{{formatMoney (data.quotationsAmount)}}</span>
          </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-xs-12">
          <div class="main-box infographic-box " id="step3">
            <i class="fa fa-money green-bg"></i>
            <span class="headline">Promedio por vendedor</span>
            <span class="value">{{formatMoney (data.quotationsAverage)}}</span>
          </div>
        </div>
      </div>
    </div>
</template>

<script>
  import {formatMoney} from './functions'
  import hint from 'vue-hint.css'
  export default {
    props: ['data'],
    directives: {hint},
    methods: {
      formatMoney: formatMoney
    }
  }
</script>