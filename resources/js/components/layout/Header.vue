<template>
  <header>
    <div
      class="bg-offset-search-bar"
      v-if="showResultSearch"
      @focus="showResultSearch = false"
      tabindex="0"
    ></div>
    <loadingbar></loadingbar>
    <nav class="container" @focus="showResultSearch = false" tabindex="0">
      <div class="branding">
        <router-link class="header" to="/">ZBIOGG</router-link>
      </div>
      <div class="scaffold-search-bar">
        <div id="custom-search-input">
          <div class="input-group col-md-12">
            <input
              @keyup.enter="toPageSearch"
              @focus="showResultSearch = true"
              v-model="keySearch"
              type="text"
              class="form-control input-lg"
              placeholder="Tìm kiếm"
            />
            <span class="input-group-btn" @click="toPageSearch">
              <button class="btn btn-info btn-lg" type="button">
                <icon
                  name="search"
                  width="20px"
                  height="20px"
                  color="#5bb810"
                ></icon>
              </button>
            </span>
          </div>
        </div>
        <div
          class="scaffold-result-search"
          v-if="keySearch.length"
          v-show="showResultSearch"
          @focusout="showResultSearch = false"
          tabindex="0"
        >
          <search-item
            v-for="(r, index) in resultSearch"
            :key="index"
            :userID="r.id"
            :userFullname="r.lastName + ' ' + r.firstName"
            :userAvt="r.img_avt"
            :mutual_friends="r.mutual_friends"
            :status_friend="r.status_friend"
          ></search-item>
          <div v-if="showLoadingSearch">
            <vue-loading
              type="cylon"
              color="#5bb810"
              :size="{ width: '30px', height: '30px' }"
            ></vue-loading>
          </div>
          <div class="btn-search-submit" v-if="keySearch.length" @click="toPageSearch">
            <div class="scaffold-search-user-item">
              <div class="search-user-item-info">
                <div class="icon-search-green">
                  <icon
                    name="search"
                    width="16px"
                    height="16px"
                    color="#5bb810"
                  ></icon>
                </div>

                <span class="title-button-search ml-2" 
                  >Tìm kiếm <b>{{ keySearch }}</b></span
                >
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="nav-links">
        <ul>
          <router-link
            @click.native="loadingbar"
            class="link"
            to="/"
            alt="home"
          >
            <icon name="home" width="24px" height="24px"></icon>
          </router-link>
          <router-link
            @click.native="loadingbar"
            class="link"
            to="/messenger"
            alt="mess"
          >
            <icon name="messenger-regular" width="24px" height="24px"></icon>
          </router-link>
          <router-link
            @click.native="loadingbar"
            class="link"
            to="/notification"
            alt="noti"
          >
            <icon name="find" width="24px" height="24px"></icon>
          </router-link>
          <router-link
            @click.native="loadingbar"
            class="link"
            to="/notification"
            alt="noti"
          >
            <icon name="instagram" width="24px" height="24px"></icon>
          </router-link>
          <router-link
            @click.native="loadingbar"
            class="link"
            :to="userAuth ? '/profile/' + userAuth.id : '/profile/'"
          >
            <div class="wrap-avt">
              <avatar
                :size="25"
                :username="user ? user.firstName : '          '"
                :src="userAuth_Avt ? userAuth_Avt : '/img/avt/avt-default.png'"
              ></avatar>
            </div>
          </router-link>
          <label
            class="drop-header-desktop"
            @click="showDropDesktop = !showDropDesktop"
            v-show="!mobile"
          >
            <icon name="sort-down" width="24px" height="24px"></icon>
            <div
              v-if="showDropDesktop"
              class="option-drop-header"
              
            >
              <!--  -->
              <router-link
                class="scaffold-link-to-profile"
                :to="userAuth ? '/profile/' + userAuth.id : '/profile/'"
              >
                
                  <avatar
                    :size="50"
                    :username="user ? user.firstName : '          '"
                    :src="
                      userAuth_Avt ? userAuth_Avt : '/img/avt/avt-default.png'
                    "
                  ></avatar>

                  <div class="mx-2">
                    <span class="name-myprofile"
                      >{{ userAuth.lastName }} {{ userAuth.firstName }}</span
                    >

                    <div class="title-to-profile">
                      <span>Xem trang cá nhân của bạn</span>
                    </div>
                  </div>
             
              </router-link>
              <!--  -->
              <hr>
              <div class="logout-profile" @click="logout">
                <icon
                  name="sign-out-alt"
                  width="30px"
                  height="30px"
                  color="#5bb810"
                ></icon>
                <span class="title-for-logout">Đăng xuất</span>
              </div>
            </div>
          </label>
        </ul>
      </div>
    </nav>
    <div class="menu-icon" @click="toogleMobileNav" v-show="mobile">
      <img src="../../assets/icons/menu.svg" alt="" />
    </div>

    <transition name="mobile-nav">
      <ul class="mobile-nav" v-show="mobileNav">
        <div class="scaffold-search-mobile-nav">
          <div id="custom-search-input">
            <div class="input-group col-md-12">
              <input
                type="text"
                class="form-control input-lg"
                placeholder="Tìm kiếm"
              />
              <span class="input-group-btn">
                <button class="btn btn-info btn-lg" type="submit">
                  <icon
                    name="search"
                    width="20px"
                    height="20px"
                    color="#5bb810"
                  ></icon>
                </button>
              </span>
            </div>
          </div>
        </div>
        <router-link @click.native="loadingbar" class="link" to="/" alt="home">
          <icon name="home" width="24px" height="24px"></icon>
          <span class="m-2">Trang chủ</span>
        </router-link>
        <router-link
          @click.native="loadingbar"
          class="link"
          to="/messenger"
          alt="mess"
        >
          <icon name="messenger-regular" width="24px" height="24px"></icon>
          <span class="m-2">Tin nhắn</span>
        </router-link>
        <router-link
          @click.native="loadingbar"
          class="link"
          to="notification"
          alt="noti"
        >
          <icon name="find" width="24px" height="24px"></icon>
          <span class="m-2">Thông báo</span>
        </router-link>
        <!-- <router-link @click.native="loadingbar" class="link" to="profile">
                    <avatar :size="24"  :src=images.imgAvt></avatar>
                </router-link> -->
        <span
          @click="logout"
          class="link btn-logout"
          to="notification"
          alt="noti"
        >
          <icon name="sign-out-alt" width="24px" height="24px"></icon>
          <span class="m-2">Đăng xuất</span>
        </span>
      </ul>
    </transition>
  </header>
</template>
<script>
import BaseRequest from "../../cores/BaseRequest";
import LoadingBar from "../effect/LoadingBar.vue";
import Avatar from "vue-avatar";
import { mapGetters, mapActions } from "vuex";
import debounce from "debounce";
import ResultSearchBar from "../childs/ResultSearchBar.vue";
// import f7-searchbar from 'framework7-vue';
export default {
  data() {
    return {
      images: {
        imgHome: require("../../assets/img/home.svg"),
        imgMess: require("../../assets/img/mess.svg"),
        imgNoti: require("../../assets/img/noti.svg"),
      },
      mobile: null,
      mobileNav: null,
      windowWidth: null,
      user: null,
      showDropDesktop: false,
      keySearch: "",
      showResultSearch: false,
      showLoadingSearch: false,
      resultSearch: null,
    };
  },
  watch: {
    keySearch(key) {
      if (key.length) {
        this.resultSearch = null;
        this.searchBar(key);
      } else {
        this.resultSearch = null;
      }
    },
    $route() {
      this.showResultSearch = false;
    },
  },
  components: {
    Avatar,
    "search-item": ResultSearchBar,
    // menuIcon,
    // Framework7Vue,
    loadingbar: LoadingBar,
  },
  computed: {
    ...mapGetters(["userAuth", "userAuth_Avt"]),
  },
  created() {
    window.addEventListener("resize", this.checkScreen);
    this.checkScreen();
    this.searchBar = debounce(this.searchBar, 500);
  },
  mounted() {
    this.getUserAuth();
  },
  methods: {
    ...mapActions(["getUserAuth"]),
    logout: function () {
      let self = this;
      BaseRequest.get("logout")
        .then(function (response) {
          localStorage.removeItem("token");
          self.$router.go(self.$router.currentRoute);
        })
        .catch(function (error) {
          console.log(error);
        });
    },
    loadingbar: function () {
      this.haha = "ok";
      this.$root.$refs.LoadingBar.animateLoadingBar();
    },
    checkScreen() {
      this.windowWidth = window.innerWidth;
      if (this.windowWidth <= 768) {
        this.mobile = true;
        return;
      }
      this.mobile = false;
      this.mobileNav = false;
      return;
    },
    toogleMobileNav() {
      this.mobileNav = !this.mobileNav;
    },
    searchBar(key) {
      this.showLoadingSearch = true;
      BaseRequest.get("search?key_search=" + key)
        .then((res) => {
          this.resultSearch = res.data.search_users;
          this.showLoadingSearch = false;
        })
        .catch(console.log);
    },
    hideResultSearch() {
      this.showResultSearch = false;
    },
    toPageSearch(){
      if(this.keySearch.length!=0){
        if(this.$router.name!='search')
         this.$router.push("/search?key_search="+this.keySearch);
      }
     
    }
  },
};
</script>
<style scoped>
header {
  position: fixed;
  width: 100%;
  /* background-color: #fff; */
  background-color: #ffffff;
  /* background-image: linear-gradient(315deg,  rgb(114, 211, 35) 0%,#ffffff 100%); */
  padding: 0 25px;
  border-bottom: 0.5px solid #dbdbdb;
  /* box-shadow: 0 2px 3px -1px rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.06); */
  z-index: 1000;
  top: 0;
  left: 0;
  height: 60px;
}
.link {
  font-weight: 500;
  margin: 2px 10px;
  transition: 0.3s color ease;
  align-items: center;
  text-decoration: none;
  line-height: 1.5 !important;
  color: rgba(37, 35, 35, 0.944);
  height: 25.5px;
  display: flex;
}
.link:hover {
  color: #5bb810;
}
nav {
  height: 60px;
  display: flex;
  padding: 12px 0;
  justify-content: space-between;
}
nav .branding {
  display: flex;
  align-items: center;
}
.nav-links ul {
  margin: 0;
  display: flex;
  padding-inline-start: 0 !important;
}

@media screen and (max-width: 768px) {
  .nav-links {
    margin-right: 35px;
  }

  .scaffold-search-bar {
    display: none;
  }
}
.nav-links {
  display: flex;
  align-items: center;
}
.branding .header {
  font-weight: 600;
  font-size: 24px;
  color: #5bb810;
  text-decoration: none;
  font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
}
.menu-icon {
  cursor: pointer;
  position: absolute;
  top: 16px;
  right: 25px;
  height: 24px;
  width: 24px;
}

.menu-icon img {
  width: 100%;
}
.mobile-nav {
  padding: 20px 0;
  width: 80%;
  max-width: 250px;
  display: flex;
  flex-direction: column;
  position: fixed;
  height: 100%;
  background-color: rgb(114, 211, 35);
  top: 0;
  left: 0;
}
.mobile-nav-enter-active,
.mobile-nav-leave-active {
  transition: all 0.75s ease;
}
.mobile-nav-enter {
  transform: translateX(-250px);
}
.mobile-nav-enter-to {
  transform: translateX(0);
}
.mobile-nav-leave-to {
  transform: translateX(-250px);
}
.router-link-exact-active .home,
.router-link-exact-active .messenger-regular,
.router-link-exact-active .find {
  color: #5bb810;
}
/* .router-link-exact-active icon{
        content:url("../../assets/img/home_.svg");
    } */
/* .router-link-exact-active img[alt~="mess"]{
        content:url("../../assets/img/mess_.svg");
    }
     .router-link-exact-active img[alt~="noti"]{
        content:url("../../assets/img/noti_.svg");
    } */

.search-wrapper label {
  position: absolute;
  font-size: 12px;
  color: rgba(0, 0, 0, 0.5);
  top: 8px;
  left: 12px;
  z-index: -1;
  transition: 0.15s all ease-in-out;
}
.router-link-exact-active .wrap-avt {
  margin-top: 0.75px;
  border-radius: 50%;
  box-shadow: 0 0 0 1px #5bb810;
}

.mobile-nav .link {
  height: 35px;
  padding: 0 10px;
}
.mobile-nav .link:hover {
  background-color: #fff;
  border-radius: 3px;
}

.scaffold-search-bar input {
  border: 1px solid #dbdbdb;
  border-radius: 5px;
}

.scaffold-search-bar {
  display: flex;
  align-items: center;
  position: relative;
}
#custom-search-input {
  border: solid 1px #e4e4e4;
  border-radius: 6px;
  background-color: #f1f1f1;
}

#custom-search-input input {
  border: 0;
  box-shadow: none;
}

#custom-search-input button {
  display: flex;
  align-items: center;
  height: 30px;
  background: none;
  box-shadow: none;
  border: 0;
  color: #666666;
  padding: 0 8px 0 10px;
}

#custom-search-input button:hover {
  border: 0;
  box-shadow: none;
}
#custom-search-input div .form-control {
  height: 30px;
  width: 285px;
  padding: 0 10px;
  background-color: #f1f1f1 !important;
}
#custom-search-input .input-group {
  padding: 0 !important;
  flex-wrap: unset;
}
@media screen and (max-width: 768px) {
  .scaffold-search-bar {
    display: none;
  }
}
.scaffold-search-mobile-nav {
  margin: 5px;
}
.btn-logout {
  cursor: pointer;
}
.drop-header-desktop {
  position: relative;
  cursor: pointer;
  width: 26px;
  height: 26px;
  margin-top: 3px !important;
  margin: auto 10px;
  background-color: #dbdbdb;
  border-radius: 26px;
  line-height: 1.4rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.drop-header-desktop:hover {
  box-shadow: 0 0 0 2px #5cb81083;
  background-color: #cecece;
}
.sort-down {
  display: flex;
  position: relative;
  align-items: center;
  justify-content: center;
  margin-top: -8px;
}
.option-drop-header {
  cursor: pointer;
  padding: 8px;
  background-color: #fff;
  border-radius: 3px;
  box-shadow: rgba(0, 0, 0, 0.16) 0px 10px 36px 0px,
    rgba(0, 0, 0, 0.06) 0px 0px 0px 1px;
  width: max-content;
  position: absolute;
  top: 30px;
  right: 0;
  color: #1a1a1a;
  font-size: 14px;
}
.option-drop-header:hover {
  /* background-color: #8cdd49; */
}
.scaffold-result-search {
  position: absolute;
  top: 48px;
  width: 325px;
  border-radius: 6px;
  /* border: 1px solid #dbdbdb; */
  background-color: #fff;
  max-height: 500px;
  overflow: auto;
  box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
  z-index: 1000;
}
.bg-offset-search-bar {
  position: absolute;
  left: 0;
  top: 60px;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.5);
  width: 100%;
}
.icon-search-green {
  display: flex;
  align-items: center;
  justify-content: center;
  height: 30px;
  width: 30px;
  background-color: #f1f1f1;
  border-radius: 30px;
  box-shadow: 0 0 0 0.5px #dbdbdb;
}
.title-button-search {
  font-size: 14px;
  color: #5bb810;
}
.btn-search-submit {
  cursor: pointer;
}
.scaffold-link-to-profile{
  padding: 5px;
  border-radius: 5px;
  display: flex;
  align-items: center;
  color: #1a1a1a;
}
.scaffold-link-to-profile:hover{
  text-decoration: none;
  background-color: rgba(219, 241, 204, 0.947);
}
.name-myprofile{
  font-size: 16px;
  font-weight: 500;
}
.title-to-profile{
  font-size: 13px;
  color: #666666;
}
.logout-profile{
  padding: 5px;
  border-radius: 5px;
}
.logout-profile:hover{
  background-color: rgba(219, 241, 204, 0.947);
}
.title-for-logout{
  margin-left: 5px;
  font-size: 16px;
  font-weight: 500;
  color: #5bb810;
}

</style>