<template>
    <div>
        <div id="efectividad-gauge"></div>
    </div>
</template>
<script>
    let chart = null;
    export default {
        props: ['data'],
        data () {
            return {}
        },
        watch: {
            data (val) {
                let columns = [];
                columns.push(val);
                chart.load({
                    columns: columns
                });
            }
        },
        methods: {
            generateChart (data) {
                chart = c3.generate({
                    bindto: '#efectividad-gauge',
                    data: {
                        json: data.values,
                        type: 'donut',
                        keys: {
                            value: data.sellers,
                        },
                        names: {
                            efectividad: '% Efectividad',
                            seller_code: 'Vendedor'
                        },

                    },
                    donut: {
                        width: 70
                    },
                    //                    color: {
//                        pattern: ['#FF0000', '#F97600', '#F6C600', '#60B044'], // the three color levels for the percentage values.
//                        threshold: {
//                            values: [40, 60, 70]
//                        }
//                    },
                    legend: {
                        position: 'right'
                    },

                });

            }
        },
        mounted () {
            this.generateChart(this.data ? this.data : [])
        }
    }
</script>