<template>
    <div class="row">
        <div class=" col-xs-12 " >
            <div class="main-box match-height">
                <header class="main-box-header clearfix">
                    <h2 style="font-weight: 500" class="pull-left">Administrar Módulos de Usuarios</h2>
                </header>
                <hr style="margin-top:0">
                <div class="main-box-body clearfix" id="step5">
                    <div class="form-group">
                        <select class="form-control">
                            <option :value="u.user_id" v-for="u in baseData.users">{{u.seller_name}}</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                baseData: baseDataFromServer
            }
        }
    }
</script>