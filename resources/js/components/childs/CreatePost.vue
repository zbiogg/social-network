<template>
  <div class="scaffold-create-post">
    <div class="scaffold-create-post-item">
      <avatar
        :size="40"
        username="         "
        :src="userAuth_Avt?userAuth_Avt : '/img/avt/avt-default.png'"
      ></avatar>

      <button class="btn-show-create-post" @click="showModal = true">
        <span v-if="createPostData.content">{{ createPostData.content }}</span>
        <span v-else>Bấm zô đây để tạo bài viết</span>
      </button>
      <modal
        v-model="showModal"
        modalStyle="border-radius:6px; top: 62px"
        title="Tạo bài viết"
        wrapper-class="animate__animated"
        :in-class="`animate__fadeInDown`"
        :out-class="`animate__fadeOutUp`"
        bg-class="animate__animated"
        :bg-in-class="`animate__fadeInUp`"
        :bg-out-class="`animate__fadeOutDown`"
      >
        <div>
          <div class="user-item-info align-items-center">
            <avatar :size="50" username="       " :src="userAuth_Avt?userAuth_Avt : '/img/avt/avt-default.png'"></avatar>

            <div class="mx-2">
              <span class="name-user-create-post">{{
                userAuth ? userAuth.lastName + " " + userAuth.firstName : ""
              }}</span>
              <div class="create-post-privacy">
                <img src="../../assets/icons/public.svg" width="14px" alt="" />
                <span>Công khai</span>
              </div>
            </div>
          </div>
          <div class="scaffold-content-create-post">
            <textarea
            
              class="input-post"
              rows="3"
              placeholder="Bạn đang nghĩ gì?"
              v-model="createPostData.content"
            ></textarea>
            <UploadImages
              uploadMsg="Nhấp để chọn ảnh hoặc kéo thả ảnh tại đây"
              v-if="showPickImage"
              @changed="checkImageCreate"
            />
          </div>
          <div class="scaffold-create-post-options">
            <label
              class="btn-create-option"
              type="file"
              @click="changeAcceptInputImage()"
            >
              <img src="../../assets/icons/photo.svg" width="24px" alt="" />
              <span>Ảnh</span>
            </label>
            <label class="btn-create-option" @click="checkImageCreate">
              <img src="../../assets/icons/video.svg" width="24px" alt="" />
              <span>Video</span>
            </label>
            <label class="btn-create-option" @click="bugFunction">
              <img src="../../assets/icons/event.svg" width="24px" alt="" />
              <span>Sự kiện</span>
            </label>
            <label class="btn-create-option" @click="bugFunction">
              <img
                src="../../assets/icons/iconfelling.svg"
                width="24px"
                alt=""
              />
              <span>Cảm xúc</span>
            </label>
          </div>
          <div class="scaffold-btn-create-post">
            <button
              @click="submitCreatePost"
              :class="[
                (createPostData.content || createPostData.images.length != 0) &&
                !loadingCreatePost
                  ? 'btn-create-post-available'
                  : 'btn-create-post-disable',
                'btn-create-post',
              ]"
            >
              <vue-loading
                v-if="loadingCreatePost"
                type="cylon"
                color="#5bb810"
                :size="{ width: '25px', height: '25px' }"
              ></vue-loading>
              <span v-else>Đăng</span>
            </button>
          </div>
        </div>
      </modal>
    </div>
    <div class="scaffold-create-post-options">
      <label
        class="btn-create-option"
        type="file"
        @click="changeAcceptInputImage()"
      >
        <img src="../../assets/icons/photo.svg" width="24px" alt="" />
        <span>Ảnh</span>
      </label>
      <label class="btn-create-option" @click="bugFunction">
        <img src="../../assets/icons/video.svg" width="24px" alt="" />
        <span>Video</span>
      </label>
      <label class="btn-create-option" @click="bugFunction">
        <img src="../../assets/icons/event.svg" width="24px" alt="" />
        <span>Sự kiện</span>
      </label>
      <label class="btn-create-option" @click="bugFunction">
        <img src="../../assets/icons/iconfelling.svg" width="24px" alt="" />
        <span>Cảm xúc</span>
      </label>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
import BaseRequest from "../../cores/BaseRequest";
import "animate.css";
import Avatar from "vue-avatar";
import UploadImages from "vue-upload-drop-images";

export default {
  data() {
    return {
      srcImageCreatePost: null,
      showModal: false,
      createPostData: {
        content: null,
        images: [],
      },
      loadingCreatePost: false,
      showPickImage: false,
      uploadImages: UploadImages,
      imagePickFiles: [],
    };
  },
  components: {
    Avatar,
    UploadImages,
  },
  computed: {
    ...mapGetters(["userAuth", "userAuth_Avt", "postsNewfeed"]),
  },
  methods: {
    ...mapActions(["createPost"]),
    // previewImageCreatePost: function (event) {
    //   var input = event.target;
    //   if (input.files) {
    //     var reader = new FileReader();
    //     reader.onload = (e) => {
    //       this.srcImageCreatePost = e.target.result;
    //       this.createPostData.image = e.target.result.split(",")[1];
    //     };
    //     this.createPostData.image = input.files[0];
    //     reader.readAsDataURL(input.files[0]);
    //   }
    // },
    submitCreatePost: function () {
      var self = this;
      var content = self.createPostData.content;
      var images = self.createPostData.images;
      self.createPostData.content = null;
      self.createPostData.images = [];
      if (content || images.length != 0) {
        self.loadingCreatePost = true;
        BaseRequest.post("posts", {
          post_Content: content,
          post_Images: images,
        })
          .then(function (response) {
            if (response.data.success) {
              self.createPost({
                post: response.data.data[0],
                currentPage: self.$route.name,
              });
              self.createPostData.content = null;
              self.createPostData.images = [];
              self.srcImageCreatePost = null;
              self.showModal = false;
              self.loadingCreatePost = false;
            }
          })
          .catch(function (error) {
            console.log(error);
            self.loadingCreatePost = false;
          });
      }
    },
    bugFunction: function () {
      alert("Coi như là Bug đi =))");
    },
    addImageToPostData() {
      var self = this;
      self.createPostData.images = [];
      const pictures = document.getElementsByClassName("imageHolder");
      Array.prototype.forEach.call(pictures, function (p, index) {
        if (self.imagePickFiles[index].type == "image/svg+xml") {
          alert("Định dạng SVG không được hỗ trợ!");
          document.querySelectorAll(".delete")[index].click();
        } else {
          self.createPostData.images.push(
            p.firstElementChild.src.split(",")[1]
          );
        }
      });
    },
    checkImageCreate(files) {
      setTimeout(this.addImageToPostData, 200);
      this.imagePickFiles = files;
    },
    changeAcceptInputImage() {
      this.showPickImage = true;
      this.showModal = true;
    },
  },
};
</script>
<style>
.user-item-info {
  display: flex;
}
.preview-image-create-post {
  min-width: 200px;
  max-width: 100%;
  max-height: 1000px;
  border: 0.5px solid #dbdbdb;
  border-radius: 6px;
}
.btn-create-post {
  border: none;
  border-radius: 6px;
  padding: 4px 15px;
  font-weight: 500;
}
.btn-create-post-available {
  background-color: #5bb810;
  color: #fff;
  box-shadow: inset 1px 1px 1px 1px rgb(#5bb810 / 8%);
}
.btn-create-post-disable {
  background-color: #dbdbdb;
  color: rgb(255, 255, 255);
  box-shadow: inset 1px 1px 1px 1px rgb(#dbdbdb / 8%);
}
.btn-create-post-available:hover {
  opacity: 0.85;
}
.btn-create-post-disable:hover {
  pointer-events: auto;
  cursor: no-drop !important;
}

.scaffold-btn-create-post {
  display: flex;
  justify-content: flex-end;
}
.name-user-create-post {
  font-size: 16px;
  font-weight: 600;
  color: #1a1a1a;
}
.vue-loading {
  display: flex;
  align-items: center;
}
.scaffold-create-post {
  background-color: #fff;
  border-radius: 10px;
  max-width: 600px;
  margin: 0 auto;
  margin-bottom: 12px;
  border: 0.5px solid #dbdbdb;
  padding: 5px;
  background-image: linear-gradient(135deg, rgb(114, 211, 35) 5%, #fff 0%);
}
.scaffold-create-post-item {
  display: flex;
  padding: 15px 20px 5px 20px;
  align-items: center;
}
.btn-show-create-post {
  display: flex;
  align-items: center;
  background-color: #fff;
  border-radius: 30px;
  border: 1px solid #b9aeae;
  flex-grow: 1;
  height: 40px;
}
.btn-show-create-post:hover,
.btn-show-create-post:focus {
  background-color: rgba(232, 247, 222, 0.947);
  outline: none;
}
.btn-show-create-post span {
  margin-left: 10px;
  color: #657786;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
  font-weight: 500;
  font-size: 14px;
}
.scaffold-create-post-item .vue-avatar--wrapper {
  margin-right: 10px;
  box-shadow: 0 0 0 1px #b9aeae;
}
.scaffold-create-post-options {
  padding: 0 20px;
  display: flex;
  justify-content: space-between;
}
.btn-create-option {
  cursor: pointer;
  border: 0px;
  background-color: #fff;
  padding: 10px;
  border-radius: 6px;
  display: flex;
  align-content: center;
  align-items: center;
  justify-content: center;
  justify-items: center;
  font-size: 14px;
  font-weight: 600;
  color: #657786;
  margin-bottom: 0!important;
}
.btn-create-option:hover {
  background-color: rgba(232, 247, 222, 0.947);
}
.btn-create-option img {
  margin-right: 6px;
}
button {
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}
.scaffold-create-post-item a:hover {
  opacity: 0.75;
}
.create-post-privacy {
  border: 1px solid #dbdbdb;
  border-radius: 6px;
  width: max-content;
  display: flex;
  font-size: 14px;
  font-weight: 500;
  color: #1a1a1abe;
  align-items: center;
  line-height: 1.2;
  padding: 3px 6px;
}
.create-post-privacy img {
  margin-right: 3px;
}
.input-post {
  overflow: hidden;
  width: 100%;
  border: none;
  resize: none;
}
.input-post:focus {
  outline: none;
}
.imgsPreview .imageHolder {
  padding: 10px;
  width: 80px !important;
  height: 80px !important;
  border-radius: 6px !important;
  border: 0.5px solid #dbdbdb;
  z-index: 1;
}
.scaffold-content-create-post .container {
  padding: 30px 0px 10px 10px;
  min-height: 150px;
  margin-bottom: 5px;
}
.clearButton {
  visibility: hidden;
}
.clearButton::after {
  font-size: 14px;
  font-weight: 500;
  visibility: visible;
  content: "Xoá tất cả";
}
.clearButton:hover {
  opacity: 0.9;
}
.scaffold-content-create-post {
  overflow: auto;
  max-height: 350px;
  margin-top: 5px;
}
.beforeUpload .icon {
  width: 60px !important;
}
.mainMessage {
  color: #1a1a1a;
  font-size: 15px;
}
</style>