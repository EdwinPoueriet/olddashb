<template>
  <div @mouseenter="canChange = false"  @mouseleave="canChange = true">

    <transition-group
        name="appear"
        enter-active-class="animated fadeIn"
    >
      <table  :key="'amount'"  v-if="showAmount" class="table  table-striped table-condensed">
        <thead>
        <tr>
          <th>Producto</th>
          <th style="text-align: right">Monto Vendido</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="product in data">
          <td>{{product.product_name}}</td>
          <td style="text-align: right">{{formatMoney(product.importe)}}</td>
        </tr>
        </tbody>
      </table>
      <table  :key="'cantidad'"  class="table table-striped table-condensed" v-else>
        <thead>
        <tr>
          <th>Producto</th>
          <th style="text-align: right">Cantidad vendida</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="product in data">
          <td>{{product.product_name}}</td>
          <td style="text-align: right">{{product.cantidad}}</td>
        </tr>
        </tbody>
      </table>
    </transition-group>

  </div>
</template>

<style scoped>
  td {
    font-size: 13px !important;
    font-weight: 300;
  }
  th {
    font-weight: 600;
  }
</style>
<script>
  import {formatMoney} from './functions'
  export default {
    data () {
      return {
        canChange: true,
        showAmount: true
      }
    },
    props: ['data', 'switch'],
    methods: {
      formatMoney: formatMoney
    },
      watch: {
          switch (val) {
              if (val) {
                  this.showAmount = !this.showAmount
                  this.$emit('switched')
              }

          }
      },
    mounted () {
      setInterval(()=> {
        if (this.canChange)
        this.showAmount = !this.showAmount
      },8000)
    }
  }
</script>