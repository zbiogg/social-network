<template>
  <div class="main_login">
    <div class="container">
      <loading-bar></loading-bar>
      <div class="scaffold-main">
        <div class="login-about pr-5 pl-5">
          <span>ZBIOGGG</span>
          <h2>Chào mừng bạn đến với ZBIOGGG :v</h2>
        </div>
        <div class="login-form mx-auto m-5">
          <ValidationObserver v-slot="{ handleSubmit }">
            <form
              action="/"
              method="POST"
              @submit.prevent="handleSubmit(login)"
            >
              <h2 class="login-title">Đăng nhập</h2>
              <div v-if="checkRegister" class="alert alert-success">
                Đăng ký thành công!
              </div>
              <ValidationProvider rules="required|min:2" v-slot="{ errors }">
                <input
                  v-model="user.username"
                  class="login-input"
                  :class="{ 'is-invalid': errors[0] }"
                  type="text"
                  name="username"
                  placeholder="Email hoặc số điện thoại"
                />
                <div v-if="errors[0]" class="invalid-feedback">
                  {{
                    errors[0] == "The username field is required"
                      ? "Vui lòng điền tên đăng nhập."
                      : "Tên đăng nhập phải nhiều hơn 2 ký tự."
                  }}
                  {{ errors[0] }}
                </div>
              </ValidationProvider>
              <ValidationProvider rules="required|min:1" v-slot="{ errors }">
                <input
                  v-model="user.password"
                  class="login-input"
                  :class="{ 'is-invalid': errors[0] }"
                  type="password"
                  name="password"
                  placeholder="Mật khẩu"
                />
                <div v-if="errors[0]" class="invalid-feedback">
                  {{
                    errors[0] == "The password field is required"
                      ? "Vui lòng điền mật khẩu."
                      : "Mật khẩu phải nhiều hơn 6 ký tự."
                  }}
                </div>
              </ValidationProvider>
              <div v-if="loginFail" class="text-danger mt-1">
                Tên đăng nhập hoặc mật khẩu không chính xác!
              </div>

              <div class="mt-1" align="right">
                <a class="forgot_pass" href="">Quên mật khẩu</a>
              </div>
              <button
                class="btn btn-success btn-lg btn_login mt-1"
                type="submit"
              >
                Đăng nhập
              </button>
              <div class="line_1px"></div>
            </form>
          </ValidationObserver>
          <div class="mt-2"></div>
      
          <facebook-login
            :class="'btn-login-facebook'"
            :loginLabel="'Đăng nhập bằng Facebook'"
            :logoutLabel="'Đang xác thực tài khoản'"
            appId="403363484643312"
            @login="onLoginFB"
            @logout="onlogoutFB"
            @sdk-loaded="sdkLoaded"
          >
          </facebook-login>
          <vue-loading
                v-if="checkID_FB"
                type="cylon"
                color="#385185"
                :size="{ width: '35px', height: '35px' }"
              ></vue-loading>
          <div class="mt-2">
            <span>Bạn chưa có tài khoản?</span>
          </div>
          <div class="mt-2">
            <button
              class="btn btn-outline-success btn-lg btn_register"
              @click="showModal = true"
            >
              Tạo tài khoản mới
            </button>
            <modal
              v-model="showModal"
              modalStyle="border-radius:6px; top: 62px"
              title="Tạo tài khoản"
              wrapper-class="animate__animated"
              :in-class="`animate__fadeInDown`"
              :out-class="`animate__fadeOutUp`"
              bg-class="animate__animated"
              :bg-in-class="`animate__fadeInUp`"
              :bg-out-class="`animate__fadeOutDown`"
            >
              <div style="padding: 5px 20px">
                <ValidationObserver v-slot="{ handleSubmit }">
                  <form
                    action="/"
                    method="POST"
                    @submit.prevent="handleSubmit(register)"
                  >
                    <ValidationProvider
                      rules="required|min:2|alpha_dash"
                      v-slot="{ errors }"
                    >
                      <input
                        v-model="registerInfo.username"
                        class="login-input"
                        :class="{ 'is-invalid': errors[0] }"
                        type="text"
                        name="username"
                        placeholder="Tên đăng nhập"
                      />
                      <div v-if="errors[0]" class="invalid-feedback">
                        {{
                          errors[0] == "The username field is required"
                            ? "Vui lòng điền tên đăng nhập."
                            : errors[0] ==
                              "The username field must be at least 2 characters"
                            ? "Tên đăng nhập phải ít nhất 2 ký tự"
                            : errors[0] ==
                              "The username field may contain alpha-numeric characters as well as dashes and underscores"
                            ? "Tên đăng nhập chỉ có thể chứa các ký tự chữ và số cũng như dấu gạch ngang và dấu gạch dưới."
                            : "Lỗi không xác định"
                        }}
                      </div>
                    </ValidationProvider>
                    <div class="scaffold-fill-register">
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <input
                          v-model="registerInfo.lastName"
                          class="login-input"
                          :class="{ 'is-invalid': errors[0] }"
                          type="text"
                          name="lastName"
                          placeholder="Họ"
                        />
                        <div v-if="errors[0]" class="invalid-feedback">
                          {{ "Vui lòng nhập họ của bạn." }}
                        </div>
                      </ValidationProvider>
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <input
                          v-model="registerInfo.firstName"
                          class="login-input"
                          :class="{ 'is-invalid': errors[0] }"
                          type="text"
                          name="firstName"
                          placeholder="Tên đệm và tên"
                        />
                        <div v-if="errors[0]" class="invalid-feedback">
                          {{ "Vui lòng nhập tên của bạn." }}
                        </div>
                      </ValidationProvider>
                    </div>
                    <div class="scaffold-fill-register">
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <select
                          :class="{ 'is-invalid': errors[0] }"
                          class="login-input"
                          name="gender"
                          v-model="registerInfo.gender"
                        >
                          <option disabled value="" selected>Giới tính</option>
                          <option value="Nam">Nam</option>
                          <option value="Nữ">Nữ</option>
                          <option value="Khác">Khác</option>
                        </select>
                        <div v-if="errors[0]" class="invalid-feedback">
                          <span
                            v-if="errors[0] == 'The gender field is required'"
                            >Vui lòng chọn giới tính</span
                          >
                        </div>
                      </ValidationProvider>
                      <ValidationProvider
                        rules="required|numeric|length:10"
                        v-slot="{ errors }"
                      >
                        <input
                          v-model="registerInfo.phone"
                          class="login-input"
                          :class="{ 'is-invalid': errors[0] }"
                          type="text"
                          name="phone"
                          placeholder="Số di động"
                        />
                        <div v-if="errors[0]" class="invalid-feedback">
                          <span
                            v-if="errors[0] == 'The phone field is required'"
                            >Vui lòng nhập số điện thoại</span
                          >
                          <span
                            v-if="
                              errors[0] == 'The phone field must be 10 long'
                            "
                            >Phải là số điện thoại 10 số</span
                          >
                          <span
                            v-if="
                              errors[0] ==
                              'The phone field may only contain numeric characters'
                            "
                            >Vui lòng nhập đúng định dạng số điện thoại</span
                          >

                          {{ errors[0] }}
                        </div>
                      </ValidationProvider>
                    </div>

                    <div class="scaffold-fill-register">
                      <span class="label-input">Ngày sinh:</span>
                      <ValidationProvider rules="required" v-slot="{ errors }">
                        <input
                          v-model="registerInfo.doB"
                          class="login-input"
                          :class="{ 'is-invalid': errors[0] }"
                          type="date"
                          name="doB"
                          placeholder="Ngày sinh của bạn"
                          onfocus="(this.type='date')"
                        />
                        <div v-if="errors[0]" class="invalid-feedback">
                          <span v-if="errors[0]">Ngày sinh không hợp lệ</span>
                        </div>
                      </ValidationProvider>
                    </div>
                    <ValidationProvider
                      rules="required|email"
                      v-slot="{ errors }"
                    >
                      <input
                        v-model="registerInfo.email"
                        class="login-input"
                        :class="{ 'is-invalid': errors[0] }"
                        type="text"
                        name="email"
                        placeholder="Email của bạn"
                      />
                      <div v-if="errors[0]" class="invalid-feedback">
                        <span
                          v-if="errors[0] == 'The username field is required'"
                          >Vui lòng nhập email của bạn</span
                        >
                        <span
                          v-else-if="
                            errors[0] == 'The email field must be a valid email'
                          "
                          >Định dạng email không đúng</span
                        >
                      </div>
                    </ValidationProvider>

                    <ValidationProvider
                      rules="required|min:8"
                      v-slot="{ errors }"
                    >
                      <input
                        v-model="registerInfo.password"
                        class="login-input"
                        :class="{ 'is-invalid': errors[0] }"
                        type="password"
                        name="password"
                        placeholder="Mật khẩu"
                      />
                      <div v-if="errors[0]" class="invalid-feedback">
                        {{
                          errors[0] == "The password field is required"
                            ? "Vui lòng điền mật khẩu."
                            : "Mật khẩu phải nhiều hơn 6 ký tự."
                        }}
                      </div>
                    </ValidationProvider>
                    <!-- <div v-if="loginFail" class="text-danger mt-1">
                      Tên đăng nhập hoặc mật khẩu không chính xác!
                    </div> -->
                    <button
                      class="btn btn-success btn-lg btn_login mt-1"
                      type="submit"
                      style="margin-top: 25px !important"
                    >
                      Đăng ký
                    </button>
                    <div class="line_1px"></div>
                  </form>
                </ValidationObserver>
              </div>
            </modal>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import LoadingBar from "../effect/LoadingBar.vue";
import BaseRequest from "../../cores/BaseRequest";
import facebookLogin from "facebook-login-vuejs";
export default {
  components: { LoadingBar, facebookLogin },
  data() {
    return {
      user: {
        username: "",
        password: "",
      },
      registerInfo: {
        username: null,
        firstName: null,
        lastName: null,
        email: null,
        doB: null,
        phone: null,
        password: null,
        confirmPassword: null,
        gender: "",
      },
      loginFail: false,
      systemError: false,
      showModal: false,
      checkRegister: false,
      FB: undefined,
      checkID_FB: false,
      isConnectedFB: false,
    };
  },
  // mounted(){
  // 	
  // },
  methods: {
    // checkLogin: function () {
    //   let self = this;
    //   BaseRequest.get("checklogin")
    //     .then(function (response) {
    //       if (response.data.success) {
    //         self.$router.replace({ name: "home" });
    //       }
    //     })
    //     .catch((error) => {
    //       console.log(error);
    //     });
    // },
    login: function () {
      BaseRequest.post("login", {
        username: this.user.username,
        password: this.user.password,
      })
        .then((response) => {
          if (response.data.success == true) {
            window.localStorage.setItem("token", response.data.token);
            this.$router.push({ name: "home" });
          } else {
            this.loginFail = true;
            this.user.username = "";
            this.user.password = "";
          }
        })
        .catch((error) => {
          console.log(error);
          this.systemError = true;
        });
    },
    register: function () {
      this.$root.$refs.LoadingBar.animateLoadingBar();
      BaseRequest.post("register", {
        email: this.registerInfo.email,
        password: this.registerInfo.password,
        firstName: this.registerInfo.firstName,
        lastName: this.registerInfo.lastName,
        doB: this.registerInfo.doB,
        username: this.registerInfo.username,
        gender: this.registerInfo.gender,
        phone: this.registerInfo.phone,
      })
        .then((response) => {
          if (response.data.success == true) {
            this.user.username = this.registerInfo.username;
            this.user.password = "";
            this.showModal = false;
            this.checkRegister = true;
          }
        })
        .catch((error) => {
          console.log(error);
        });
    },
    getUserDataFB() {
      this.FB.api(
        "/me",
        "GET",
        {
          fields:
            "id,name,email,picture,first_name,last_name,middle_name,gender",
        },
        (userInformation) => {
          console.log(userInformation);
          this.checkID_FB=true;
          BaseRequest.post("loginfb", {
            fbID: userInformation.id,
            firstName:
              userInformation.middle_name?userInformation.middle_name + " " + userInformation.first_name:userInformation.first_name,
            lastName: userInformation.last_name,
            email: userInformation.email,
          })
            .then((res) => {
              if(res.data.token!=null){
                window.localStorage.setItem("token", res.data.token);
                this.$router.push({ name: "home" });
                this.checkID_FB=false;
                
              }
            })
            .catch(console.log);
        }
      );
    },
    sdkLoaded(payload) {
      this.isConnectedFB = payload.isConnectedFB;
      this.FB = payload.FB;
    },
    onLoginFB() {
      this.isConnectedFB=true;
      this.getUserDataFB();
      this.FB.logout();
    },
    onlogoutFB() {
      this.isConnectedFB=false;
    },
  },
};
</script>
<style>
.scaffold-fill-register {
  display: grid;
  grid-template-columns: minmax(0, 6fr) minmax(0, 12fr);
  justify-content: space-between;
  column-gap: 15px;
}
.label-input {
  width: 100%;
  height: 50px;
  margin-top: 25px;
  display: flex;
  align-items: center;
}
.scaffold-main {
  display: flex;
  justify-content: center;
}

/*  */
.login-form {
  max-width: 450px;
  text-align: center;
}
.login-title {
  font-family: "Times New Roman", Times, serif;
  border-bottom: 1px solid #dadde1;
  font-weight: 600;
  padding-bottom: 10px;
  color: #5ac618;
}

.login-input {
  width: 100%;
  background-color: #f4f4f4;
  height: 50px;
  margin-top: 25px;
  padding: 10px;
  border: 1px solid #a9a9a9;
  border-radius: 6px;
}
.login-about {
  text-align: center;
  top: 0;
  bottom: 0;
  margin: auto;
}
.login-about span {
  font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
}
.login-about h2 {
  font-weight: 400;
}
@media screen and (max-width: 992px) {
  .scaffold-main {
    display: block !important;
  }
  .login-about h2 {
    font-size: 22px;
  }
}
.login-form {
  padding: 50px !important;
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0px 2px 4px rgb(0 0 0/10%), 0px 8px 16px rgb(0 0 0/10%);
}
.line-hr {
  height: 1px;
  background-color: #d2d2d2;
  margin: 0 0.75rem;
}
.forgot_pass {
  text-decoration: none;
  color: rgb(109, 109, 109);
  font-size: 14;
}
.forgot_pass:hover {
  color: #5ac618;
}
.login-about span {
  color: #76ff03;
  font-size: 300%;
  font-weight: bolder;
  text-shadow: 3px 3px 3px #fff;
}
.line_1px {
  padding: 8px;
  border-bottom: 1px solid #dadde1;
}
.btn_login {
  background-color: #5ac618;
  font-weight: bold;
  width: 100%;
}
.btn_login:hover {
  background-color: #83e445;
}
.btn_login:focus {
  background-color: #83e445;
}
.btn_register:hover {
  background-color: #ffc233;
}
.btn_register {
  color: #5ac618;
  font-weight: bold;
}
.LoadingBar {
  transition: all 0.3s ease;
  -moz-transition: all 0.3s ease;
  -webkit-transition: all 0.3s ease;
  -o-transition: all 0.3s ease;
  position: fixed;
  top: 0;
  background: #76ff03;
  height: 3px;
  opacity: 1;
}

.LoadingBar-glow {
  top: 0;
  position: absolute;
  width: 100px;
  height: 100%;
  box-shadow: -3px 0 15px 1px rgba(0, 255, 231, 0.4);
}

.LoadingBar--to_right {
  left: 0;
}

.LoadingBar--to_left,
.LoadingBar--to_right .LoadingBar-glow {
  right: 0;
}

.LoadingBar--to_left .LoadingBar-glow {
  left: 0;
}

.LoadingBar--full {
  transition: all 0.1s ease;
  -moz-transition: all 0.1s ease;
  -webkit-transition: all 0.1s ease;
  -o-transition: all 0.1s ease;
  height: 0;
  opacity: 0;
}

.LoadingBar--error {
  background: #f44336;
}

.LoadingBar--error .LoadingBar-glow {
  box-shadow: -3px 0 15px 1px rgba(244, 67, 54, 0.4);
}
@media screen and (max-width: 480px) {
  .login-form {
    padding: 30px !important;
  }
}
a .vue-avatar--wrapper,
a .vue-avatar--wrapper:hover {
  text-decoration: none;
}

.btn-login-facebook {
  padding: 0 !important;
}
.btn-login-facebook button {
  background-image: none !important;
  background-color: #fff;
  width: 100%;
  color: #385185!important;
  display: flex;
  padding: 5px 0!important;
  border-radius: 3px;
  align-items: center;
  justify-content: center;
}
.btn-login-facebook button img{
  background-color: #385185!important;
  position: relative!important;
  border-radius: 1.5px!important;
  top: 0;
  left: 0;
  width: 22px;
  box-shadow: 0 0 0 1px #385185;
  margin-right: 8px;
}
.btn-login-facebook button:hover {
  box-shadow: rgba(14, 30, 37, 0.12) 0px 2px 4px 0px,
    rgba(14, 30, 37, 0.32) 0px 2px 16px 0px;
  font-weight: 500;
}
.btn-login-facebook button .spinner{
  height: 30px!important;
}
</style>
<style scoped>
  @media screen and  (max-width: 480px) {
    .container{
      padding:  0!important;
    }
  }
</style>