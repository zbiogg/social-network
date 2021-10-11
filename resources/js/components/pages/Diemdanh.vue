<template>
   <div>
       <loadingbar></loadingbar>
        <zbio-header></zbio-header>
       <h1 align="center">Điểm danh VKU</h1>
    </div> 
</template>
<script>
import LoadingBar from '../effect/LoadingBar.vue';
import Header from '../layout/Header.vue';
export default {
    components: {
      'loadingbar': LoadingBar,
      'zbio-header': Header 
    }
}
</script>
<style scoped>

</style>