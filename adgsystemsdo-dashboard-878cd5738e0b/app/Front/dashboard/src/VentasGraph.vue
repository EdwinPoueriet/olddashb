<template>
  <div @mouseenter="interacting = true"   @mouseleave="interacting = false"  id="ventasgraph"></div>

</template>

<script>
  import {formatMoney} from './functions'
  let step = 0;
  export default {
    props: ['data'],
    data () {
      return {
        dynamic: true,
        interacting: false
      }
    },
    methods: {
      generateGraph () {
        const data = this.data ? this.data : [];
        const chart = c3.generate({
          bindto: '#ventasgraph',
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
          point: {
            r: 3.5
          },
          color: {
            pattern: ['#8bc34a',
              '#1f77b4',   '#ff861c']
          },
          data: {
            json: data,
            keys: {
              x: 'order_date',
              value: ['total', 'cantidad','promedio']
            },
            names: {
              total: 'Monto Vendido',
              cantidad: 'Cantidad de Pedidos',
              promedio: 'Monto promedio de Pedidos'
            },
            type:'bar',
            axes: {
              total: 'y',
              promedio: 'y',
              cantidad: 'y2'
            }
          },
          axis: {
            x: {
              type: 'timeseries',
              tick: {
                format: '%d-%m-%Y'
              },
              label: {
                text: 'Fecha',
                position: 'outer-left'
              },
            },
            y: {
              label: {
                text : 'Monto',
                position: 'outer-left'
              },
              tick: {
                format: function (v) {
                  return formatMoney(v)
                }
              }
            },
            y2: {
              show: true,
              label: 'Cantidad'
            }
          },
          tooltip: {
            format: {
              value: function (value, r, id, index) {
                if (id !== 'cantidad') {
                  return formatMoney(value)
                }
                return value;

              }
            },
          }
        });

        setInterval(()=> {
          if (this.interacting === false) {
            if (step === 0 ) {
              step = 1;
              chart.hide('total')
              chart.show('promedio')
              chart.show('cantidad')
            } else if (step === 1) {
              chart.show('total')
              chart.hide('promedio')
              chart.show('cantidad')
              step = 2;
            } else if (step === 2) {
              chart.show('total')
              chart.show('promedio')
              chart.show('cantidad')
              step = 0;
            }
          }
        },4800)
      },

      formatMoney: formatMoney
    },
    mounted () {
      this.generateGraph();
    }
  }
</script>