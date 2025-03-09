<template>
  <div @mouseenter="canChange = false"  @mouseleave="canChange = true">
    <transition-group
        name="appear"
        enter-active-class="animated fadeIn"
    >
      <table :key="'amount'" v-if="showAmount" class=" table table-striped table-condensed">
        <thead>
        <tr>
          <th>Marca</th>
          <th style="text-align: right">Monto Vendido</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="brand in data">
          <td>{{brand.brand_name}}</td>
          <td style="text-align: right">{{formatMoney(brand.importe)}}</td>
        </tr>
        </tbody>
      </table>
      <table :key="'cantidad'"  class=" table table-striped table-condensed" v-else >
        <thead>
        <tr>
          <th>Marca</th>
          <th style="text-align: right">Cantidad vendida</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="brand in data">
          <td>{{brand.brand_name}}</td>
          <td style="text-align: right">{{brand.cantidad}}</td>
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
    props: ['data', 'switch'],
    data () {
      return {
        canChange: true,
        showAmount: true
      }
    },
      watch: {
        switch (val) {
            if (val) {
                this.$emit('switched')
                this.showAmount = !this.showAmount
            }

        }
      },
    methods: {
      formatMoney: formatMoney
    },
    mounted () {
      setInterval(()=> {
        if (this.canChange)
        this.showAmount = !this.showAmount
      },8000)
    }
  }
</script>