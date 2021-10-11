<template>
  <div>
    <loadingbar></loadingbar>
    <zbio-header></zbio-header>
    <div class="zbio-main">
      <div class="scaffold-content-search">
        <div class="scaffold-left-content">
          <div class="scaffold-main-left-content">
            <div class="scaffold-options-search">
              
                <span class="title-options-search-left-content"
                  >Kết quả tìm kiếm</span>
            </div>
            <div class="line-hr"></div>
            <div class="options-search">
              <div class="option-search-item">
                <div class="icon-option-search-item">
                  <icon name="pager" width="18px" height="18px"></icon>
                </div>
                <span class="title-option-search">Tất cả</span>
              </div>
              <div class="option-search-item">
                <div class="icon-option-search-item">
                  <icon name="user-alt" width="18px" height="18px"></icon>
                </div>
                <span class="title-option-search">Mọi người</span>
              </div>
              <div class="option-search-item">
                <div class="icon-option-search-item">
                  <icon name="credit-card" width="18px" height="18px"></icon>
                </div>
                <span class="title-option-search">Bài viết</span>
              </div>
              
              
            </div>
          </div>
        </div>
        <div class="scaffold-right-content">
          
          <div class="scaffold-result-search-people">
            <div class="title-for-search-people">
              <span>Mọi người</span>
            </div>
            <div class="result-search-people-item" v-for="(u,index) in search_users" :key="index">
              <div class="search-user-info">
                <router-link
                  :to="'/profile/'+u.id"
                  class="search-user-item-avatar m-1"
                >
                  <avatar
                    class="search-user-avt"
                    :size="55"
                    username="           "
                    :src="u.img_avt?u.img_avt:'/img/avt/avt-default.png'"
                  ></avatar>
                </router-link>
                <div class="mx-1">
                  <router-link :to="'/profile/'+u.id" class="name-search-result-user">{{u.lastName}} {{u.firstName}}</router-link>
                  <div class="search-user-mutual-friends">
                    <div v-if="u.status_friend=='1'">Bạn bè</div>
                    <div v-if="u.mutual_friends!='0'">{{u.mutual_friends}} bạn chung</div>
                  </div>
                </div>
                <div class="scaffold-action-search">
                  <icon name="messenger-regular" width="24px" height="24px"></icon>
                </div>
              </div>
              <hr v-if="index!=Object.keys(search_users).length-1">
        
            </div>
          </div>
          <div v-if="search_posts" class="scaffold-search-posts">
            <detail-post
              v-for="(post, index) in search_posts"
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
    <!-- <div
      class="zbio-main"
      v-if="Object.keys(search_users)==0&&Object.keys(search_posts)==0"
    >
      <div class="not-found-link">
        <div class="scaffold-icon-not-found">
          <icon name="unlink" width="50px" height="50px"></icon>
        </div>

        <span class="title-not-found">Không tìm thấy kết quả tìm kiếm phù hợp!</span>
        <div class="btn-to-home">
          <router-link to="/">Đi tới bảng tin</router-link>
        </div>
      </div>
    </div> -->
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
      search_posts: [],
      search_users: {}
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

  mounted() {
    window.scrollTo(0, 0);
    this.getResultSearch();
  },
  watch: {
    $route() {
      this.getResultSearch();
    }
  },

  methods: {
    getResultSearch(){
      BaseRequest.get('search?key_search='+this.$route.query.key_search)
      .then(res =>{
        console.log(res);
        this.search_users=res.data.search_users;
        this.search_posts=res.data.search_posts;
      }).catch(console.log);
    }
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

@media screen and (min-width: 992px) {
  .scaffold-content-search {
    margin-top: 10px;
    min-height: 200px;
    display: grid;
    grid-template-columns: minmax(0, 4fr) minmax(0, 6fr);
    column-gap: 15px;
  }

}

.scaffold-content-search {
  margin-top: 10px;
  min-height: 200px;
  display: grid;
  column-gap: 15px;
  position: relative;
}
.scaffold-left-content {
  height: max-content;
  border-radius: 8px;
  margin-bottom: 10px;
  position: sticky;
  top: 70px;
  box-shadow: rgba(0, 0, 0, 0.08) 0px 4px 12px;
}
.scaffold-right-content {
  /*  */
}

.scaffold-main-left-content { 
  background-color: #fff;
  border-radius: 5px;
  text-align: center;
  overscroll-behavior: contain;
}
.scaffold-options-search {
  padding-top: 10px;
}
.scaffold-options-search a {
  text-decoration: none;
}
.title-options-search-left-content {
  font-family: Cambria, Cochin, Georgia, Times, "Times New Roman", serif;
  font-weight: 600;
  font-size: 18px;
  color: #5bb810;
  text-shadow: 0px 0px 1px #b9b9b9;
}
.search-user-info{
  display: flex;
  position: relative;
  align-items: center;
}
.scaffold-action-search{
  position: absolute;
  right: 0;
}
.search-user-mutual-friends{
  font-size: 13px;
  color: #3a3939;
}
.name-search-result-user{
  font-size: 16px;
  font-weight: 600;
  color: #1a1a1a;
}
.scaffold-result-search-people{
  background-color: #fff;
  border-radius: 8px;
  padding: 10px;
  box-shadow: rgba(0, 0, 0, 0.08) 0px 4px 12px;
}
.title-for-search-people{
  margin-bottom: 10px!important;
  font-weight: 700;
}
.result-search-people-item{

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
.options-search{
  padding: 10px;
}
.option-search-item{
  cursor: pointer;
  display: flex;
  border-radius: 6px;
  padding: 8px 15px;
  align-items: center;
  column-gap: 6px;
} 
.option-search-item:hover{
  background-color: rgba(232, 247, 222, 0.547)
}
.title-option-search{
  font-size: 15px;
  font-weight: 500;
  color: #1a1a1a;
}
.icon-option-search-item{
  display: flex;
  align-items: center;
  justify-content: center;
  background-color: #dbdada;
  border-radius: 50%;
  width: 35px;
  height: 35px;
}
.post-item{
  box-shadow: rgba(0, 0, 0, 0.08) 0px 4px 12px;
}
.scaffold-search-posts{
  margin-top: 15px;
}

</style>
