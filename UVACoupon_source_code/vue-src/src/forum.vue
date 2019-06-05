<template>
  <section id="forum-wrapper">
    <div class="top">
      <Select class="top-select" v-model="selectType" multiple style="width:260px">
        <Option v-for="item in types" :value="item" :key="item">{{ item }}</Option>
      </Select>
      <Post class="post" @post="getLocal" ref="post"></Post>
    </div>
    <Collapse accordion @on-change="check_l" style="overflow:auto">
      <Spin fix class="spin" v-if="spin">
        <Icon type="ios-loading" size="18" class="demo-spin-icon-load"></Icon>
        <div>Loading</div>
      </Spin>
      <Panel class="panel" v-for="(item,index) of local" :key="index" :name="item.l_id">
        <span>{{item.title}}</span>
        <span style="margin:0 10px;">
          <Time :time="item.date"/>
        </span>
        <Icon class="star" :class="{'rotate':1}" type="ios-star" v-if="isMyPost(item)" />
        <PostItem :item="item" slot="content" @update="$refs.post.update" @delete="$refs.post.delete"></PostItem>
      </Panel>
    </Collapse>
  </section>
</template>
<script>
import axios from 'axios'
import PostItem from './post-item'
import Post from "./post.vue"
import { host } from './main'
export default {
  name: 'forum',
  components: {
    PostItem,
    Post,
  },
  data() {
    return {
      selectType: ["ALL"],
    }
  },
  computed: {
    spin() { return this.$store.state.spin4 },
    user() { return this.$store.state.user },
    types() {
      var types = this.$store.state.local.map(x => x.l_type.toUpperCase())
      return ['ALL', ...new Set(types)]
    },
    local() {
      if (this.selectType.indexOf('ALL') !== -1) {
        return this.$store.state.local
      } else {
        return this.$store.state.local.filter(x => this.selectType.indexOf(x.l_type.toUpperCase()) !== -1)
      }
    },
  },
  methods: {
    isMyPost(item) {
        if(!this.$store.state.user.email) return false
        return item.u_id == this.$store.state.user.u_id
    },
    async check_l(actives) {
      var l_id = actives[0]; if (!l_id) return;
      if (!this.$store.state.user.email) return;
      try {
        //self ponit --
        let ThisPostIsFromMyself = this.$store.state.post_history.find(x => {
          return x.u_id === this.$store.state.user.u_id
            && x.l_id === l_id
        })
        if (!ThisPostIsFromMyself) {
          Vue.set(this.$store.state.user, 'point_balance', this.$store.state.user.point_balance - 1)
        }
        var sql = `UPDATE coupon_user SET point_balance = point_balance - 1 WHERE u_id = ${this.user.u_id}`
        await axios.post(host + "/server/sql.php", { sql: sql })
        //insert into post
        let date = (new Date()).toISOString();
        axios.post(host + "/server/sql.php", {
          sql: `INSERT INTO clicks2 VALUES (${this.user.u_id}, ${l_id}, '${date}')`
        })
        //author point++
        var complicated = `
        update coupon_user
        set point_balance = point_balance+1
        where u_id =  (select distinct post.u_id from clicks2 inner join post on clicks2.l_id = post.l_id where clicks2.l_id=${l_id});`
        axios.post(host + "/server/sql.php", { sql: complicated })
      } catch (err) {
        this.$Notice.error({ title: err.response.data })
      }
    },
    getLocal() {
      axios.post(host + "/server/sql.php", {
        sql: "SELECT * FROM local_coupon"
      })
        .then(o => this.$store.state.local = o.data)
        .catch(err => this.$Notice.error({
          title: err.response.data
        }))
    }
  }
}
</script>
<style lang="stylus">
@import './main'

@keyframes ani-demo-spin {
  from {
    transform rotate(0deg)
  }

  50% {
    transform rotate(180deg)
  }

  to {
    transform rotate(360deg)
  }
}

#forum-wrapper {
  display flex
  flex-flow column nowrap
  justify-content flex-start

  .ivu-collapse {
    position relative
    min-height 120px
    min-width 800px
  }

  .demo-spin-icon-load {
    animation ani-demo-spin 1s linear infinite
  }

  .spin {
    position absolute
    top 0px
    right 0
    height 100%
    left 0
  }
}

#forum-wrapper .top {
  display flex
  flex-flow row nowrap
  width 800px
  justify-content space-between
  align-items center

  .post {
    flex 0 0
  }
}

#forum-wrapper .top-select {
  .ivu-select-selection, .ivu-select-dropdown {
    background-color $color1
    color white

    .ivu-select-item {
      background-color $color1
      color white

      &:hover {
        background-color $color2
      }
    }
  }
}

.panel {
  width 800px
  .star{
      color $color3
  }
  .ivu-collapse-header {
    // background-color $color1 !important
  }
  .ivu-collapse-content{
      width 800px
  }

  div>span:nth-of-type(1) {
    font-weight 700
  }

  .discount {
    color red
    display inline-block
    margin 0 10px
    animation shake 0.3s infinite ease-in-out
  }

  .discount + span {
    color grey
  }

  .panel-table {
    overflow auto
  }
  
}
</style>
