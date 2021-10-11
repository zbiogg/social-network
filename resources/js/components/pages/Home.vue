<template>
  <div>
    <loadingbar></loadingbar>
    <zbio-header></zbio-header>
    <div class="zbio-main" v-cloak>
      <div class="scaffold-info" v-if="(!mobile)&&checkLoadedAll">
        <div class="scaffold-info-bg"></div>
        <router-link :to="userAuth ? '/profile/' + userAuth.id : '#'">
          <avatar
            class="scaffold-info-avt"
            :username="userAuth ? userAuth.firstName : '          '"
            :size="66"
            :src="userAuth_Avt?userAuth_Avt : '/img/avt/avt-default.png'"
          ></avatar>
        </router-link>
        <div class="scaffold-info-profile">
          <router-link
            class="scaffold-info-username"
            :to="userAuth ? '/profile/' + userAuth.id : '#'"
            >{{ userAuth ? userAuth.lastName : "" }}
            {{ userAuth ? userAuth.firstName : "" }}</router-link
          >
          <div class="line-hr"></div>
          <div class="testvuithoi">
            <img src="../../assets/img/QR.png" style="width: 80%" alt="" />
          </div>
          <div>
            <span>QR CODE</span>
          </div>
        </div>
      </div>
      <div
        class="load-shimmer-layout-left-info"
        v-if="!mobile && !checkLoadedAll"
      >
        <vue-content-loading
          :width="250"
          :height="400"
          :secondary="'#BDBDBD'"
          :primary="'#E0E0E0'"
        >
          <circle cx="125" cy="60" r="30" />
          <rect x="0" y="0" rx="5" ry="5" width="250" height="60" />
          <rect x="0" y="20" rx="0" ry="0" width="250" height="40" />
          <rect x="50" y="100" rx="2" ry="2" width="150" height="15" />
          <rect x="20" y="130" rx="2" ry="2" width="210" height="2" />
          <rect x="25" y="147" rx="2" ry="2" width="200" height="200" />
          <rect x="85" y="360" rx="2" ry="2" width="80" height="16" />
        </vue-content-loading>
      </div>
      <div>
        <create-post v-show="checkLoadedAll"></create-post>
        <div class="load-shimmer-layout" v-if="!checkLoadedAll">
          <vue-content-loading
            :width="600"
            :height="120"
            :secondary="'#BDBDBD'"
            :primary="'#E0E0E0'"
          >
            <circle cx="45" cy="40" r="20" />
            <rect x="75" y="20" rx="20" ry="20" width="495" height="40" />
            <rect x="40" y="80" rx="4" ry="4" width="60" height="20" />
            <rect x="193" y="80" rx="4" ry="4" width="60" height="20" />
            <rect x="346" y="80" rx="4" ry="4" width="60" height="20" />
            <rect x="500" y="80" rx="4" ry="4" width="60" height="20" />
          </vue-content-loading>
        </div>
        <!-- <vcl-facebook></vcl-facebook> -->
        <!-- <vcl-instagram :secondary="'#BDBDBD'" :primary="'#E0E0E0'"></vcl-instagram> -->
        <div class="load-shimmer-layout" v-if="!checkLoadedAll">
          <vue-content-loading
            :width="600"
            :height="300"
            :secondary="'#BDBDBD'"
            :primary="'#E0E0E0'"
            v-for="index in 3"
            :key="index"
          >
            <circle cx="32" cy="32" r="20" />
            <rect x="60" y="16" rx="2" ry="2" width="200" height="13" />
            <circle cx="584" cy="25" r="2" />
            <circle cx="578" cy="25" r="2" />
            <circle cx="572" cy="25" r="2" />
            <rect x="60" y="38" rx="2" ry="2" width="100" height="9" />
            <rect x="12" y="60" rx="4" ry="4" width="576" height="230" />
          </vue-content-loading>
        </div>

        <posts-newfeed v-show="checkLoadedAll"></posts-newfeed>
      </div>
      <div v-if="!tablet&&!mobile" class="scaffold-suggest-friend">
        <div class="suggest-friend-main">
          <vue-content-loading
            v-if="!checkLoadedAll"
            :width="300"
            :height="26"
            :secondary="'#BDBDBD'"
            :primary="'#E0E0E0'"
          >
            <circle cx="290" cy="12" r="7" />
            <rect x="0" y="5" rx="5" ry="5" width="100" height="14" />
          </vue-content-loading>
          <div class="scaffold-suggest-friend-title" v-show="checkLoadedAll">
            <span class="suggest-friend-title">Gợi ý kết bạn</span>
            <span class="suggest-friend-refesh">
              <img
                src="../../assets/icons/reload.svg"
                width="16"
                alt="btn-suggest-friend-refesh"
              />
            </span>
          </div>

          <hr />
          <div>
            <div v-if="!checkLoadedAll">
              <vue-content-loading
                :width="300"
                :height="76"
                :secondary="'#BDBDBD'"
                :primary="'#E0E0E0'"
                v-for="index in 5"
                :key="index"
              >
                <circle cx="38" cy="38" r="26" />
                <rect x="75" y="16" rx="2" ry="2" width="150" height="15" />
                <rect x="75" y="40" rx="10" ry="10" width="100" height="20" />
              </vue-content-loading>
            </div>

            <suggest-friend
              v-show="checkLoadedAll"
              v-for="(sg, index) in suggestFriends"
              :key="index"
              :username="sg.lastName + ' ' + sg.firstName"
              :mutual_friends="sg.mutual_friends"
              :userAvt="sg.img_avt"
              :userid="sg.id"
            >
            </suggest-friend>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
// import BaseRequest from '../../cores/BaseRequest';
import LoadingBar from "../effect/LoadingBar.vue";
import Header from "../layout/Header.vue";
import Posts from "../childs/Posts.vue";
import SuggestFriend from "../childs/SuggestFriend.vue";
import Avatar from "vue-avatar";
import BaseRequest from "../../cores/BaseRequest";
import LinkImage from "../../cores/LinkImage";
import { mapGetters, mapMutations } from "vuex";
import {
  VclFacebook,
  VclInstagram,
  VueContentLoading,
} from "vue-content-loading";
export default {
  components: {
    loadingbar: LoadingBar,
    "zbio-header": Header,
    "suggest-friend": SuggestFriend,
    "posts-newfeed": Posts,
    Avatar,
    VclFacebook,
    VclInstagram,
    VueContentLoading,
  },
  data() {
    return {
      mobile: null,
      mobileNav: null,
      tablet: null,
      windowWidth: null,
      userAvt: null,
      imgCover: null,
      suggestAvt: null,
      linkAvt: null,
      suggestFriends: null,
      checkLoadedAll: false,
    };
  },
  computed: {
    ...mapGetters(["userAuth", "userAuth_Avt"]),
  },
  created() {
    window.addEventListener("resize", this.checkScreen);
    this.checkScreen();
     window.scrollTo(0, 0);
  },
  mounted() {
    BaseRequest.get("suggestFriends")
      .then((response) => {
        this.suggestFriends = response.data.data;
        this.linkAvt = LinkImage.avt();
      })
      .catch((error) => {
        console.log(error);
      });

    this.$nextTick(function () {
      setTimeout(this.loadedAll, 1000);
    });
  },
  methods: {
    loadedAll() {
      this.checkLoadedAll = true;
    },
    ...mapMutations(["setUserAuth", "setUserAuth_Avt"]),
    checkScreen() {
      this.windowWidth = window.innerWidth;
      if (this.windowWidth <= 992) {
        if(this.windowWidth>768){
          this.tablet = true;
          this.mobile=false;
        }else {
          this.mobile = true;
        }
        return;
      }
      this.mobile = false;
      this.mobileNav = false;
      this.tablet = false;
      return;
    },
  },
};
</script>
<style scoped>
.zbio-main {
  padding-top: 75px;
}
@media screen and (min-width: 769px) {
  .zbio-main {
    display: grid;
    grid-template-columns: minmax(0, 7fr) minmax(0, 17fr);
    column-gap: 1.2rem;
    margin: 0 20px;
  }
}
@media screen and (min-width: 992px) {
  .zbio-main {
    display: grid;
    grid-template-columns: minmax(0, 5fr) minmax(0, 12fr) minmax(0, 7fr);
    column-gap: 1.6rem;
    margin: 0 30px;
  }
}
.scaffold-info {
  position: -webkit-sticky;
  position: sticky;
  top: 75px;
  max-height: 300px;
  font-family: Segoe UI;
}

.scaffold-info-bg {
  background-color: #deebdd;
  background-image: linear-gradient(90deg, rgb(114, 211, 35) 0%, #deebdd 95%);
  /* background-image: linear-gradient(315deg, #deebdd 0%, #bbdbbe 74%); */
  height: 60px;
  border-top-left-radius: 10px;
  border-top-right-radius: 10px;
  border-top: 0.5px solid #dbdbdb;
  border-right: 0.5px solid #dbdbdb;
  border-left: 0.5px solid #dbdbdb;
}
.scaffold-info-profile {
  padding-top: 40px;
  min-height: 300px;
  border: 0.5px solid #dbdbdb;
  background-color: #fff;
  border-bottom-left-radius: 10px;
  border-bottom-right-radius: 10px;
}
.scaffold-info-avt {
  position: relative;
  margin: -33px auto;
  z-index: 1;
  box-shadow: 0 0 0 2px #fff;
}
.scaffold-info-profile a {
  display: flex;
  justify-content: center;
}
.scaffold-info-username {
  font-weight: 600;
  text-decoration: none;
  color: #1a1a1a;
}
.scaffold-info-username:hover {
  text-decoration: underline;
  color: #1a1a1a;
}
.scaffold-info-profile .line-hr {
  margin-top: 10px;
}
.scaffold-info-profile div {
  display: flex;
  justify-content: center;
  margin-bottom: 5px;
}
.testvuithoi {
  margin-top: 20px;
}
.scaffold-suggest-friend {
  /* max-height: 600px; */
  height: max-content;
  border-radius: 10px;
  border: 0.5px solid #dbdbdb;
  background-color: #fff;
}
.suggest-friend-main {
  padding: 20px;
}
.scaffold-suggest-friend-title {
  display: flex;
  justify-content: space-between;
  margin-bottom: 5px;
  color: #5bb810;
}
.suggest-friend-title {
  font-weight: 500;
  font-size: 16px;
  font-family: Segoe UI;
}
.suggest-friend-refesh img {
  margin-top: -2px;
}
.suggest-friend-main hr {
  margin: 1px;
}
.load-shimmer-layout {
  margin: 0 auto;
  max-width: 600px;
  background-color: #fff;
  border-radius: 6px;
  border: 0.5px solid #dbdbdb;
  margin-bottom: 10px !important;
}
.load-shimmer-layout-left-info {
  position: -webkit-sticky;
  position: sticky;
  top: 75px;
  max-height: 400px;
  font-family: Segoe UI;
  background-color: #fff;
  border-radius: 8px;
}
</style>