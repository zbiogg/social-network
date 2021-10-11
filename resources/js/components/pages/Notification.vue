<template>
   <div>
       <loadingbar></loadingbar>
        <zbio-header></zbio-header>
        <h1 align="center" style="margin-top: 80px">THÔNG BÁO</h1>
       <div class="alert alert-warning" align="center" role="alert">
        Coi như là Bug đi
        </div>
    </div> 
</template>
<script>
import BaseRequest from '../../cores/BaseRequest';
import LoadingBar from '../effect/LoadingBar.vue';
import Header from '../layout/Header.vue';
export default {
    components: {
      'loadingbar': LoadingBar,
      'zbio-header': Header 
    },
    // mounted(){
    //     this.checkLogin();
    //     this.$root.$refs.LoadingBar.animateLoadingBar();
    // },
    methods:{
        checkLogin: function(){
            BaseRequest.get('checklogin')
            .then(function (response){    
                if(response.data.success==false){
                    this.$router.push({name:'login'});
                }
            })
            .catch(()=>{
                this.$router.push({name:'login'});
            })
        }
    }
}
</script>
<style scoped>

</style>