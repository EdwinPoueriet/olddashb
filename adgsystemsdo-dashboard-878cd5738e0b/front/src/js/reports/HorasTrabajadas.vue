<template>
  <div>
    <div class="alert alert-info ">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
      <i class="fa fa-info-circle fa-fw fa-lg"></i>
      <strong >Reporte de Horas Trabajadas</strong>
      <ul>
        <li>
          Para este reporte funcionar correctamente debe de contar con la versión
          1.6.4, de lo contrario contactar con la oficina: 809-241-7309
        </li>
      </ul>

    </div>
    <div class="row" style="padding: 20px">
      <div class="col-xs-12">

        <div class="main-box" >
          <form action="/reports/horastrabajadas" method="post" target="_blank">
          <header class="main-box-header clearfix"  >
            <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Filtros</h2>
            <button type="submit" class="btn btn-success pull-right">
              <i class="fa fa-print fa-fw"></i>
              Imprimir
            </button>
          </header>
          <hr style="margin-top:0; margin-bottom: 5px">
          <div class="main-box-body ">

              <div class="row">
                <div class="col-xs-6">
                  <div class="form-group">
                    <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                      <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                    </div>
                    <label> Vendedores </label>
                    <select name="vendedor" @change="getData()"  class="form-control" v-model="selectedSellers" >
                      <option value="todos">Todos</option>

                      <option v-for="vendedor in vendedores"
                              :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>

                      <option v-for="vendedor in vendedores_inactivos"
                              v-if="showInactiveSellers"
                              style="background-color: #f2dede"
                              :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>
                    </select>
                    <!--<multiselect-->
                            <!--:options="sellersList"-->
                            <!--:multiple="true"-->
                            <!--@close="getData()"-->
                            <!--@remove="getData()"-->
                            <!--:close-on-select="false"-->
                            <!--:hide-selected="true"-->
                            <!--placeholder="Seleccione Vendedores"-->
                            <!--label="seller_name"-->
                            <!--track-by="seller_code"-->
                            <!--v-model="selectedSellers"-->
                    <!--&gt;</multiselect>-->
                  </div>
                </div>
                <div class="col-xs-3">
                  <div class="form-group" >
                    <label>Fecha</label>
                    <div class="filter-range-container" style="margin-top:3px;" >
                      <div class="input-group" >
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input autocomplete="off" type="text" class=" form-control" id="duracion-date-input" name="date">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!--<div class="row">-->
                <!--<div class="col-xs-12">-->
                  <!--<div style="display: block; margin-top: 15px" >-->
                    <!---->
                  <!--</div>-->
                <!--</div>-->
              <!--</div>-->
              <!--<template v-for="seller in selectedSellers">-->
                <input type="hidden" name="sellers" :value="selectedSellers">
              <!--</template>-->

          </div>
          </form>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xs-12">

        <div class="main-box" style="padding: 10px;">

          <template v-if="loading">
            <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i></div>
          </template>


          <table v-show="!loading" id="hourlywork"  style="margin: auto">
            <thead>
            <tr st>
              <th rowspan="2">Vendedores</th>
              <th colspan="3" style="text-align: center">Visitas</th>

            </tr>
            <tr>
              <th>Cantidad</th>
              <th class="text-center">Tiempo Total</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in data">
              <td>{{ item.name }}</td>
              <td>{{ item.count }}</td>
              <td  class="text-center">{{ item.hours }}</td>
            </tr>


            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>

</template>
<!--<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>-->
<script>

  var table =  $('#hourlywork').DataTable();
  export default {
    data () {
      return {
        vendedores: sellersList,
        vendedores_inactivos: sellersListInactive,
        showInactiveSellers: false,
        orden: 'se.seller_code',
        orden_method: 'desc',
        selectedSellers: [],
        fecha: moment().format('YYYY-MM-DD'),
        data: [],
        loading: false,
        selectedSellers: 'todos'
      }
    },
    methods: {
      getData(){
        // this.loading = true;
        table.destroy();
        $.ajax({

          url: '/getreport/hourlywork',
          method: 'POST',
          data: {
            'sellers': this.selectedSellers ,
            'date': this.fecha,
            'order_by': this.orden,
            'order_method': this.orden_method
          },
          success:  (data) => {
            this.data = data;


          },
          complete: ()=>{
            setTimeout(()=>{
              // this.loading = false;
              table =  $('#hourlywork').DataTable({
                // "ordering": false,
                // 'iDisplayLength': 25,
                "info":     false


              });

            }, 2000)


          }

        })

      }

    },
    computed: {
      sellersList () {
        if(this.showInactiveSellers) {
          return this.vendedores.concat(this.vendedores_inactivos)
        } else {
          return this.vendedores;
        }
      }
    },
    mounted () {
      this.getData()
      let date = $('#duracion-date-input');
      date.daterangepicker({
        "applyClass": "btn-primary",
        singleDatePicker: true,
        startDate: moment(),
        locale: {
          format: 'YYYY-MM-DD',
          "daysOfWeek": [
            "Do",
            "Lu",
            "Ma",
            "Mi",
            "Ju",
            "Vi",
            "Sa"
          ],
          "applyLabel": "Aplicar",
          "cancelLabel": "Cancelar",
          "monthNames": [
            "Enero",
            "Febrero",
            "Marzo",
            "Abril",
            "Mayo",
            "Junio",
            "Julio",
            "Agosto",
            "Septiembre",
            "Octubre",
            "Noviembre",
            "Diciembre"
          ]
        }
      });
      date.on('apply.daterangepicker', (ev, picker) => {
        this.fecha = picker.startDate.format('YYYY-MM-DD');
        this.getData();
      });
    }


  }
</script>
