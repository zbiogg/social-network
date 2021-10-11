<template>
  <div id="app">
    <facebook-login
      :loginLabel="'Đăng nhập bằng Facebook'"
      appId="222743853205711"
      @login="onLoginFB"
      @logout="onLogoutFB"
      @sdk-loaded="sdkLoaded">
    </facebook-login>
    
 
  </div>
</template>

<script>
import facebookLogin from 'facebook-login-vuejs';

export default {
  name: 'app',
  data() {
    return {
      isConnected: false,
      FB: undefined
    }
  },
  components: { facebookLogin },
  methods: {
    getUserData() {
      this.FB.api('/me', 'GET', { fields: 'id,name,email,picture,middle_name,gender'},
        userInformation => {
          console.log(userInformation);
          this.FB.logout();
        }
      )
    },
    sdkLoaded(payload) {
      this.isConnected = payload.isConnected
      this.FB = payload.FB
      if (this.isConnected) this.getUserData()
    },
    onLoginFB() {
      this.isConnected = true
      this.getUserData()
    },
    onLogoutFB() {
      this.isConnected = false;
    }
  }
}
</script>

<style>
#app {
  display: flex;
  flex-direction: column;
  align-items: flex-start
}
.information {
  margin-top: 100px;
  margin: auto;
  display: flex;
  flex-direction: column;
}
.well {
  background-color: rgb(191, 238, 229);
  margin: auto;
  padding: 50px 50px;
  ;
  border-radius: 20px;
  /* display:inline-block; */
}
.login {
  width: 200px;
  margin: auto;
}
.list-item:first-child {
  margin: 0;
}
.list-item {
  display: flex;
  align-items: center;
  margin-top: 20px;
}
.button {
  margin: auto;
}
</style>