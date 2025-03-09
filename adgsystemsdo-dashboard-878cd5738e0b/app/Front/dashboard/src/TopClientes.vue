<template>
<div>
  <div class="row">
    <div class="col-xs-12" style="overflow: auto">
      <div style="width: 536px" id="cobrado"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-12"  style="overflow: auto">
      <div style="width: 536px" id="cantidad_pedidos"></div>
    </div>
  </div>
</div>
</template>

<script>
  let vendidoChart = null;
  let cantidadChart = null;
  import {formatMoney} from './functions'
  export default {
    props: {
        data: Array,
        cotizaciones:{
            type: Boolean,
            default: false
        }
    },
    methods: {
      formatMoney: formatMoney,
      generateVendidoChart () {
         const data = this.data ? this.data : [];
        vendidoChart = c3.generate({
          bindto: '#cobrado',
          size: {
            height: 250
          },
          color: {
            pattern: ['#8bc34a', '#1f77b4', '#ff861c', '#aec7e8',
              '#e84e40', '#03a9f4', '#ED6A5E', '#F9DBBD', '#3185FC'],
          },
          zoom: {
            enabled: true
          },
          grid: {
            x: {
              show: true
            },
            y: {
              show: true
            }
          },

          data: {
            json: data,
            keys: {
              x: 'customer_code',
              value: ['vendido']
            },
            type: 'bar',
            names:{
              vendido: this.cotizaciones ? 'Total Cotizado':'Total Vendido'
            },
          },
          axis: {
//            rotated: true,
            x: {
              type: 'category',
              label: {
                text : 'Código de Cliente',
                position: 'outer-left'
              },
            },
            y: {

              label: {
                position: 'outer-left',
                text:  'Monto'
              },
              tick: {
                format: function (v) {
                  return formatMoney(v, true)
                }
              }
            },
          },
          tooltip: {
            format: {
              title: function (x) {
                const customer = data[x]
                return customer.customer_code + ' - ' + customer.customer_name
              },
              value: function (value,r,id,index) {
                  return formatMoney(value)
              },

            },
          }
        });
      },
      generateCantidadChart () {
        const data = this.data ? this.data : [];
        cantidadChart = c3.generate({
          bindto: '#cantidad_pedidos',
          size: {
            height: 180
          },
          color: {
            pattern: [ '#1f77b4'],
          },
          zoom: {
            enabled: true
          },
          grid: {
            x: {
              show: true
            },
             y: {
              show: true
            }
          },
          data: {
            onmouseover: function (d) {
              vendidoChart.tooltip.show({x: d.x})
            },
            json: data,
            keys: {
              x: 'customer_code',
              value: ['cantidad_ventas']
            },
            type: 'bar',
            names:{
              cantidad_ventas: this.cotizaciones ? 'Cantidad de cotizaciones' : 'Cantidad de Pedidos'
            },
          },
          axis: {
            x: {
              type: 'category',
              label: {
                text : 'Código de Cliente',
                position: 'outer-left'
              },
            },
            y: {
              label: {
                position: 'outer-left',
                text:  'Cantidad'
              },

            },
          },
          tooltip: {
            format: {
              title: function (x) {
                const customer = data[x]
                return customer.customer_code + ' - ' + customer.customer_name
              }
            },
          }
        });

      }
    },
    mounted () {
      this.generateVendidoChart()
      this.generateCantidadChart()
    }
  }
</script>