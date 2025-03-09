<template>
  <div class="table-wrapper" style="text-align: left">
    <div class="row" v-if="!loading">
      <div class="col-xs-5 col-xs-offset-2">
        <scrollbar  v-if="updates"  class="scroll-area" :settings="settings" >
          <table class="table table-hover table-striped table-bordered table-condensed">
            <thead>
            <tr>
              <th>ID de sincronización</th>
              <th>Tipo</th>
              <th>Fecha</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(log,index) in updates" @click="showModal(log.log_id)" style="cursor: pointer;">
              <td>{{ log.log_id }}</td>
              <td>{{ log.sync_type }}</td>
              <td>{{ formatDate(log.date)  }}</td>
            </tr>
            </tbody>
          </table>
        </scrollbar>
        <div style="text-align: center; padding: 25px" v-show="!updates">
          <b> No hay información disponible para el usuario seleccionado. </b>
        </div>
      </div>
      <div class="col-xs-3">
        <!--<h4>Detalle</h4>-->
        <table  class="table table-condensed table-bordered">
          <thead>
          <tr>
            <th style="text-align: center" colspan="2">Detalle
            <template v-if="selectedUpdate">sync # {{ selectedUpdate.log_id }}</template>
            </th>
          </tr>
          <tr>
            <th>Descripción</th>
            <th>Cantidad</th>
          </tr>
          </thead>
          <tbody v-if="!selectedUpdate">
          <tr>
            <td colspan="2">
              No hay data para mostrar
            </td>
          </tr>
          </tbody>
          <tbody v-if="selectedUpdate">
          <tr v-for="detail in selectedUpdate.detail">
            <td>{{ detail.description }}</td>
            <td>{{ detail.quantity }}</td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-else style="text-align:   center">
      <span ><i class="fa fa-spinner fa-spin"></i></span>
    </div>


  </div>
</template>
<style scoped>
  .modal-header {
    background-color: #03a9f4;
    color: white;
  }
  .scroll-area {
    position: relative;
    margin: auto;
    height: 500px;
      box-shadow: 0 0 5px 2px rgba(0,0,0,.15);
    /*border: 1px solid rgba(81, 102, 123, 0.94);*/
  }
  th {
    background-color: #03a9f4 ;
    color: white;
  }
  .table-wrapper {
    margin: auto  ;
    margin-top: 10px;
  }
  td {
    font-size: 12px !important;
    font-weight: 300;
  }
</style>

<script>
  import scrollbar from 'vue-perfect-scrollbar'
  const dateFormat = require('dateformat');
  export default {
    components: {
      scrollbar
    },
    props: ['updates', 'loading'],
    data () {
      return {
        selectedUpdate: null,
        settings: {
          maxScrollbarLength: 60
        }
      }
    },
    methods : {
      formatDate(string) {
        return dateFormat(new Date(string), "dd-mm-yyyy hh:MM TT");
      },
      showModal(id) {
        this.selectedUpdate = id;
        let log = this.updates.find( (data) => {
          return data.log_id === id
        })
        if (log) {
          this.selectedUpdate = log;
        }
      }
    },
    mounted () {

    }
  }
</script>