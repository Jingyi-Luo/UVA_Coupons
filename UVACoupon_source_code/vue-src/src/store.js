import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
import { host } from './main'
Vue.use(Vuex)

const defaultUser = {
    name: 'Ann',
    email: 'ann123@gmail.com'
}

export var store = () => new Vuex.Store({
    state: {
        user: {},
        online: [],
        local: [],
        online_history: [],
        local_history: [],
        post_history: [],
        spin: { 1: 1, 2: 1, 3: 1},
        spin4:1,
    },
    getters: {},
    mutations: {
        update(state, [key, val]) {
            state[key] = val
        },
    },
    actions: {
        async init(store, that) {
            store.dispatch('updateForum')
            axios.post(host + "/server/sql.php", {
                sql: "SELECT * FROM online_coupon"
            }).then(res => store.commit('update', ['online', res.data]))

            var res = await axios.post(host + "/server/login.php", defaultUser)
            store.commit('update', ['user', res.data])
            store.dispatch('updateHistory')
        },
        async updateForum(store, that={}) {
            store.state.spin4 = 1
            axios.post(host + "/server/sql.php", {
                sql: "select * from local_coupon inner join post where local_coupon.l_id = post.l_id"
            }).then(o => {
                store.commit('update', ['local', o.data])
                Vue.nextTick(() => store.state.spin4 = 0)
            })
            .catch(err => console.log(err))
        },
        async updateHistory(store, that) {
            store.state.spin = { 1: 1, 2: 1, 3: 1 }
            var sql = `select  * from clicks1 inner join online_coupon where clicks1.o_id = online_coupon.o_id AND clicks1.u_id = ${store.state.user.u_id}`
            axios.post(host + "/server/sql.php", { sql: sql })
                .then(res => {
                    store.commit('update', ['online_history', res.data.sort((a, b) => a.date > b.date ? -1 : 1)]);
                    Vue.nextTick(() => store.state.spin[1] = 0)
                })
                .catch(err => console.log(err))

            sql = `select  * from clicks2 inner join local_coupon where clicks2.l_id = local_coupon.l_id AND clicks2.u_id = ${store.state.user.u_id}`
            axios.post(host + "/server/sql.php", { sql: sql })
                .then(res => {
                    store.commit('update', ['local_history', res.data.sort((a, b) => a.date > b.date ? -1 : 1)]);
                    Vue.nextTick(() => store.state.spin[2] = 0)
                })
                .catch(err => console.log(err))

            sql = `select * from local_coupon inner join post where local_coupon.l_id = post.l_id AND u_id =${store.state.user.u_id}`
            axios.post(host + "/server/sql.php", { sql: sql })
                .then(res => {
                    store.commit('update', ['post_history', res.data.sort((a, b) => a.date > b.date ? -1 : 1)]);
                    Vue.nextTick(() => store.state.spin[3] = 0)
                })
                .catch(err => console.log(err))
        }
    },
})