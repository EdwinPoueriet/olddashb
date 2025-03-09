<template>
    <div>
        <table id="tabletest" class="table table-bordered table-condensed device-details table-striped table-hover">
            <thead>
            <tr>
                <th>Seriales</th>
                <th>Users</th>
                <th>IMEI</th>
                <th></th>
            </tr>
            </thead>

            <tbody>
            <template v-for="(serial, index) in serials">
                <tr-edit :data="serial" :available="available" @refresh="refresh" @changestatus="changeavaiable" :users="users" :key="index"></tr-edit>
            </template>
            </tbody>

        </table>
    </div>
</template>

<script>
  export default {
    props:['serials'],
    data(){
      return{
        available:true,
        loading:false,
        users:[]
      }
    },
    methods:{
      refresh({status}){
        console.log(status);
        this.available = status;
        this.$emit('refreshserials');
      },
      changeavaiable({status,data}){
        this.available = status;
        if(status && data){
          $.ajax({
            url: '/serialnumbers',
            data:data,
            method: 'post',
            success: (data) => {
              console.log("Edited");
            },
            error: (xhr) => {
              console.log(xhr)
            }
          });
          return;
        }
      },
      return_serials(){
        this.loading = true;
        $.ajax({
          url: '/users/list',
          method: 'GET',
          success: (data) => {
            if (typeof data === 'string') {
              data = JSON.parse(data);
            }

            this.users = data;
            this.loading = false;
          },
          error: (xhr) => {
            console.log(xhr)
          }
        });
      }
    }, created() {
      this.return_serials();
    }
  }
</script>

<style scoped>

</style>
