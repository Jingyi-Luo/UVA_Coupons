import Vue from 'vue'
import App from "./App.vue"
import iView from 'iview'
import 'iview/dist/styles/iview.css'
import axios from "axios"
import locale from 'iview/dist/locale/en-US';
import VueRouter from 'vue-router'
import Coupon from './coupon'
import Forum from './forum'
import search from './search'
import history from './history'
import { store } from './store'
Vue.use(VueRouter)
Vue.use(iView, { locale });

Vue.config.productionTip = false

export const host = process.env.NODE_ENV === 'production' ?
    "http://localhost/UVACoupon"
    : "http://localhost:8080/WB_COUPONS"

const routes = [
    { path: '/', redirect:'coupon' },
    { path: '/coupon', component: Coupon },
    { path: '/forum', component: Forum },
    { path: '/search', component: search },
    { path: '/history', component: history },
]

const router = new VueRouter({
    base:'UVACoupon',
    mode:'history',
    routes
})

var app = new Vue({
  router,
  store,
  render:h=>h(App)
}).$mount('#app')

Object.assign(window,{Vue,axios,app})




