<template>
    <div id="efectividad-graph"></div>
</template>
<style scoped>
    .c3-chart-arcs-title {
        font-size: 2em;
        color: #252527;
    }
    .help-block {
        font-size: .7em;
    }
    .c3-line {
        stroke-width: 2px;
    }
    .c3-circle {
        stroke: white;
        stroke-width: 2;
    }
</style>
<script>
    let chart = null;
    export default {
        props: ['data'],
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
                    bindto: '#efectividad-graph',
                    point: {
                        r: 5
                    },
                    zoom: {
                        enabled: true
                    },
                    data: {
                        colors: {
                            efectivas: '#8bc34a',
                            noefectivas: '#1f77b4',
                            efectividad: '#ff861c',
                            efectividad_global: '#c32544'
                        },
                        groups: [
                            ['efectivas','noefectivas']
                        ],
                        json: data,
                        keys: {
                            x: 'seller_code',
                            value: ['efectivas','noefectivas','efectividad','efectividad_global']
                        },
                        names:{
                            efectivas: 'Efectivas',
                            noefectivas: 'No Efectivas',
                            efectividad: 'Efectividad Individual',
                            efectividad_global: 'Efectividad Global'
                        },
                        type:'bar',
                        types: {
                            efectividad_global: 'line'
                        },
                        axes: {
                            efectivas: 'y',
                            noefectivas: 'y',
                            efectividad: 'y2',
                            efectividad_global: 'y2'
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
                            label: 'Cantidad'
                        },
                        y2: {
                            show: true,
                            label: 'Porcentaje'
                        }
                    },
                    tooltip: {
                        format: {
                            title: function (x) {
                                const seller = data[x]
                                return seller.seller_code + ' - ' + seller.seller_name
                            },
                            value: function (value,r,id,index) {
                                if (id === 'efectividad' || id === 'efectividad_global')
                                return value + '%';
                                else
                                    return value;
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
                                if (i===2) {
                                    const seller = data[d[0].index];
                                    if (seller) {
                                        text += "<tr>";
                                        text += "<td class='name'><span style='background-color:#3b3b1f '></span>Total Visitas</td>";
                                        text += "<td class='value'> " + (parseInt(seller.efectivas) + parseInt(seller.noefectivas))  +" </td>";
                                        text += "</tr>";
                                    }
                                }
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

                            return text + "</table>";
                        }
                    }
                });
            }
        },
        mounted () {
            this.generateChart(this.data ? this.data : [])
        }
    }



</script>