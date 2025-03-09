<template>
  <div class="row">
    <div class=" col-xs-12 " >
      <div class="main-box match-height">
        <header class="main-box-header clearfix">
          <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Gráfico de Cotizaciones por Vendedor</h2>
          <div class="header-tools" id="step6">
            <div class="input-group" style="width: 200px;z-index: 0">
              <span class="input-group-addon"><i class="fa fa-gear"></i></span>
              <select class="form-control" v-model="chartType">
                <option value="dynamic">Modo dinámico</option>
                <option value="bar">Gráfico de Barras</option>
                <option value="line">Gráfico Lineal</option>
              </select>
            </div>
          </div>
        </header>
        <hr style="margin-top:0">
        <div class="main-box-body clearfix" id="step5">
          <div id="sellers-graph"></div>
          <p class="help-block">Coloque el cursor sobre un punto en el gráfico para ver mas detalles.
            También puede hacer click en la leyenda del gráfico para ocultar data</p>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
  .c3-chart-arcs-title {
    font-size: 2em;
    color: #252527;
  }

  .help-block {
    font-size: .7em;
  }
</style>
<script>
  import {formatMoney} from './functions'
  let chart = null;
  let donutChart = null;
  export default {
    props: ['data', 'sellers'],
    data () {
      return {
        chartType: 'dynamic',
        currentType: 'bar'
      }
    },
    watch :{
      chartType (val) {
        if (val !== 'dynamic') {
          this.currentType = val;
          chart.transform(val)
        }
      }
    },
    methods: {
      generateChart (data) {
      chart = c3.generate({
        grid: {
          x: {
            show: true
          },
          y: {
            show: true
          }
        },
          bindto: '#sellers-graph',
          point: {
            r: 3.5
          },
        color: {
          pattern: ['#8bc34a',
            '#1f77b4',
            '#ff861c',
            '#aec7e8',
            '#e84e40', '#03a9f4', '#ED6A5E','#F9DBBD', '#3185FC']
        },
        zoom: {
            enabled: true
        },
          data: {
            json: data,
            keys: {
              x: 'seller_code',
              value: ['monto','cantidad_pedidos','mayor_pedido',]
            },
            names:{
              monto: 'Cotizaciones Totales',
              mayor_pedido: 'Monto Mayor Cotización',
              cantidad_pedidos: 'Cantidad de Cotizaciones'
            },
            type:'bar',
            axes: {
              monto: 'y',
              mayor_pedido: 'y',
              cantidad_pedidos: 'y2'
            }
          },
          axis: {
            x: {
              type: 'category',
              label: {
                text : 'Código de Vendedor',
                position: 'outer-left'
              },
            },
            y: {
              label: 'Monto',
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
              title: function (x) {
                const seller = data[x]
                  let text = "";
                  if (seller.status) {
                    text =  ' - ' + (seller.status === 1 ? 'Activo' : 'Inactivo')
                  }
                return seller.seller_code + ' - ' + seller.seller_name + text
              },
              value: function (value,r,id,index) {
                if (id !== 'cantidad_pedidos') {
                  return formatMoney(value)
                } else
                  return value
              }
            },
            contents: function (d, defaultTitleFormat, defaultValueFormat, color) {
              let $$ = this, config = $$.config,
                titleFormat = config.tooltip_format_title || defaultTitleFormat,
                nameFormat = config.tooltip_format_name || function (name) {
                    return name;
                  },
                valueFormat = config.tooltip_format_value || defaultValueFormat,
                text, i, title, value, name, bgcolor;
              for (i = 0; i < d.length; i++) {
                if (!(d[i] && (d[i].value || d[i].value === 0))) {
                  continue;
                }

                if (!text) {
                  title = titleFormat ? titleFormat(d[i].x) : d[i].x;
                  text = "<table class='" + $$.CLASS.tooltip + "'>" + (title || title === 0 ? "<tr><th colspan='2'>" + title + "</th></tr>" : "");
                }

                name = nameFormat(d[i].name);
                value = valueFormat(d[i].value, d[i].ratio, d[i].id, d[i].index);
                bgcolor = $$.levelColor ? $$.levelColor(d[i].value) : color(d[i].id);

                text += "<tr class='" + $$.CLASS.tooltipName + "-" + d[i].id + "'>";
                text += "<td class='name'><span style='background-color:" + bgcolor + "'></span>" + name + "</td>";
                text += "<td class='value'>" + value + "</td>";
                text += "</tr>";
              }
              const cus = data[d[0].index];
              if (cus && cus.mayor_pedido_customer_code !== null) {
                text += "<tr>";
                text += "<td class='name'><span style='background-color:#3b3b1f '></span>Cliente de Mayor Cotización</td>";
                text += "<td class='value'> "+ cus.mayor_pedido_customer_code  +' - '+cus.mayor_pedido_customer_name +" </td>";
                text += "</tr>";
              }
              return text + "</table>";
            }
          }
        });
        setInterval(() => {
          if (this.chartType === 'dynamic') {
            this.currentType = this.currentType === 'bar' ? 'line' : 'bar';
            chart.transform(this.currentType)
          }
        },12000)
      }
    },
    mounted () {
      this.generateChart(this.data ? this.data : [])
      $('.match-height').matchHeight();
    }
  }



</script>