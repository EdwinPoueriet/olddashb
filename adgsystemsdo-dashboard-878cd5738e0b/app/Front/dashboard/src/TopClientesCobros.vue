<template>
    <div class="row">
      <div class="col-sm-6 col-xs-12">
        <div id="topclientescobros"></div>
      </div>
      <div class="col-sm-6 col-xs-12">
        <table class="table table-condensed">
          <thead>
          <tr>
            <th></th>
          </tr>
          </thead>
          <tbody>

          </tbody>
        </table>
      </div>
    </div>
</template>

<script>
  let vendidoChart = null;
  import {formatMoney} from './functions'
  export default {
    props: ['data'],
    methods: {
      formatMoney: formatMoney,
      generateVendidoChart () {
        const data = this.data ? this.data : [];
        vendidoChart = c3.generate({
          bindto: '#topclientescobros',
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
              vendido: 'Total Vendido'
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
      }
    },
    mounted () {
      this.generateVendidoChart()
    }
  }
</script>