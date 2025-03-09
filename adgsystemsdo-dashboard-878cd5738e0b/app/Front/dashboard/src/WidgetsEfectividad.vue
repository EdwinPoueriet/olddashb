<template>
    <div class="row" v-if="data">
        <div class="col-md-4">
            <div class="main-box infographic-box " >
                <i class="fa fa-signal green-bg"></i>
                <span class="headline">Efectividad del Periodo</span>
                <span class="value">{{formatEfectividad(data.actual)}} %</span>
                <span class="widget-range">{{data.rangos.actual}}</span>
            </div>
        </div>

        <div class="col-md-4">
            <div class="main-box infographic-box " id="step3">
                <i class="fa fa-undo gray-bg    "></i>
                <span class="headline">Efectividad Periodo Anterior</span>
                <template v-if="data.anterior">
                    <span class="value">{{formatEfectividad(data.anterior)}} %</span>
                    <span class="widget-range">{{data.rangos.anterior}}</span>
                </template>
                <template v-else>
                    <span class="value">N/A</span>
                    <span class="widget-range">N/A</span>
                </template>

            </div>
        </div>
        <div class="col-md-4">
            <div class="main-box infographic-box" style="padding-bottom: 34px;">
                <i v-show="signoPositivo" class="fa fa-level-up green-bg"></i>
                <i v-show="!signoPositivo" class="fa fa-level-down red-bg"></i>
                <template v-if="data.anterior">
                <span class="headline">Cambio de Efectividad</span>
                <span class="value">
                    <span v-show="signoPositivo">+</span>
                    <span v-show="!signoPositivo">-</span>
                    {{formatEfectividad(Math.abs(data.actual - data.anterior)) }} %</span>
                </template>
                <template v-else>
                    <span class="headline">Cambio de Efectividad</span>
                    <span class="value">N/A</span>
                </template>
            </div>
        </div>


    </div>
</template>

<style scoped>
    .widget-range {
        display: block;
        text-align: right;
        font-weight: 300;
        font-size: 11px;
    }
</style>
<script>
    export default {
        props: ['data'],
        computed: {
            signoPositivo () {
                return this.data.actual - this.data.anterior > 0;
            }
        },
        methods: {
            formatEfectividad (val) {
                return parseFloat(val*100).toFixed(2)
            },
        }
    }
</script>