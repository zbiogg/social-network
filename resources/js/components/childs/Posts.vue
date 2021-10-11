<template>
  <div>
    <detail-post
      v-for="(post, index) in postsNewfeed"
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
    <infinite-loading
      direction="bottom"
      @infinite="infiniteHandler"
    >
  <div slot="no-more">Bạn đã xem hết bài viết</div>
  <div slot="no-results">Bạn đã xem hết bài viết</div>
    </infinite-loading>
  </div>
</template>

<script>
import DetailPost from "../childs/DetailPost.vue";
import "vue2-timeago/dist/vue2-timeago.css";
import { mapGetters, mapActions } from "vuex";
import BaseRequest from "../../cores/BaseRequest";


export default {
  components: {
    "detail-post": DetailPost,
  },
  data() {
    return {
      arrPostsID: [],
    };
  },
  computed: {
    ...mapGetters(["userAuth", "userAuth_Avt", "postsNewfeed"]),
  },
  mounted() {
    this.getPostsNewfeed();
  },
  methods: {
    ...mapActions(["getPostsNewfeed","loadMorePostNewfeed"]),
    infiniteHandler($state) {
      this.arrPostsID = this.postsNewfeed.map((a) => a.postID);
      BaseRequest.get(`posts?arrPosts=`+this.arrPostsID)
          .then((res) => {
            if(res.data.data.length){
              this.loadMorePostNewfeed(res.data.data);
              $state.loaded();
            }else{
              $state.complete();
            }
            
          })
          .catch((er) => {
            console.log(er);
          });
    },
  },
};
</script>

<style>
/* .post-item-info,
.user-item-info {
  display: flex;
}

.btn-post-more {
  display: table;
  margin: 0 0 auto auto;
  padding: 6px;
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
  box-shadow: 0 1px 3px rgb(26 26 26 / 5%);
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
.btn-submit-cmt-available {
  color: #5bb810;
}
.btn-submit-cmt-disable {
  pointer-events: none;
  color: #657786;
}
.scaffold-post-cmt{
   padding: 10px;
}
.detail_cmt{
   display: flex;
   column-gap: 5px;
}
.cmt-username{
   font-size: 15px;
   font-weight: 500;
   padding-right: 5px;
}
.scaffold-content-cmt{
   display: flex;
   align-items: center;
   border-radius: 30px;
   height: 35px;
   background-color: #f7f7f7;
   padding: 0 10px;
}
.cmt-content{
   font-size: 15px;
   font-weight: 400;
}
.cmt-time-stamp{
   padding-left: 10px;
   font-size: 12px;
}
.btn-repcmt{
   margin-left: 5px;
   cursor: pointer;
   font-size: 14px;
   font-weight: 500;
}
.view-more-cmt{
  font-size: 14px;
  font-weight:500;
  cursor: pointer
}
.scaffold-view-more-cmt{
  padding: 5px 0;
} */
</style>
