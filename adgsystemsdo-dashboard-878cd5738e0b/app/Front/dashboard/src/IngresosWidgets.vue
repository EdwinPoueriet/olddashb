<template>
  <div class="row">
    <div class="col-lg-4">
      <div class="main-box infographic-box" id="step1">
        <i class="fa fa-pie-chart red-bg"></i>
        <div class="row">
          <div  class="col-xs-4" >
            <span class="headline" >Clientes</span>
            <span class="value">{{data.clientCount}}</span>
          </div>
          <div class="col-xs-4" >
            <span class="headline">Recibos</span>
            <span class="value" >{{data.receiptCount}}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="main-box infographic-box " id="step2">
        <i class="fa fa-shopping-cart green-bg"></i>
        <span class="headline">Monto total de cobros</span>
        <span class="value" id="amount">{{formatMoney (data.receiptAmount)}}</span>
      </div>
    </div>

    <div class="col-lg-4 ">
      <div class="main-box infographic-box " id="step3">
        <i class="fa fa-money green-bg"></i>
        <span class="headline">Promedio por cobrador</span>
        <span class="value">{{formatMoney (data.receiptAverage)}}</span>
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