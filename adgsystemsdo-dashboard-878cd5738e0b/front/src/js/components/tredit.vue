<template>
    <tr>
        <td>{{data.user_serial_number}}</td>
        <template v-if="edit">
            <td>
                <select class="form-control" v-model="data.user_id">
                    <option value="0">--Seleccionar--</option>
                    <option v-for="user in  users" :value="user.user_id">{{user.user_name}}</option>
                </select>
            </td>
            <td style="width: 40%">
                <input type="text" class="form-control" v-model="data.user_related_imei">
            </td>
            <td style="width: 20%;">
                <button type="button" class="btn btn-sm btn-danger" @click="refresh"><i style="font-size: 18px" class="fa fa-undo"></i></button>
                <button type="button" class="btn btn-sm btn-warning" @click="limpiar"><i style="font-size: 18px" class="fa fa-trash"></i></button>
                <button type="button" class="btn btn-sm btn-primary" @click="editing"><i style="font-size: 18px" class="fa fa-save"></i></button>
            </td>
        </template>
        <template v-else>
            <td>{{username(data.user_id)}}</td>
            <td>{{data.user_related_imei}}</td>
            <td><button type="button" class="btn btn-sm btn-warning" @click="editing"><i style="font-size: 18px" class="fa fa-edit"></i></button></td>
        </template>
    </tr>
</template>

<script>
  export default {
    props:['data', 'users', 'available'],
    data(){
      return {
        edit:false,
        user:"",
        imei:""
      }
    },
    methods:{
      refresh(){
        this.edit = false;
        this.$emit('refresh', {
          status:true
        })
      },
      limpiar(){
        this.data.user_id = 0;
        this.data.user_related_imei = "";
      },
      editing(){
        if(this.available && !this.edit){
          this.edit = !this.edit;
          this.$emit("changestatus",  {
            status: false
          });
        }else if(!this.available && this.edit){
          this.$emit("changestatus", {
            status: true,
            data:this.data
          });
          this.edit = !this.edit;
        }
      },
      username(val){
        var found =  this.users.find(e => e.user_id == val)
        if(found){
          return found.user_name;
        }
      }

    }
  }
</script>

<style scoped>

</style>
