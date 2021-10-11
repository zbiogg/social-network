<template>
  <router-link class="link-to-search-item" :to="'/profile/'+userID">
    <div class="scaffold-search-user-item">
      <div class="search-user-item-info">  
          <avatar
            class="sg-user-avt mr-1"
            :size="30"
            username="           "
            :src="userAvt?userAvt:'/img/avt/avt-default.png'"
          ></avatar>
        <div class="mx-1">
            <span class="name-search-user">{{userFullname}}</span>
          <div class="search-user-mutual-friends">
            <span v-if="mutual_friends!='0'">{{status_friend=='1'?'Bạn bè':'('+mutual_friends+' bạn chung)'}}</span>
          </div>
        </div>
      </div>
    </div>
  </router-link>
</template>
<script>
import Avatar from "vue-avatar";
export default {
  components: {
    Avatar,
  },
  props:[
      'userID',
      'userFullname',
      'userAvt',
      'mutual_friends',
      'status_friend'
  ]
};
</script>
<style>

.search-user-item-info {
  display: flex;
  font-family: Segoe UI;
  align-items: center;
}
.name-search-user {
  line-height: 1.4;
  font-size: 14px;
  font-weight: 600;
  color: #1a1a1a;
}

.search-user-mutual-friends span {
  font-size: 12px;
  color: #1a1a1a;
}

.btn-add-friend img {
  margin-right: 5px;
}
.scaffold-search-user-item {
  padding: 10px;
  border-radius: 6px;
  line-height: 1;
}
.scaffold-search-user-item:hover {
  background-color: rgba(182, 219, 157, 0.87);
  text-decoration: none;
}



.link-to-search-item:hover{
    text-decoration: none;

}
</style>