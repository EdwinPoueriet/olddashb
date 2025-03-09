<template>
  <div >
    <!--<div v-if="users"  style="text-align: left;      box-shadow: 0 0 5px 2px rgba(0,0,0,.15); ">-->

        <table id="tabletest" class="table table-bordered table-condensed device-details table-striped table-hover">
          <thead>
          <tr>
            <th>Uid</th>
            <th>Usuario</th>
            <th>Vendedor</th>
            <th>IMEI</th>
            <th>Marca</th>
            <th>Modelo</th>
            <th>Android Ver.</th>
            <th>API Lvl</th>
            <th>Nivel Bat.</th>
            <th>Vers. Cod</th>
            <th>Vers.</th>
            <th>Fecha de Act. Datos</th>
            <th>Ult Fecha Desc</th>
            <th>Ult Fecha Env</th>
          </tr>
          </thead>

          <tbody >

          <!--<scrollbar  class="scroll-area" :settings="settings" >-->
          <tr v-for="user in users" class="clickable" @click="rowClicked(user)">
            <td>{{user.user.user_id}}</td>
            <td>{{user.user.user_name}}</td>
            <td>{{user.user.seller}}</td>
            <td>{{ user.device.imei }}</td>
            <td>{{ user.device.brand }}</td>
            <td>{{ user.device.model }}</td>
            <td>{{ user.device.android_version}} | {{ user.device.android_version_release}}</td>
            <td>{{ user.device.api_level }}</td>
            <td>{{ user.device.battery_level }} %</td>
            <td>{{ user.device.version_name }}</td>
            <td>{{ user.device.version_code }}</td>
            <td>{{ formatDate(user.device.date) }}</td>
            <td>{{ formatDate(user.device.desc_date) }}</td>
            <td>{{ formatDate(user.device.sub_date) }}</td>
          </tr>

          <!--</scrollbar>-->
          </tbody>

        </table>
    <div class="row">
      <div style="text-align: left; margin-top: 8px;
    margin-left: 9px;">
        <span style="    font-weight: bold;">Total de usuarios:</span> {{ userCount }}

      </div>

    </div>



    <!--</div>-->

    <div style="text-align: center; padding: 25px" v-show="!users">
      <b> No hay información disponible para el usuario seleccionado.</b>
    </div>

  </div>
</template>
<style scoped>

  .clickable {
    cursor: pointer;
  }

  .scroll-area {
    position: relative;
    margin: auto;
    height: 500px;

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
  import dateformat from 'dateformat'
  export default {
    components: {
      scrollbar
    },
    props: ['users'],
    data () {
      return {
        settings: {
          maxScrollbarLength: 60
        },
        userCount: this.users.length
      }
    },
    methods: {
      formatDate(date) {
          const newdate = new Date(date);
          if(!isNaN(newdate.getTime())) {
              return dateformat(newdate, 'hh:MM:ss TT  - dd/mm/yy');
          }
            else
              return ''
      },
      rowClicked (user) {
        this.$emit('userClicked',user)
      }
    },
    mounted() {
      $('#tabletest').DataTable({
        "scrollY":        "600px",
        "scrollCollapse": true,
        "paging":         false,
        "ordering": false,
        "info":     false
      });
    }
  }
</script>