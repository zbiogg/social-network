<template>
<div class="scaffold-suggest-item">
  <div class="suggest-item-info">
    <router-link :to="'/profile/'+userid" class="suggest-item-avatar  m-1">
        <avatar class="sg-user-avt" :size="55" username="           " 
        :src="userAvt?userAvt:'/img/avt/avt-default.png'"></avatar>
    </router-link>
    <div class ="mx-1 ">
      <router-link class="profile-suggest" :to="'/profile/'+userid">
      <span class="name-suggest">{{username}}</span>
      </router-link>
      <div v-if="mutual_friends!='0'" class="suggest-mutual-friends">
           <span>{{mutual_friends}} bạn chung</span>   
      </div>
      <button class="btn btn-add-friend" @click="statusFriend==-1?addFriend():cancelAddFriend()">
        <img v-if="statusFriend==-1" src="../../assets/icons/add.svg" width="12px" alt="add-friend">
        <span v-if="statusFriend==-1">Thêm bạn</span>
        <span v-else-if="statusFriend==0">Hủy lời mời</span>
        <span v-else-if="statusFriend==1">Bạn Bè</span>
      </button>
    </div>
  </div>
  
</div>
</template>

<script>
import Avatar from 'vue-avatar';
import LinkImage from '../../cores/LinkImage';
import BaseRequest from '../../cores/BaseRequest';
export default {
  data(){
    return {
      linkUserAvt: LinkImage.avt(),
      statusFriend: -1
    }
  },
  components:{
    Avatar
  },
  props: ['username','mutual_friends','userAvt','userid'],

  methods:{
    addFriend : function(){
      BaseRequest.post('addFriend',{
        receiverID: this.userid
      }).then(response=>{
        this.statusFriend=response.data.status;
      }).catch(error=>{
        console.log(error)
      });
      document.activeElement.blur();
    },
    cancelAddFriend: function(){
      BaseRequest.post('cancelAddFriend',{
        receiverID: this.userid
      }).then(response=>{
        if(response.data.success==true){
          this.statusFriend=-1;
        }
      }).catch(error=>{
        console.log(error)
      });
      document.activeElement.blur();
    }
  }
}
</script>

<style>
  .suggest-item-info{
    display: flex;
    font-family: Segoe UI;
    align-items: center;
  }
  .name-suggest{
    line-height: 1.4;
    font-size: 15px;
    font-weight: 600;
    color: #1a1a1a;
  }
  .profile-suggest{
    text-decoration: none;
  }
  .profile-suggest:hover{
    text-decoration: underline;
    color: #1a1a1a;
  }
  .suggest-mutual-friends span{
    font-size: 12px;
  }
  .btn-add-friend{
    border-radius: 20px;
    padding: 6px 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 15px;
    font-weight: 600;
    color: #5a5757;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.6);
    margin-top: 5px;
  }
  .btn-add-friend:hover{
    background-color: #fff;
    box-shadow: none;
    border: 1px solid rgba(0,0,0,0.6);
  }
  .btn-add-friend:focus{
    /* opacity: 1; */
    background-color: #a1e26cc2;
    box-shadow: none;
    border: 1px solid rgba(0,0,0,0.6);
  }
  .btn-add-friend img{
    margin-right: 5px;
  }
  .scaffold-suggest-item{
    padding: 10px;
    border-radius: 10px;
  }
  .scaffold-suggest-item:hover{
    background-color: rgba(232, 247, 222, 0.547)
  }

  .suggest-item-avatar .vue-avatar--wrapper:hover{
    opacity: 0.75;
    
  }

  .sg-user-avt{
    box-shadow: 0 0 0 1px #dbdbdb;
  }
</style>