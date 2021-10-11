<template>
  <div
    :class="[{ 'animate__animated animate__backOutUp': isDelete }, 'post-item']"
  >
    <div class="post-item_header">
      <div class="post-item-info align-items-center">
        <router-link :to="'/profile/' + userID" class="post-item-avatar">
          <avatar
            :size="40"
            username="          "
            :src="
              userAuth
                ? userAuth.id == userID
                  ? userAuth_Avt
                    ? userAuth_Avt
                    : '/img/avt/avt-default.png'
                  : userAvt
                  ? userAvt
                  : '/img/avt/avt-default.png'
                : userAvt
                ? userAvt
                : '/img/avt/avt-default.png'
            "
          ></avatar>
        </router-link>
        <div class="mx-2">
          <router-link class="profile-post" :to="'/profile/' + userID">
            <span class="name-request">{{ userFullName }}</span>
          </router-link>
          <div class="post_time">
            <router-link :to="'/posts/' + postID">
              <time-ago
                refresh
                :locale="'vi'"
                :datetime="created_at"
                :long="true"
                tooltip
                :tooltip-options="tooltipOptions"
              ></time-ago>
            </router-link>
          </div>
        </div>
        <span
          class="btn-post-more"
          @click="showPostOption"
          @focusout="testTiep"
          tabindex="0"
        >
          <img src="../../assets/icons/more.svg" width="15x" alt="" />
          <div class="scaffold-post-options" v-if="isShowPostOptions">
            <p>haha</p>
            <p>haha</p>
            <p>haha</p>
            <p>haha</p>
          </div>
        </span>
      </div>
    </div>
    <div class="post-item_content">
      <div class="post-item-title" v-if="post_Content">
        {{ post_Content }}
      </div>
      <div
        v-if="post_Images.length != 0"
        style="
          position: relative;
          height: 1px;
          background-color: #dbdbdb;
          top: 2px;
          z-index: 999;
        "
      ></div>
      <div class="post-content-image" v-if="post_Images.length != 0">
        <photos :items="post_Images" :cells="5"></photos>
      </div>
    </div>
    <div class="post-stats" v-if="like_qty != 0 || cmt_qty != 0">
      <div class="post-stats-like">
        <img
          v-if="like_qty != 0"
          src="../../assets/icons/love_circle.svg"
          width="20px"
          alt=""
        />
        <span v-if="like_qty != 0" class="post-like-qty">{{ like_qty }}</span>
      </div>
      <div class="post-stats-cmt">
        <span v-if="cmt_qty != 0">{{ cmt_qty }} bình luận</span>
      </div>
    </div>
    <div
      class="line-hr"
      v-if="like_qty != 0 || cmt_qty != 0 || post_Images.length == 0"
    ></div>
    <div class="post-events">
      <button
        @click="like"
        class="col btn-events btn"
        :class="{ 'btn-events-active': liked == 1 }"
        style=""
      >
        <img src="../../assets/icons/like.svg" alt="like" width="20" />
        Thích
      </button>
      <button class="col btn-events btn" @click="showCmt()">
        <img src="../../assets/icons/cmt.svg" alt="cmt" width="20" />
        Bình luận
      </button>
    </div>
    <div class="line-hr" v-if="isShowCmt == true"></div>
    <div class="scaffold-post-cmt" v-if="isShowCmt">
      <vue-loading
        v-if="isShowLoadingCmts"
        type="spin"
        color="#5bb810"
        :size="{ width: '30px', height: '30px' }"
      ></vue-loading>
      <div class="post_cmt">
        <div class="scaffold-view-more-cmt" v-if="showViewMoreCmts">
          <span class="view-more-cmt" @click="viewMoreCmt"
            >Xem thêm các bình luận</span
          >
          <vue-loading
            v-if="isShowLoadingMoreCmts"
            class="loading-view-more-cmts"
            type="spin"
            color="#5bb810"
            :size="{ width: '22px', height: '22px' }"
          ></vue-loading>
        </div>
        <div class="all-cmt">
          <div class="detail_cmt" v-for="cmt in postCmts" :key="cmt.id">
            <router-link
              :to="/profile/ + cmt.userID"
              class="post-item-avatar mr-2"
            >
              <avatar
                :size="35"
                username="         "
                :src="cmt.userAvt ? cmt.userAvt : '/img/avt/avt-default.png'"
              ></avatar>
            </router-link>
            <div>
              <div class="scaffold-content-cmt">
                <router-link
                  class="cmt-username"
                  :to="'/profile/' + cmt.userID"
                  >{{ cmt.userfullname }}</router-link
                >
                <span>{{ cmt.content_cmt }}</span>
              </div>
              <div>
                <time-ago
                  class="cmt-time-stamp"
                  refresh
                  :locale="'vi'"
                  :datetime="cmt.created_at"
                  :long="true"
                  tooltip
                  :tooltip-options="tooltipOptions"
                ></time-ago>
                <span class="btn-repcmt" @click="viewRepCmt"
                  >trả lời
                  <icon name="reply" width="14px" height="14px"></icon>
                </span>
              </div>
              <div class="scaffold-repCmt-input" v-if="isShowRepCmt">
                <avatar
                  :size="30"
                  username="         "
                  :src="
                    userAuth_Avt ? userAuth_Avt : '/img/avt/avt-default.png'
                  "
                ></avatar>
                <div class="around-input-repCmt">
                  <input
                    v-focus="focusRepCmt"
                    v-on:keyup.enter="inputCmt ? addRepCmt() : ''"
                    class="post-input-repCmt"
                    name="content_repCmt"
                    type="text"
                    :placeholder="'Trả lời '+cmt.userfullname"
                    autocomplete="off"
                    v-model="inputCmt"
                  />
                  <button
                    :class="[
                      'btn-submit-repCmt',
                      inputCmt
                        ? 'btn-submit-repCmt-available'
                        : 'btn-submit-repCmt-disable',
                    ]"
                    @click="addRepCmt()"
                  >
                    Gửi
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="scaffold-post-input-cmt">
        <avatar
          :size="35"
          username="         "
          :src="userAuth_Avt ? userAuth_Avt : '/img/avt/avt-default.png'"
        ></avatar>
        <div class="around-input-cmt">
          <input
            v-focus="focusCmt"
            v-on:keyup.enter="inputCmt ? addCmt() : ''"
            class="post-input-cmt"
            name="content_cmt"
            type="text"
            placeholder="Viết bình luận"
            autocomplete="off"
            v-model="inputCmt"
          />
          <button
            :class="[
              'btn-submit-cmt',
              inputCmt ? 'btn-submit-cmt-available' : 'btn-submit-cmt-disable',
            ]"
            @click="addCmt()"
          >
            Gửi
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import BaseRequest from "../../cores/BaseRequest";
import LinkImage from "../../cores/LinkImage";
import TimeAgo from "../custom/TimeAgo/cpn/TimeAgo.vue";
import "vue2-timeago/dist/vue2-timeago.css";
import Avatar from "vue-avatar";
import { mapGetters, mapActions, mapMutations } from "vuex";
import "../../assets/css/fontawesome.min.css";
import "../../assets/css/regular.min.css";
import PhotosGrid from "../custom/PhotosGrid.vue";
export default {
  props: [
    "index",
    "userID",
    "userAvt",
    "userFullName",
    "postID",
    "created_at",
    "post_Content",
    "post_Images",
    "like_qty",
    "cmt_qty",
    "liked",
  ],
  data() {
    return {
      LinkImgAvt: null,
      tooltipOptions: {
        placement: "bottom",
      },
      isShowCmt: false,
      isShowRepCmt:false,
      focusCmt: false,
      focusRepCmt:false,
      inputCmt: null,
      postCmts: [],
      // postLiked: this.liked,
      isDelete: null,
      options: {
        title: false,
        movable: false,
        fullscreen: false,
      },
      firstCmtID: null,
      showViewMoreCmts: false,
      isShowLoadingCmts: false,
      isShowLoadingMoreCmts: false,
      isShowPostOptions: false,
    };
  },
  components: {
    "time-ago": TimeAgo,
    Avatar,
    photos: PhotosGrid,
  },
  computed: {
    ...mapGetters(["userAuth", "userAuth_Avt"]),
  },
  mounted() {
    this.LinkImgAvt = LinkImage.avt();
  },
  methods: {
    ...mapMutations(["setLikedPost"]),
    ...mapActions(["deletePost", "changeLikedPost"]),
    like: function () {
      this.changeLikedPost({
        index: this.index,
        currentPage: this.$route.name,
      });
      BaseRequest.post("likes", {
        postID: this.postID,
      })
        .then((response) => {
          if (response.data.message == "liked") {
            this.setLikedPost({
              index: this.index,
              value: 1,
              currentPage: this.$route.name,
            });
          } else if (response.data.message == "disliked") {
            this.setLikedPost({
              index: this.index,
              value: 0,
              currentPage: this.$route.name,
            });
          }
        })
        .catch((error) => {
          console.log(error);
        });
      document.activeElement.blur();
    },

    addCmt: function () {
      var content = this.inputCmt;
      this.inputCmt = null;
      if (content) {
        BaseRequest.post("comments", {
          postID: this.postID,
          content_cmt: content,
        })
          .then((response) => {
            this.postCmts.push(response.data.cmt[0]);
            this.inputCmt = null;
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    showCmt: function () {
      document.activeElement.blur();
      this.focusCmt = true;
      this.focusCmt = false;
      if (!this.isShowCmt) {
        this.isShowLoadingCmts = true;
        BaseRequest.get("postcmts?postID=" + this.postID)
          .then((response) => {
            this.isShowLoadingCmts = false;
            this.postCmts = response.data.cmts;
            this.firstCmtID = this.postCmts[0].id;
            if (response.data.cmts_qty > 5) {
              this.showViewMoreCmts = true;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }

      this.isShowCmt = true;
    },

    viewMoreCmt() {
      this.isShowLoadingMoreCmts = true;
      document.activeElement.blur();
      BaseRequest.post("viewMoreCmts", {
        postID: this.postID,
        firstCmtID: this.firstCmtID,
      })
        .then((response) => {
          this.postCmts.unshift(...response.data.cmts);
          this.firstCmtID = this.postCmts[0].id;
          if (response.data.cmts_qty <= 5) {
            this.showViewMoreCmts = false;
          }
          this.isShowLoadingMoreCmts = false;
        })
        .catch((error) => {
          console.log(error);
        });
    },
    showPostOption: function () {
      this.isShowPostOptions = !this.isShowPostOptions;
      console.log("ds");
      // BaseRequest.post("deletePost", {
      //   postID: this.postID,
      // })
      //   .then((response) => {
      //     this.deletePost({ index: this.index, currentPage: this.$route.name });
      //   })
      //   .catch((error) => {
      //     console.log(error);
      //   });
    },
    viewRepCmt(){
      this.isShowRepCmt=true;
    },
    testTiep() {
      console.log("fsfasdsad");
    },
  },
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->

<style >
.post-item-info,
.user-item-info {
  display: flex;
}

.btn-post-more {
  display: table;
  margin: 0 0 auto auto;
  padding: 6px;
  position: relative;
}
.btn-post-more:hover {
  cursor: pointer;
  border-radius: 50%;
  background-color: #e4e4e4;
}
.btn-post-more img {
  display: table-row;
}

.hello {
  margin: 10px;
}
.gapo-avatar--40 {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  border: 1px solid #d2d2d2;
}
.name-request {
  line-height: 1.4;
  font-size: 15px;
  font-weight: 600;
  color: #1a1a1a;
}
.profile-post {
  text-decoration: none;
}

.post_time {
  font-size: 0.6789rem;
}
.post_time a {
  text-decoration: none;
}
.post-item {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  max-width: 600px;
  background-color: #fff;
  border-radius: 0.45rem;
  margin-bottom: 0.75rem;
  /* box-shadow: 0 1px 3px rgb(26 26 26 / 5%); */
  vertical-align: middle;
  margin: 0px auto;
  margin-bottom: 12px;
  border: 0.5px solid #dbdbdb;
}
.post-item_header {
  padding: 0.75rem 0.75rem 0.25rem 0.75rem;
}
.post-item-title {
  padding: 0.25rem 0.75rem 0.35rem 0.75rem;
  font-size: 14px;
}

.post-content-image {
  border-bottom: 0.5px solid #dbdbdb;
  /* border-radius: 3px; */
  display: flex;
  align-items: center;
  justify-content: center;
}
.post-content-image img {
  width: 100%;
  max-height: 1500px;
  height: auto;
}
.post-events {
  display: flex;
  padding: 3px 10px;
  column-gap: 5px;
}
.btn-events {
  color: rgba(var(--i1d, 38, 38, 38), 1);
  font-weight: 600;
  padding: 3px;
  font-size: 15px;
  display: flex;
  position: relative;
  height: 32px;
  justify-items: center;
  justify-content: center;
}
.btn-events:hover {
  background-color: #e9e9e99c;
}
.btn-events:focus {
  outline: 0 !important;
  background-color: rgba(91, 184, 16, 0.3);
  box-shadow: none;
}
.profile-post:hover {
  text-decoration: underline #1a1a1a !important;
}
.v-timeago {
  font-size: 12px;
}
.post_time a:hover {
  text-decoration: underline #657786;
}
.post-item-avatar:hover {
  opacity: 0.75;
}
.btn-events img {
  margin: auto 8px;
}
.btn-events-active {
  color: #5bb810;
}
.btn-events-active img[alt~="like"] {
  content: url("../../assets/icons/like_.svg");
}

.post-stats {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0.75rem;
  font-size: 15px;
}
.post-stats-like {
  display: flex;
  align-items: center;
}
.post-like-qty {
  margin-left: 5px;
}

.scaffold-post-input-cmt {
  display: grid;
  grid-template-columns: 40px auto;
  /* column-gap: 10px; */
}
.scaffold-post-input-cmt .vue-avatar--wrapper {
  box-shadow: 0 0 0 1px #dbdbdb;
}
.post-item-avatar .vue-avatar--wrapper {
  box-shadow: 0 0 0 0.5px #dbdbdb;
}
.post-input-cmt {
  border: none;
  width: 85%;
  font-size: 14px;
}
.post-input-cmt:focus {
  outline: none;
}
.around-input-cmt {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 10px;
  border: 1px solid #dbdbdb;
  border-radius: 35px;
}
.btn-submit-cmt {
  font-size: 14px;
  font-weight: 500;
  border: 1px solid #dbdbdb;
  border-radius: 10px;
  border: none;
  background-color: #fff;
  cursor: pointer;
}
.btn-submit-repCmt {
  font-size: 14px;
  font-weight: 500;
  border: 1px solid #dbdbdb;
  border-radius: 10px;
  border: none;
  background-color: #fff;
  cursor: pointer;
}
.btn-submit-cmt-available {
  color: #5bb810;
}
.btn-submit-repCmt-available {
  color: #5bb810;
}
.btn-submit-cmt-disable {
  pointer-events: none;
  color: #657786;
}
.btn-submit-repCmt-disable {
  pointer-events: none;
  color: #657786;
}
.scaffold-post-cmt {
  padding: 10px;
}
.detail_cmt {
  display: flex;
  column-gap: 5px;
}
.cmt-username {
  font-size: 15px;
  font-weight: 500;
  padding-right: 5px;
  color: #1a1a1a;
}
.cmt-username:hover,
:focus {
  color: #1a1a1a;
}
.scaffold-content-cmt {
  display: flex;
  width: max-content!important;
  align-items: center;
  border-radius: 30px;
  height: 35px;
  background-color: #f7f7f7;
  padding: 0 10px;
}
.cmt-content {
  font-size: 15px;
  font-weight: 400;
}
.cmt-time-stamp {
  padding-left: 10px;
  font-size: 12px;
}
.btn-repcmt {
  margin-left: 5px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 500;
}
.view-more-cmt {
  font-size: 15px;
  font-weight: 500;
  cursor: pointer;
}
.view-more-cmt:hover,
.view-more-cmt:focus {
  text-decoration: underline;
  color: #5bb810;
}
.scaffold-view-more-cmt {
  padding-bottom: 10px;
  display: flex;
  justify-content: flex-start;
}
.lb-grid {
  width: 100%;
  height: 100%;
  display: flex;
  position: absolute;
}
.lb {
  width: 100%;
  position: relative;
  overflow: hidden;
  padding-bottom: 100%;
}

.lb-modal-img img {
  max-width: 100%;
  max-height: 100% !important;
  vertical-align: middle;
  object-fit: contain;
}
.loading-view-more-cmts {
  margin: 0 3px !important;
}

.scaffold-post-options {
  position: absolute;
  background-color: #fff;
  z-index: 1000;
  top: 30px;
  right: 0;
  width: 200px;
  box-shadow: rgba(9, 30, 66, 0.25) 0px 4px 8px -2px,
    rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
}
.scaffold-repCmt-input{
  padding: 5px 0;
  display: grid;
  grid-template-columns: 40px auto;
}
.scaffold-repCmt-input .vue-avatar--wrapper {
  box-shadow: 0 0 0 1px #dbdbdb;
}
.around-input-repCmt{
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 10px;
  border: 1px solid #dbdbdb;
  border-radius: 35px;
}
.post-input-repCmt{
  border: none;
  width: 85%;
  font-size: 14px;
}
.post-input-repCmt:focus{
  outline: none;
}
.detail_cmt div{
  width: 100%;
}
</style>

