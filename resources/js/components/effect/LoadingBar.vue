<template>
    <LoadingBar
		:on-error-done="errorDone"
		:on-progress-done="progressDone"
		:progress="progress"
		:direction="direction"
		:error="error" >
    </LoadingBar>
</template>
<script>
import LoadingBar from 'vue2-loading-bar'
export default {
    data(){
        return{
            progress: 0,
			error: false,
			direction: 'right'
        }
    },
    components:{LoadingBar},
    created() {
        this.$root.$refs.LoadingBar = this;
    },
    methods:{
        progressTo: function (val) {
          this.progress = val;
        },

        setToError: function (bol) {
          this.error = bol;
        },

        // Callback
        errorDone(){
          this.error = false
        },

        progressDone() {
          this.progress = 0
        },
        animateLoadingBar: function () {
            var me = this;
            me.progress = 10;
            for (var i = 0; i < 30; i++) {
            if(i > 20 && i < 29){
                setTimeout(function () {
                me.progress += 5;
                },50*i);
            }else{
                setTimeout(function () {
                me.progress ++;
                },10*i);
            }
            }
            setTimeout(function () {
                me.progress = 100;
            },1000);
        }
    }
    
}
</script>