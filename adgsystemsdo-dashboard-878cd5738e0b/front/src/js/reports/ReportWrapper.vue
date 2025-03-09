<template>
  <div>
    <div class="row">
      <div class="col-xs-12">

        <div class="main-box" >
          <header class="main-box-header clearfix"  >
            <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Filtros</h2>
            <button style="margin-left: 5px" type="button" @click="showPrintOptions" class="btn btn-success pull-right">
              <i class="fa fa-print fa-fw"></i>
              Imprimir
            </button>
          </header>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body ">
            <slot name="report" :loadingEvent="loadingEvent"></slot>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">
        <div class="main-box" style="padding-top: 20px">
          <template v-if="loading">
            <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i></div>
          </template>
          <div v-show="!hideTable" class="main-box-body">
            <div class="tablewrapper">
              <div id="jsGrid"></div>
              <div>
                <slot name="totales"></slot>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</template>

<style>

  .panel-title {
    font-weight: 500;
  }

  /*.tablewrapper td {*/
    /*font-size: 0.62vw !important;*/
  /*}*/

  .jsgrid-header-cell {
    font-size: 13px !important;
  }

</style>
<script>
  import { EventBus } from './bus';
  export default {
    props: ['hideTable'],
    data () {
      return {
        loading: false,
        cantidad: 0
      }
    },
    computed: {
      hasData () {
        return this.cantidad > 0
      }
    },
    methods: {
      showPrintOptions(){
        EventBus.$emit('showPrintOptions')
      },
      loadingEvent (val) {
        this.loading = val
      },
      updateTable (options, cantidad) {
        this.cantidad = cantidad;
        const grid =  $("#jsGrid");
        grid.jsGrid("destroy");
        grid.jsGrid(
          {...options,
            width: '100%',
            onRefreshed: () => {
            const sorting = grid.jsGrid("getSorting");
            this.$emit('sortingChanged',sorting)
          },

          }
        );
        grid.jsGrid("option", "filtering", false);
      }

    }
  }
</script>
