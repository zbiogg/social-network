<template>
  <div>
    <loadingbar></loadingbar>
    <zbio-header></zbio-header>
    <div class="zbio-main" v-if="!isLoadedAll">
      <div class="load-shimmer-layout" >
        <vue-content-loading
          :width="950"
          :height="410"
          :secondary="'#BDBDBD'"
          :primary="'#E0E0E0'"
        >
          <rect x="0" y="0" rx="2" ry="2" width="950" height="250" />
          <circle cx="475" cy="205" r="75" style="fill:rgb(0,0,0);stroke-width:3;stroke:rgb(0,0,0)"/>
          <rect x="350" y="290" rx="2" ry="2" width="250" height="25" />
          <rect x="125" y="330" rx="10" ry="10" width="125" height="60" />
          <rect x="268" y="330" rx="10" ry="10" width="125" height="60" />
          <rect x="411" y="330" rx="10" ry="10" width="125" height="60" />
          <rect x="554" y="330" rx="10" ry="10" width="125" height="60" />
          <rect x="697" y="330" rx="10" ry="10" width="125" height="60" />
          
        </vue-content-loading>
        <vue-content-loading
          :width="950"
          :height="1000"
          :secondary="'#BDBDBD'"
          :primary="'#E0E0E0'"
        >
        <rect x="0" y="0" rx="10" ry="10" width="380" height="380" />
        <rect x="0" y="390" rx="10" ry="10" width="380" height="380" />
        <rect x="0" y="780" rx="10" ry="10" width="380" height="300" />
        <rect x="390" y="0" rx="10" ry="10" width="560" height="560" />
        <rect x="390" y="570" rx="10" ry="10" width="560" height="560" />
        </vue-content-loading>
   
      </div>
    </div>
    <div class="zbio-main" v-if="(userInfo ? userInfo.status == '0' : false)&&isLoadedAll">
      <div class="scaffold-header-profile">
        <div class="scaffold-bg-cover">
          <img
            :class="{ 'is-hover-avt': isHoverAvt }"
            width="100%"
            class="img-bg-cover"
            :src="
              userInfo.img_cover
                ? userInfo.img_cover
                : '/img/cover/cover-default.png'
            "
            alt=""
          />
        </div>

        <span
          class="scaffold-options-avt-profile"
          @mouseover="isHoverAvt = true"
          @mouseleave="isHoverAvt = false"
          @click="
            userAuth
              ? $route.params.id == userAuth.id
                ? $refs.radialMenu.open()
                : viewAvt()
              : ''
          "
        >
          <r-menu
            ref="radialMenu"
            :menu-items="itemsOptionAvt"
            :size="150"
            :close-on-click="true"
            :imgAvt="
              userAuth
                ? $route.params.id == userAuth.id
                  ? userAuth_Avt
                    ? userAuth_Avt
                    : '/img/avt/avt-default.png'
                  : userInfo.img_avt
                  ? userInfo.img_avt
                  : '/img/avt/avt-default.png'
                : '/img/avt/avt-default.png'
            "
          ></r-menu>

          <avatar
            :class="[
              {
                'is-not-userAuth-avt': userAuth
                  ? $route.params.id != userAuth.id
                  : false,
              },
              'scaffold-avt-profile',
            ]"
            :size="150"
            username="   "
            :src="
              userAuth
                ? $route.params.id == userAuth.id
                  ? userAuth_Avt
                    ? userAuth_Avt
                    : '/img/avt/avt-default.png'
                  : userInfo.img_avt
                  ? userInfo.img_avt
                  : '/img/avt/avt-default.png'
                : '/img/avt/avt-default.png'
            "
          ></avatar>
        </span>
        <div class="scafold-abc-xyz">
          <div class="scaffold-fullname-profile">
            <span class="fullname-profile"
              >{{ userInfo.lastName }} {{ userInfo.firstName }}</span
            >
          </div>

          <div class="scaffold-options-profile">
            <div
              class="scaffold-options-friends"
              v-if="
                userAuth && userInfo ? userAuth.id != $route.params.id : false
              "
            >
              <span
                class="btn-add-friend-profile"
                v-if="userInfo.status_friend == '-1'"
                @click="addFriend"
              >
                <icon name="user-plus" width="16px" height="16px"></icon>
                Thêm bạn bè
              </span>
              <span
                class="btn-add-friend-profile"
                v-if="
                  userInfo.status_friend == '0' &&
                  userInfo.action_userID == $route.params.id
                "
              >
                <icon name="user-check" width="16px" height="16px"></icon>
                Xác nhận
              </span>
              <span
                class="btn-add-friend-profile"
                v-if="
                  userInfo.status_friend == '0' &&
                  userInfo.action_userID != $route.params.id
                "
                @click="cancelAddFriend"
              >
                <icon name="user-times" width="16px" height="16px"></icon>
                Hủy lời mời
              </span>
              <span
                class="btn-friend-profile"
                v-if="userInfo.status_friend == '1'"
              >
                <icon name="user-friends" width="16px" height="16px"></icon>
                Bạn bè
              </span>
              <span class="btn-to-messenger-profile">
                <icon
                  name="facebook-messenger"
                  width="16px"
                  height="16px"
                ></icon>
                Nhắn tin
              </span>
              <span class="btn-more-profile">
                <icon name="ellipsis-h" width="16px" height="16px"></icon>
              </span>
            </div>
            <div class="scafold-pages-in-profile">
              <router-link
                class="scaffold-detail-page-profile"
                :to="'/profile/' + $route.params.id"
              >
                <div class="scaffold-image-icon-page">
                  <img
                    src="../../assets/icons/timeline.svg"
                    width="18px"
                    height="18px"
                    alt=""
                  />
                </div>
                <span class="title-page-profile">Hoạt động</span>
              </router-link>
              <router-link
                class="scaffold-detail-page-profile"
                :to="'/profile/' + $route.params.id + '/about'"
              >
                <div class="scaffold-image-icon-page">
                  <img
                    src="../../assets/icons/about.svg"
                    width="18px"
                    height="18px"
                    alt=""
                  />
                </div>
                <span class="title-page-profile">Giới thiệu</span>
              </router-link>
              <router-link
                class="scaffold-detail-page-profile"
                :to="'/profile/' + $route.params.id + '/friends'"
              >
                <div class="scaffold-image-icon-page">
                  <img
                    src="../../assets/icons/friend.svg"
                    width="18px"
                    height="18px"
                    alt=""
                  />
                </div>
                <span class="title-page-profile">Bạn bè</span>
              </router-link>
              <router-link
                class="scaffold-detail-page-profile"
                :to="'/profile/' + $route.params.id + '/albums'"
              >
                <div class="scaffold-image-icon-page">
                  <img
                    src="../../assets/icons/gallery.svg"
                    width="18px"
                    height="18px"
                    alt=""
                  />
                </div>
                <span class="title-page-profile">Ảnh/Video</span>
              </router-link>
              <router-link
                class="scaffold-detail-page-profile"
                :to="'/profile/' + $route.params.id + '/others'"
              >
                <div class="scaffold-image-icon-page">
                  <img
                    src="../../assets/icons/layers.svg"
                    width="18px"
                    height="18px"
                    alt=""
                  />
                </div>
                <span class="title-page-profile">Khác</span>
              </router-link>
            </div>
          </div>
        </div>
      </div>
      <div class="scaffold-content-profile">
        <div class="scaffold-left-content">
          <div class="scaffold-main-left-content">
            <div class="scaffold-statistic-profile">
              <div class="item-statistic-profile">
                <span class="qty-per-item-statistic">{{
                  userInfo.statistic.postQty
                }}</span>
                <span class="name-item-statistic">Bài viết</span>
              </div>
              <div class="item-statistic-profile">
                <span class="qty-per-item-statistic">{{
                  userInfo.statistic.likeQty
                }}</span>
                <span class="name-item-statistic">Lượt thích</span>
              </div>
              <div class="item-statistic-profile">
                <span class="qty-per-item-statistic">{{
                  userInfo.statistic.cmtQty
                }}</span>
                <span class="name-item-statistic">Bình luận</span>
              </div>
            </div>
            <div class="line-hr"></div>
            <UploadAvt />
            <div class="scaffold-photos-profile">
              <router-link :to="'/profile/' + $route.params.id + '/albums'">
                <span class="title-photos-profile-left-content"
                  >Photos Albums</span
                >
              </router-link>

              <div id="vjs-app">
                <viewer
                  :options="options"
                  :images="userInfo.photos"
                  class="photos-profile"
                >
                  <div class="items-list">
                    <div
                      class="scaffold-items-list-detail"
                      v-for="(album, index) in userInfo.photos"
                      :key="index"
                    >
                      <img :src="album" class="img-detail-in-photos" />
                    </div>
                  </div>
                </viewer>
              </div>
            </div>
          </div>
        </div>
        <div class="scaffold-right-content">
          <create-post
            v-if="userAuth ? $route.params.id == userAuth.id : false"
          ></create-post>
          <div class="posts-profile">
            <detail-post
              v-for="(post, index) in postsProfile"
              :key="index"
              :index="index"
              :userID="post.userID"
              :userAvt="post.userAvt"
              :userFullName="post.userfullname"
              :postID="post.postID"
              :created_at="post.created_at"
              :post_Content="post.post_Content"
              :post_Images="post.post_Images.map((a) => a.image)"
              :like_qty="post.like_qty"
              :cmt_qty="post.cmt_qty"
              :liked="post.liked"
            ></detail-post>
          </div>
        </div>
      </div>
    </div>
    <div
      class="zbio-main"
      v-if="(userInfo ? userInfo.isNull == true || userInfo.status == '1' : '')&&isLoadedAll"
    >
      <div class="not-found-link">
        <div class="scaffold-icon-not-found">
          <icon name="unlink" width="50px" height="50px"></icon>
        </div>

        <span class="title-not-found">Liên kết không tồn tại!</span>
        <div class="btn-to-home">
          <router-link to="/">Đi tới bảng tin</router-link>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import Avatar from "vue-avatar";
import BaseRequest from "../../cores/BaseRequest";
import LoadingBar from "../effect/LoadingBar.vue";
import Header from "../layout/Header.vue";
import DetailPost from "../childs/DetailPost.vue";
import UploadAvt from "../childs/UploadAvt.vue";
import { mapGetters, mapActions, mapMutations } from "vuex";
// import { RadialMenu, RadialMenuItem } from "vue-radial-menu";
import RadialMenu from "../custom/RadialMenu.vue";
import {
  VclFacebook,
  VclInstagram,
  VueContentLoading,
} from "vue-content-loading";

export default {
  data() {
    return {
      items: ["Camera", "Ảnh", "Xem", "Xóa"],
      likeQty: null,
      cmtQty: null,
      postQty: null,
      images: [],
      imagesAvt: [],
      options: {
        title: false,
        movable: false,
        fullscreen: false,
      },
      isLoadedAll: false,
      isHoverAvt: false,
      itemsOptionAvt: [
        {
          id: "viewAvt",
          title: "Xem",
          icon: "search",
        },
        {
          id: "changeAvt",
          title: "Thay đổi",
          icon: "file-image",
        },
        {
          id: "save",
          title: "Lưu",
          icon: "save",
        },
        {
          id: "more",
          title: "Khác",
          icon: "ellipsis-h",
          items: [
            {
              id: "test1",
              title: "Không",
              icon: "#eat",
            },
            {
              id: "test2",
              title: "Có",
              icon: "#eat",
            },
            {
              id: "test3",
              title: "Gì",
              icon: "#eat",
            },
            {
              id: "test4",
              title: "Đâu",
              icon: "#eat",
            },
          ],
        },
      ],
    };
  },
  components: {
    loadingbar: LoadingBar,
    "zbio-header": Header,
    UploadAvt,
    Avatar,
    "detail-post": DetailPost,
    "r-menu": RadialMenu,
    VueContentLoading,
  },
  computed: {
    ...mapGetters([
      "postsProfile",
      "userAuth",
      "userInfo",
      "userAuth_Avt",
      "userAuth_Cover",
    ]),
  },
  mounted() {
    window.scrollTo(0, 0);
    this.getUserProfile(this.$route.params.id);
    
    // this.$nextTick(function () {
    //   setTimeout(this.loadedAll, 1000);
    // });
  },
  watch: {
    $route() {
      this.getUserProfile(this.$route.params.id);
      this.isLoadedAll=false;
    },
    userInfo(){
      setTimeout(this.loadedAll, 800);
    }
  },
  methods: {
    ...mapActions(["getUserProfile"]),
    ...mapMutations(["setStatusFriendForUser"]),
    addFriend() {
      BaseRequest.post("addFriend", {
        receiverID: this.$route.params.id,
      })
        .then(() => {
          this.setStatusFriendForUser(0);
        })
        .catch((error) => {
          console.log(error);
        });
      document.activeElement.blur();
    },
    acceptFriend() {},
    cancelAddFriend() {
      BaseRequest.post("cancelAddFriend", {
        receiverID: this.$route.params.id,
      })
        .then((response) => {
          if (response.data.success == true) {
            this.setStatusFriendForUser(-1);
          }
        })
        .catch((error) => {
          console.log(error);
        });
      document.activeElement.blur();
    },
    loadedAll: function () {
      var self = this;
      self.isLoadedAll = true;
    },
        viewAvt: function () {
     
      BaseRequest.get("useravts/" + this.$route.params.id)
        .then((response) => {
          if (response.data.length != 0) {
            this.imagesAvt = response.data;
          } else {
            this.imagesAvt = ["/img/avt/avt-default.png"];
          }
           this.$viewerApi({
            options: this.options,
            images: this.imagesAvt,
          });
        })
        .catch((error) => {
          console.log(error);
        });
    },
  },
};
</script>
<style scoped>
.zbio-main {
  padding-top: 60px;
  max-width: 950px;
  margin: auto;
  position: relative;
}
.scaffold-header-profile {
  border-bottom: 1px solid #dbdbdb;
}

.img-bg-cover {
  max-height: 250px;
  object-fit: cover;
  border-bottom-right-radius: 3px;
  border-bottom-left-radius: 3px;
  background-color: #1a1a1a !important;
}
.scaffold-bg-cover {
  display: flex;
  justify-content: center;
  height: 250px;
}
.scaffold-avt-profile {
  margin-top: -120px !important;
  position: relative;
  margin: -150px auto;
  justify-content: center;
  box-shadow: 0 0 0 3px #fff;
}
.scaffold-avt-profile:hover {
  box-shadow: 0 0 0 3px rgb(113, 223, 46);
}
.scafold-abc-xyz {
  background-color: #fff;
  padding-top: 150px;
  max-width: 950px;
}
.scaffold-fullname-profile {
  display: flex;
  justify-content: center;
}
.fullname-profile {
  font-size: 25px;
  font-weight: 600;
}
@media screen and (max-width: 992px) {
  .post-item {
    max-width: 700px;
  }
  .scaffold-create-post {
    max-width: 700px;
  }
}
@media screen and (max-width: 500px) {
  .title-page-profile {
    font-size: 10px !important;
  }
}
@media screen and (min-width: 992px) {
  .scaffold-options-profile {
    padding: 0 15px;
  }
  .scaffold-content-profile {
    margin-top: 10px;
    min-height: 200px;
    display: grid;
    grid-template-columns: minmax(0, 4fr) minmax(0, 6fr);
    column-gap: 15px;
  }
}
.btn-add-friend-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 3px;
  padding: 3px 5px;
  background-color: rgb(113, 223, 46);
  color: #fff;
  font-weight: 500;
  font-size: 14px;
}
.btn-friend-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 3px;
  padding: 3px 5px;
  background-color: rgb(113, 223, 46);
  color: #fff;
  font-weight: 500;
  font-size: 14px;
}
.btn-to-messenger-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 3px;
  padding: 3px 8px;
  background-color: #e9ebee;
  color: #1a1a1a;
  font-weight: 500;
  font-size: 14px;
}
.btn-more-profile {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  border-radius: 3px;
  padding: 3px 7px;
  background-color: #e9ebee;
  color: #1a1a1a;
  font-weight: 500;
  font-size: 14px;
}
.btn-add-friend-profile:hover,
.btn-to-messenger-profile:hover,
.btn-more-profile:hover {
  opacity: 0.9;
  fill-opacity: 0.9;
}
.user-plus,
.facebook-messenger,
.user-check,
.user-times,
.user-friends {
  margin-right: 5px;
}

.scaffold-options-friends {
  display: flex;
  justify-content: center;
  column-gap: 5px;
  padding: 5px;
}
.scafold-pages-in-profile {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 10px 0px;
  column-gap: 15px;
  max-width: 700px;
  margin: auto;
}
.scaffold-image-icon-page {
  display: flex;
  justify-content: center;
}
.scaffold-detail-page-profile {
  flex-grow: 1;
  flex-basis: 0;
  color: #7e7d7d;
  text-decoration: none;
  padding: 10px 5px;
}
.title-page-profile {
  display: flex;
  justify-content: center;
  font-size: 14px;
}
.scaffold-detail-page-profile {
  border-radius: 10px;
}
.scafold-pages-in-profile .router-link-exact-active {
  background-color: #84fb95;
  background-image: linear-gradient(
    315deg,
    rgb(204, 219, 194) 0%,
    rgb(113, 223, 46) 100%
  );
  color: #000;
  font-weight: 500;
  box-shadow: 0 5px 10px -4px rgb(58 46 68 / 20%);
}
.scaffold-detail-page-profile:hover {
  background-color: rgb(223, 223, 223);
  box-shadow: 0 5px 10px -4px rgb(58 46 68 / 20%);
}
.scaffold-content-profile {
  margin-top: 10px;
  min-height: 200px;
  display: grid;
  column-gap: 15px;
}
.scaffold-left-content {
  height: max-content;
  border-radius: 8px;
  margin-bottom: 10px;
}
.scaffold-right-content {
  border-radius: 8px;
}
.scaffold-options-avt-profile {
  cursor: pointer;
}

.custom-item-option-avt-profile {
  box-shadow: none;
}

.vue-radial-menu-wrapper {
  color: rgba(255, 255, 255, 0);
}

.vue-radial-menu-wrapper:hover {
  box-shadow: 0 0 0 3px rgb(113, 223, 46);
  z-index: 15;
}

.item-detail-avt-profile {
  background-color: rgba(255, 255, 255);
  width: 80px;
  height: 80px;
  border-radius: 80px;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 0 0 2px rgb(113, 223, 46);
}
.scaffold-statistic-profile {
  display: flex;
  justify-content: space-around;
  align-items: center;
  padding: 10px;
  max-width: 700px;
  margin: 0 auto;
}
.qty-per-item-statistic {
  display: flex;
  justify-content: center;
  color: #5bb810;
  font-weight: 600;
  font-size: 16px;
}
.name-item-statistic {
  color: #838daa;
  font-size: 15px;
}

.scaffold-main-left-content {
  background-color: #fff;
  border-radius: 5px;
  border: 0.5px solid #dbdbdb;
  text-align: center;
  overscroll-behavior: contain;
}
.scaffold-photos-profile {
  padding-top: 6px;
}
.scaffold-photos-profile a {
  text-decoration: none;
}
.title-photos-profile-left-content {
  font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
  font-weight: 600;
  font-size: 18px;
  color: #5bb810;
  text-shadow: 0px 0px 1px #b9b9b9;
}
.title-photos-profile-left-content:hover {
  cursor: pointer;
  text-decoration: underline;
}
.not-found-link {
  display: table;
  margin: 30vh auto;
  align-items: center;
  justify-content: center;
}
.scaffold-icon-not-found {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
  color: #5bb810;
}
.title-not-found {
  padding-top: 20px;
  font-weight: 500;
  font-size: 20px;
  color: #5bb810;
}
.btn-to-home {
  margin-top: 10px;
  display: flex;
  justify-content: center;
  background-color: #5bb810;
  border-radius: 6px;
  box-shadow: 0 0 0 1px #5bb810;

  cursor: pointer;
}
.btn-to-home:hover {
  opacity: 0.9;
}

.btn-to-home a {
  padding: 5px;
  display: flex;
  width: 100%;
  height: 100%;
  justify-content: center;
  color: #fff;
  text-decoration: none;
  font-weight: 500;
  font-size: 16px;
}

.vue-avatar--wrapper {
  background-color: #fff !important;
}

.items-list {
  padding-left: 0;
  display: grid;
  grid-gap: 5px;
  grid-template-columns: repeat(3, 1fr);
  grid-template-rows: 150px 1fr;
  grid-template-rows: fit-content(100%);
  list-style-type: none;
}

.scaffold-items-list-detail {
  position: relative;
  overflow: hidden;
  padding-bottom: 100%;
  border-radius: 6px;
}
.img-detail-in-photos {
  width: 100%;
  height: 100%;
  display: flex;
  position: absolute;
  object-fit: cover;
  cursor: pointer;
  border-radius: 6px;
  transition: transform 0.6s;
  /* box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 0px 2px rgb(0 0 0 / 24%); */
  box-shadow: 0 1px 3px rgb(0 0 0 / 12%), 0 1px 2px rgb(0 0 0 / 24%);
}
.img-detail-in-photos:hover {
  border-radius: 6px !important;
  transform: scale(1.5);
}
.photos-profile {
  margin: 10px 15px 15px 15px;
  border-collapse: separate;
}
.is-hover-avt {
  border-bottom-left-radius: 0;
  border-bottom-right-radius: 0;
  box-shadow: 0 3px 0px rgb(113, 223, 46);
}
.radialMenu {
  position: absolute !important;
  margin: -150px auto !important;
  margin-top: -120px !important;
  justify-content: center !important;
  z-index: 9999;
  left: 0;
  right: 0;
}
.load-shimmer-layout {
  position: absolute;
  top: 60px;
  left: 0;
  width: 100%;
  background-color: #fff;
  display: block;
}
</style>
