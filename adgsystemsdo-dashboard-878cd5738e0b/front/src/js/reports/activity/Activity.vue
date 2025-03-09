<template>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box" >
                    <div class="main-box-header clearfix"  >
                        <h2 style="color: #03a9f4; font-weight: 500" class="pull-left">Filtros</h2>
                        <template v-if="secondLoading">
                        <div > <i style="    font-size: 19px;
    margin-left: 10px;" class="fa fa-spinner fa-spin"></i></div>
                        </template>
                        <form action="/legacy/print/activity" target="_blank" method="POST">
                            <input type="hidden" :value="selectedSeller" name="seller_code">
                            <input type="hidden" :value="selectedCustomer" name="customer_code">
                            <input type="hidden" :value="rango" name="rango">
                            <div  style="float: right; display: inline-block;">
                                <div v-if="radio == 2" style="display: inline-block;" class="filter-value-container form-inline" >
                                    <label for="pr">Metodo de orden</label>
                                    <select name="OrderMethod" id="pr"   class="form-control" v-model="OrderMethod" >
                                        <option value="asc">Ascendiente</option>
                                        <option value="desc">Descendiente</option>
                                    </select>
                                </div>
                                <div  class="form-check" style="display: inline-block;">
                                    <input  type="radio" name="printOption" v-model="radio" id="exampleRadios1" value="1" checked="checked" class="form-check-input"> <label for="exampleRadios1" class="form-check-label">
                                    Resumido
                                </label></div> <div class="form-check" style="
    display: inline-block;
"><input  type="radio" name="printOption" id="exampleRadios2" v-model="radio" value="2" class="form-check-input"> <label for="exampleRadios2" class="form-check-label">
                                Detallado
                            </label></div>


                                <button  type="submit" class="btn btn-success pull-right" style="margin-left: 5px;"><i  class="fa fa-print fa-fw"></i>
                                Imprimir
                            </button></div>
                        </form>

                    </div>
                    <hr style="margin-top:0; margin-bottom: 5px">
                    <div class="main-box-body ">
                        <div class="row" style="padding: 10px">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Rango de Fecha</label>
                                    <div class="filter-range-container" style="margin-top:3px;" >
                                        <div class="input-group" >
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input  type="text" class="form-control" id="date-input" autocomplete="off">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <div v-if="vendedores_inactivos !== null && vendedores_inactivos.length > 0" class="checkbox" style="float: right; margin: 0; ">
                                        <label><input v-model="showInactiveSellers" style="bottom: 4px" type="checkbox" value="">Mostrar Inactivos</label>
                                    </div>
                                    <label>Relacionado vendedor</label>
                                    <div class="filter-value-container" style="margin-top:3px;">
                                        <select name="vendedor"   class="form-control" v-model="selectedSeller" >
                                            <option value="0">Todos</option>

                                            <option v-for="vendedor in vendedores"
                                                    :value="vendedor.seller_code">{{ vendedor.seller_code }} -  {{ vendedor.seller_name }}</option>

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label>Relacionado a Cliente</label>
                                    <div class="filter-value-container" style="margin-top:3px;">
                                        <select  style="margin-top: 3px;" v-model="selectedCustomer" class="form-control" >
                                            <option value="0">Todos</option>
                                            <option v-for="item in customers" :value="item.customer_code">{{ item.customer_name }}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="showPrintOptions" style=" background-color: #ebedee; border-radius: 10px; margin-top: 5px;padding: 10px">
                            <h4>Opciones de Impresión</h4>
                            <form action="/legacy/print/activity" method="POST">
                                <input type="hidden" :value="selectedSeller" name="seller_code">
                                <input type="hidden" :value="selectedCustomer" name="customer_code">
                                <input type="hidden" :value="rango" name="rango">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="form-group">
                                            <label>Tipo de actividad</label>
                                            <div class="filter-value-container" style="margin-top:3px;">
                                                <select name="filter" class="form-control" >
                                                    <option value="1">Pedidos</option>
                                                    <option value="2">Cotizaciones</option>
                                                    <option value="3">Facturas</option>
                                                    <option value="4">Devoluciones</option>
                                                    <option value="5">Visitas</option>
                                                    <option value="6">Cobros</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row" >
                                    <div class="col-xs-12">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-print fa-fw"></i> Imprimir</button>
                                        <button type="button" class="btn btn-default" @click="showPrintOptions = false">Volver</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="main-box" >

                    <hr style="margin-top:0; margin-bottom: 5px">
                    <div class="main-box-body ">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Pedidos</span>
                                    <span id="pedidos"  class="value">{{ orderCount }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Cobros</span>
                                    <span id="cobros"  class="value">{{ receivableCount }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Visita no Venta</span>
                                    <span id="visitas"  class="value">{{ visitCount }}</span>
                                </div>
                            </div>



                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Cotizaciones</span>
                                    <span id="cotizaciones"  class="value">{{ quotationCount }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Devoluciones</span>
                                    <span id="devoluciones"  class="value">{{ devolutionCount }}</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="main-box infographic-box" style=" box-shadow: rgba(0, 66, 251, 0.34) 0px 1px 10px;">
                                    <span style="color: rgb(218, 173, 134)" class="card-title" >Deposito Bancario</span>
                                    <span id="bank"  class="value">{{ bank }}</span>
                                </div>
                            </div>



                        </div>
                        <ul class="nav nav-pills nav-justified">
                            <li class="nav-item">
                                <a class="nav-link" :class="{'active': compact}" @click="changeTabR"  >Resumido</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" :class="{'active': !compact}" @click="changeTabD"  >Detallado</a>
                            </li>
                        </ul>
                        <div v-if="compact" class="row" style="padding: 10px">
                            <div class="col-sm-4"  v-for="item in listActiviti">
                                <div class="card" style="margin-bottom: 10px">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ item[0] }}</h5>
                                        <!--<h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>-->
                                        <table class="table table-responsive">
                                            <tbody>
                                            <tr>
                                                <th scope="row">Pedidos:</th>
                                                <td>{{ item[1].orderi }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Cotizaciones:</th>
                                                <td>{{ item[2].quotation }}</td>
                                            </tr>
                                            <!--<tr>-->
                                            <!--<th scope="row">Facturas:</th>-->
                                            <!--<td>{{ item[3].invoices }}</td>-->
                                            <!--</tr>-->
                                            <tr>
                                                <th scope="row">Devoluciones:</th>
                                                <td>{{ item[4].devo }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Visita No Ventas :</th>
                                                <td>{{ item[5].visit }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Cobros:</th>
                                                <td>{{ item[3].receivable }}</td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Deposito Bancario:</th>
                                                <td>{{ item[6].bank }}</td>
                                            </tr>
                                            </tbody>
                                        </table>

                                        <!--<a href="#" class="card-link">Card link</a>-->
                                        <!--<a href="#" class="card-link">Another link</a>-->
                                    </div>
                                </div>
                            </div>

                        </div>

                        <template v-if="loading">
                            <div style="text-align: center"> <i style="font-size: 30px" class="fa fa-spinner fa-spin"></i></div>
                        </template>
                        <div v-show="compact == false" class="row">
                            <!--<div class="col-xs-6 col-xs-offset-6">-->

                            <table id="tabletest" class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Fecha</th>
                                    <th scope="col">Vendedor</th>

                                    <th scope="col">Tipo de Doc.</th>
                                    <th scope="col">No. Documento</th>
                                    <th><i style="font-size: 16px" class="fa fa-map-marker"></i></th>
                                    <!--<th scope="col">En sitio</th>-->
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="item in data">

                                    <th scope="row">{{ moment(item.date_time)  }}</th>
                                    <th>{{ item.seller_code }} - {{ item.seller_name }}</th>
                                    <td>{{ item.table_indentify }}</td>
                                    <td>{{ item.noDoc }}</td>
                                    <td>{{ item.in_location }}</td>
                                    <!--<td>N/A</td>-->
                                </tr>

                                </tbody>
                            </table>


                            <!--</div>-->
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</template>

<style>
    .border-primary{
        border-color: #007bff !important;
    }
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-body {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.25rem;
    }
    .mb-3, .my-3 {
        margin-bottom: 1rem!important;
    }
    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
    }
    .nav-pills .nav-link.active, .nav-pills .show>.nav-link {
        color: #fff;
        background-color: #007bff;
    }


</style>


<script>


  var table =  $('#tabletest').DataTable();

  export default {
    name: "Activity",
    data (){

      return {
        selectedSeller: '0',
        rango:  moment().format('YYYY-MM-DD') + ' - ' + moment().format('YYYY-MM-DD'),
        selectedCustomer: '0',
        vendedores_inactivos: sellersList,
        vendedores: sellersList,
        customers: customers,
        showInactiveSellers: false,
        orderCount: 0,
        OrderMethod: 'asc',
        listActiviti: [],
        compact : true,
        quotationCount: 0,
        // invoiceCount: 0,
        receivableCount: 0,
        devolutionCount: 0,
        bank: 0,
        visitCount: 0,
        showPrintOptions: false,
        data: [],
        radio: '1',
        secondLoading: false,
        loading: false,

      }
    },
    watch: {
      selectedSeller(){
        this.getCountActivity();


      },
      selectedCustomer(){
        this.getCountActivity();
      }


    },


    methods: {
      moment: function (date) {
        return  moment(date, 'YYYY-MM-DD h:mm:ss').format('YYYY-MM-DD h:mm a')
      },
      changeTabR(){
        this.compact = true;
        this.getSellerActivity();

      },
      changeTabD(){
        this.compact = false;
        // if (this.data.length == 0){
          this.getData()

        // }
      },
      getData(){
        this.loading = true;
        $.ajax({
          url: '/reports/activity/detailed',
          method: 'POST',
          data: {
            seller: this.selectedSeller,
            rango: this.rango,
            customer: this.selectedCustomer,
          },
          success:  data => {

            console.log(data);
            this.data = data;


          },
          complete: ()=> {
            this.loading = false;
            this.secondLoading = false;
            table.destroy();
            setTimeout(()=>{
              // table.destroy();
              table =  $('#tabletest').DataTable({
                // "ordering": false,
                'iDisplayLength': 25,
                "info":     false


              });


            }, 2000)


          }


        })


      },



      getCountActivity(){
        this.secondLoading = true;

        $.ajax({
          url: '/reports/activity/count',
          method: 'POST',
          data: {
            seller: this.selectedSeller,
            rango: this.rango,
            customer: this.selectedCustomer,
            type: 1,
          },
          success:  data => {

            this.orderCount = data[0][0].orderi;
            this.quotationCount = data[1][0].quotation;
            // this.invoiceCount = data[2][0].invoices;
            this.receivableCount = data[2][0].receivable;
            this.devolutionCount = data[3][0].devo;
            this.visitCount = data[4][0].visit;
            this.bank = data[5][0].bank

          },
          complete: ()=> {

            if (this.compact == false){

              this.getData();
            }else{
              this.getSellerActivity();
            }
            // this.$emit('loading', false);
            // this.
          }


        })

      },

      getSellerActivity(){
        $.ajax({
          url: '/reports/activity/sellers',
          method: 'POST',
          data: {
            seller: this.selectedSeller,
            customer: this.selectedCustomer,
            rango: this.rango,
          },
          success:  data => {

            this.listActiviti = data.data;

          },
          complete: ()=> {
            this.secondLoading = false;
            // this.$emit('loading', false);
          }


        })

      },

      setupBasico () {
        let date = $('#date-input');
        date.daterangepicker({
          "applyClass": "btn-primary",
          startDate: moment(),
          ranges: {
            'Hoy': [moment(), moment()],
            'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Ultimos 7 dias':[moment().subtract(6, 'days'), moment()],
            'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
            'Este mes': [moment().startOf('month'), moment().endOf('month')],
            'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          locale: {
            format: 'YYYY-MM-DD',
            applyLabel: 'Aceptar',
            cancelLabel: 'Borrar',
            fromLabel: 'Desde',
            toLabel: 'Hasta',
            customRangeLabel: 'Personalizado',
            daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            firstDay: 1
          }
        });
        date.on('apply.daterangepicker', (ev, picker) => {
          this.rango = picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD');
          this.getCountActivity();
          // if (this.compact == false){
          //
          //   this.getData()
          // }else{
          //   this.getSellerActivity();
          // }



        });
      }
    },
    mounted() {
      this.setupBasico();
      // this.getSellerActivity();
      this.getCountActivity();
    }
  }
</script>

<style scoped>

</style>
