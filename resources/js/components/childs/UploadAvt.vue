<template>
  <div>
    <div > <my-upload
      @crop-success="cropSuccess"
      @crop-upload-success="cropUploadSuccess"
      @crop-upload-fail="cropUploadFail"
      v-model="show"
      :width="888"
      :height="888"
      :lang-ext="en"
      :headers="headers"
      img-format="png"
    ></my-upload></div>
  </div>
</template>


<script>
import "@babel/polyfill";
import myUpload from 'vue-image-crop-upload/upload-2.vue';
import BaseRequest from "../../cores/BaseRequest";
import { mapGetters, mapActions,mapMutations } from "vuex";

export default {
  data() {
    return {
      show: false,
      params: {
        img_Avt: null
      },
      headers: BaseRequest.getHeader(),
      imgDataUrl: "", // the datebase64 url of created image,
      en: {
        hint: "Nhấp hoặc kéo tệp vào đây để tải lên",
        loading: "Đang upload...",
        noSupported:
          "Browser is not supported, please use IE10+ or other browsers",
        success: "Upload thành công",
        fail: "Upload lỗi",
        preview: "Xem trước",
        btn: {
          off: "Hủy",
          close: "Đóng",
          back: "Quay lại",
          save: "Lưu",
        },
        error: {
          onlyImg: "Vui lòng chọn tệp hình ảnh",
          outOfSize: "Kích thước ảnh vượt quá giới hạn cho phép: ",
          lowestPx: "Kích thước của hình ảnh quá thấp. Tối thiểu: ",
        },
      },
    };
  },
  created() {
        this.$root.$refs.UploadAvt = this;
    },
  components: {
    "my-upload": myUpload,
  },
  methods: {
    ...mapMutations(['setUserAuth_Avt']),
    toggleShow() {
       console.log("hhaa");
      this.show = !this.show;
    },
    /**
     * crop success
     *
     * [param] imgDataUrl
     * [param] field
     */
    cropSuccess(imgDataUrl, field) {
      console.log("-------- crop success --------");
      this.imgDataUrl = imgDataUrl;
      this.params.img_Avt=imgDataUrl.split(',')[1];
      BaseRequest.post('upAvt',{
         img_Avt: imgDataUrl.split(',')[1]
      }).then(response =>{
         if(response.data.success==true){
           this.setUserAuth_Avt(response.data.dataImage);
         }
      }).catch(error =>{
         console.log(error);
      })
    },
    /**
     * upload success
     *
     * [param] jsonData  server api return data, already json encode
     * [param] field
     */
    cropUploadSuccess(jsonData, field) {
      console.log("-------- upload success --------");
      console.log(jsonData);
      console.log("field: " + field);
    },
    /**
     * upload fail
     *
     * [param] status    server api return error status, like 500
     * [param] field
     */
    cropUploadFail(status, field) {
      console.log("-------- upload fail --------");
      console.log(status);
      console.log("field: " + field);
    },
  },
};
</script>
<style>
 .vicp-close{
   top: -25px!important;
   right: -25px!important;
 }
 .vicp-wrap{
   border-radius: 6px!important;
   /* background-color: #66e95a!important; */
 }
 .vicp-drop-area{
   border-radius: 6px;
 }
 .vicp-step1::before,.vicp-step2::before{
   content: "Thay đổi ảnh đại diện";
   font-weight: 500;
   font-size: 18px;
   margin-bottom: 20px!important;
 }
 .vicp-drop-area,.vicp-crop{
   margin-top: 15px;
 }
</style>