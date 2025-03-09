<template>
  <div>
    <div id="content-header" class="clearfix">
      <div class="pull-left">
        <ol class="breadcrumb">
          <li><a href="/">Home</a></li>
          <li class="active"><span> Info. de Dispositivos</span></li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class=" col-md-12">
        <div class="main-box" >
          <div class="main-box-body" style="text-align: center; padding-bottom: 30px; padding-top: 15px;">

            <ul class="nav nav-tabs" role="tablist">
              <li role="presentation" class="active"><a href="#detalle" aria-controls="home" role="tab" data-toggle="tab">Detalles</a></li>
              <li role="presentation"><a href="#sync" aria-controls="profile" role="tab" data-toggle="tab">
                <span v-if="loading"><i class="fa fa-spinner fa-spin"></i></span> Historial de Sincronización
                <template v-if="selectedUser"> de
                  {{selectedUser.user.user_name}}
                </template>
              </a></li>
              <li role="presentation"><a href="#serial" aria-controls="seial" role="tab" data-toggle="tab">Seriales</a></li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div role="tabpanel" class="tab-pane active" id="detalle">
                <div class="row" style="margin-top: 15px">
                  <div class="col-sx-12" style="padding-left: 15px; padding-right: 15px">
                    <a :href="url">Descargar APK</a>
                    <br>
                  </div>
                  <div class="col-xs-12" style="padding-left: 15px; padding-right: 15px">
                    <h2>Detalles Generales de Dispositivos</h2>
                    <device-info-table
                            :users = usersData
                            @userClicked="handleUserClicked"
                    ></device-info-table>
                  </div>
                </div>
                <div class="row" style="margin-top: 25px">
                  <div class="col-xs-6 col-xs-offset-3">
                    <div class="alert alert-info fade in alert-dismissable">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                      <strong>Info</strong> Puede hacer click en un dispositivo para cargar detalles de sincronización
                    </div>
                  </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="sync">

                <div class="row"  style="margin-top: 15px">
                  <div class="col-xs-12">

                    <h2>Historial de Sincronización
                      <template v-if="currentUpdates"> de
                        {{selectedUser.user.user_name}}
                      </template>
                    </h2>
                    <small>Mostrando últimas 100</small>
                    <device-updates
                            :loading = loading
                            :updates = currentUpdates
                    ></device-updates>
                  </div>
                </div>

                <div class="row" style="margin-top: 25px">
                  <div class="col-xs-6 col-xs-offset-3">
                    <div class="alert alert-info fade in alert-dismissable">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                      <strong>Info</strong> Haga click en un registro para ver su detalle.
                    </div>
                  </div>
                </div>
              </div>
              <div role="tabpanel" class="tab-pane" id="serial">

                <div class="row"  style="margin-top: 15px">
                  <div class="col-md-12">
                    <device-serials-table
                            :serials = serials
                            @refreshserials="return_serials()"
                    ></device-serials-table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>


  </div>
</template>

<script>
  import axios from 'axios';
  export default {
    data () {
      return {
        usersData: usersData,
        selectedUser: null,
        currentUpdates: null,
        loading: false,
        serials:[],
        url:''
      }
    },
    methods: {
      handleUserClicked (user) {
        this.selectedUser = user;
        this.fetchDetails(user.user.user_id)
      },
      fetchDetails (userid) {
        this.loading = true;
        $.ajax({
          url: '/userdevices/details/'+userid,
          method: 'GET',
          success: (data) => {
            if (typeof data === 'string') {
              data = JSON.parse(data);
            }

            this.currentUpdates = data;
            this.loading = false;
          },
          error: (xhr) => {
            console.log(xhr)
          }
        });
      },
      return_serials(){
        this.loading = true;
        $.ajax({
          url: '/serialnumbers',
          method: 'GET',
          success: (data) => {
            if (typeof data === 'string') {
              data = JSON.parse(data);
            }

            this.serials = data;
            this.loading = false;
          },
          error: (xhr) => {
            console.log(xhr)
          }
        });
      },
      clienthost(){
        $.ajax({
          url: '/clienthost',
          method: 'GET',
          success: (data) => {
            console.log(data)
            this.url = data.url
          },
          error: (xhr) => {
            console.log(xhr)
          }
        });
      },
      new_tab(){
        window.open(this.url+'/update.json', '_blank');
      }

    },
    computed:{
    },
    mounted () {

    },
    created() {
      this.return_serials();
      this.clienthost();
    }
  }
</script>

<style scoped>

  .box-header {
    background-color: #03a9f4;
    padding: 15px;
    color: white;
    font-size: 17px;
    font-weight: 600;
    text-align: center;
  }
</style>
