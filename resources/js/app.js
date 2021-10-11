import Vue from 'vue'
import App from './App.vue'
import 'bootstrap';



//import vue router
import VueRouter from 'vue-router'
Vue.use(VueRouter)
import { ValidationObserver } from 'vee-validate'
import { ValidationProvider } from 'vee-validate/dist/vee-validate.full.esm'
Vue.component('ValidationObserver', ValidationObserver);
Vue.component('ValidationProvider', ValidationProvider);
//import VueModal
import VueModal from '@kouts/vue-modal'
import '@kouts/vue-modal/dist/vue-modal.css'
Vue.component('Modal', VueModal)
//import Vue icons svg
import icons from "v-svg-icons"
Vue.component("icon", icons)
//import Vue Loading
import VueLoading from 'vue-loading-template'
Vue.use(VueLoading, /** options **/)
//import Vuex
import Vuex from 'vuex'
Vue.use(Vuex)
//import Vue v-view
import 'viewerjs/dist/viewer.css'
import VueViewer from 'v-viewer'
Vue.use(VueViewer)
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
/* add font awesome icon component */
Vue.component('font-awesome-icon', FontAwesomeIcon)
//
import InfiniteLoading from 'vue-infinite-loading';

Vue.use(InfiniteLoading, { /* options */ });

///


//import my components
import Test from './test.vue'
import UploadAvt from './components/childs/UploadAvt.vue'
import Login from './components/pages/Login.vue'
import Home from './components/pages/Home.vue'
import Profile from './components/pages/Profile.vue'
import Notification from './components/pages/Notification.vue'
import Messenger from './components/pages/Messenger.vue'
import Search from './components/pages/Search.vue'
import CreatePost from './components/childs/CreatePost.vue'
import PageNotFound from './components/pages/PageNotFound.vue'
Vue.component('create-post', CreatePost);
import BaseRequest from './cores/BaseRequest';
import LinkImage from "./cores/LinkImage";



const store = new Vuex.Store({
	state: {
		userAuth: null,
		userAuth_Avt: null,
		userAuth_Cover: null,
		postsNewfeed: null,
		postsProfile: null,
		userInfo: null,
	},
	getters: {
		userAuth: state => state.userAuth,
		userInfo: state => state.userInfo,
		userAuth_Avt: state => state.userAuth_Avt,
		userAuth_Cover: state => state.userAuth_Cover,
		postsNewfeed: state => state.postsNewfeed,
		postsProfile: state => state.postsProfile
	},
	mutations: {
		setUserAuth(state, userAuth) {
			state.userAuth = userAuth;
		},
		setUserInfo(state, userInfo) {
			state.userInfo = userInfo;
		},
		setUserAuth_Avt(state, userAuth_Avt) {
			state.userAuth_Avt =  userAuth_Avt;
		},
		setUserAuth_Cover(state, userAuth_Cover) {
			state.userAuth_Cover = LinkImage.cover() + userAuth_Cover;
		},
		setPostsNewfeed(state, postsNewfeed) {
			state.postsNewfeed = postsNewfeed;
		},
		setPostsProfile(state, postsProfile) {
			state.postsProfile = postsProfile;
		},
		// setStatisticProfile(state, st){
		// 	state.userInfo.push(st);
		// }
		// ,
		createPostNewfeed(state,{post,currentPage}){
			if(currentPage=="home"){
				state.postsNewfeed.unshift(post);
			}else{
				state.postsProfile.unshift(post);
			}
			
		},
		loadMorePostNewfeed(state,posts){
			state.postsNewfeed.push(...posts);
		},
		deletePostNewfeed(state,{index,currentPage}){
			if(currentPage=="home"){
				state.postsNewfeed.splice(index,1);
			}else{
				state.postsProfile.splice(index,1);
			}
		},
		changeLikedPost(state,{index,currentPage}){
			if(currentPage=="home"){
				state.postsNewfeed[index].liked=!state.postsNewfeed[index].liked;
			}else{
				state.postsProfile[index].liked=!state.postsProfile[index].liked;
			}		
		},
		setLikedPost(state,{index,value,currentPage}){
			if(currentPage=="home"){
				state.postsNewfeed[index].liked=value;
			}else{
				state.postsProfile[index].liked=value;
			}
		},
		setStatusFriendForUser(state,statusFriend){
			state.userInfo.status_friend = statusFriend;
		}

	},
	actions: {
		getUserAuth({ commit }) {
			BaseRequest.get("user")
				.then((response) => {
					commit('setUserAuth', response.data.data[0]);
					commit('setUserAuth_Avt', response.data.data[0].img_avt);
					commit('setUserAuth_Cover', response.data.data[0].img_cover);
				})
				.catch((error) => {
					console.log(error);
				});
		},
		getPostsNewfeed({ commit }) {
			BaseRequest.get(`posts?arrPosts=''`)
				.then((response) => {
					// this.posts = response.data.data;
					commit('setPostsNewfeed',response.data.data);
					
				})
				.catch((error) => {
					console.log(error);
				});	
		},
		getUserProfile({ commit },id) {
			BaseRequest.get("userprofile/"+id)
				.then((response) => {
					commit('setPostsProfile',response.data.data);
					if(response.data.user[0]){
						var obj = response.data.user[0];
						var likeQty=0;
						var cmtQty=0;
						response.data.data.forEach(post => {
							likeQty+=post.like_qty;
							cmtQty+=post.cmt_qty;
						});
						obj.photos=response.data.photos;
						obj.statistic = {
							'likeQty' : likeQty,
							'cmtQty' : cmtQty,
							'postQty': response.data.data.length,
						};
					
						commit('setUserInfo',obj
							
							
						);
						// commit('setStatisticProfile',{'adsa':'dd'});
					}else{
						commit('setUserInfo',{'isNull':true});
					}
				})
				.catch((error) => {
					console.log(error);
				});
		},
		createPost({commit},post){
			commit('createPostNewfeed',post);
		},
		loadMorePostNewfeed({commit},posts){
			commit('loadMorePostNewfeed',posts);
		},
		deletePost({commit},index){
			commit('deletePostNewfeed',index);
		},
		changeLikedPost({commit},index){
			commit('changeLikedPost',index);
		},
	}
})

const routes = [
	{
		path: '/login', component: Login, name: 'login',
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				if (response.data.success == true) {
					next('/');
				} else {
					next();
				}
			}).catch(() => {
				// next('/login');
			})
		}
	},
	{
		path: '/', component: Home, name: 'home',
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				console.log(response);
				if (response.data.success == false) {
					next('/login');
				} else {
					next();
				}
			}).catch(() => {
				next('/login');
			})
		}
	},
	{
		path: '/profile/:id', component: Profile, name: 'profile',children:[
			{
				path: 'about',
				name: 'about'
			},
			{
				path: 'albums',
				name: 'albums'
			},
			{
				path: 'friends',
				name: 'friends'
			},
			{
				path: 'others',
				name: 'others'
			},
			
			
		],
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				if (response.data.success == false) {
					next('/login');
				} else {
					next();
				}
			}).catch(() => {
				next('/login');
			})
		},
	},
	{
		path: '/notification', component: Notification, name: 'notification',
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				if (response.data.success == false) {
					next('/login');
				} else {
					next();
				}
			}).catch(() => {
				next('/login');
			})
		}
	},
	{
		path: '/messenger', component: Messenger, name: 'messenger',
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				if (response.data.success == false) {
					next('/login');
				} else {
					next();
				}
			}).catch(() => {
				next('/login');
			})
		}
	},
	{
		path: '/search', component: Search, name: 'search',
		beforeEnter: (to, from, next) => {
			BaseRequest.get('checklogin').then(function (response) {
				if (response.data.success == true) {
					next();
				} else {
					next('/login');
				}
			}).catch(() => {
				next('/login');
			})
		}
	},
	{ path: '/test', component: Test, name: 'test' },
	{path:'*',component: PageNotFound}

]

const router = new VueRouter({
	mode: 'history',
	routes // short for `routes: routes`
})
Vue.config.productionTip = false
Vue.directive("focus", {
	inserted: function (el) {
		// Focus the element
		el.focus()
	},
	update: function (el, binding) {
		var value = binding.value;
		if (value) {
			Vue.nextTick(function () {
				el.focus();
			});
		}
	}
})
new Vue({
	router,
	components: {
		'modal': VueModal,
	},
	store,
	render: h => h(App),
}).$mount('#app')