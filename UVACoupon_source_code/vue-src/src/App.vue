<template>
  <div>
    <header id="header">
      <div class="nav-left">
        <a target="_blank" href="http://www.virginia.edu"></a>
      </div>
      <div class="nav-title">UVA COUPON</div>
      <div class="nav-mid">
        <router-link to="/coupon">COUPON</router-link>
        <router-link to="/forum">FORUM</router-link>
        <router-link to="/search">SEARCH</router-link>
      </div>

      <div class="nav-right">
        <div v-if="!user.name" class="no-log">
          <i-button
            @click="state=0;loginValue = true"
            type="default"
            ghost
            style="margin-right:5px"
          >Sign in</i-button>
          <i-button type="default" ghost @click="state=1;loginValue=true">Sign up</i-button>
        </div>
        <div v-else class="user-info">
          <i-button id="user-name" type="default" ghost>
            {{user.name}}
            <Icon :class="{'rotate':rotate}" type="ios-star"/>
            {{user.point_balance}}
          </i-button>
          <Card class="user-card" dis-hover>
            <div slot="title">
              <Icon type="ios-mail-outline" size="18"/>
              {{user.email}}
            </div>
            <i-button style="float:right" type="text" ghost @click="logout">Sign out</i-button>
            <i-button style="float:left" type="text" ghost>
              <router-link id="history-link" to="/history">History</router-link>
            </i-button>
          </Card>
        </div>
      </div>
    </header>
    <keep-alive>
      <router-view class="body"></router-view>
    </keep-alive>

    <Drawer
      :title="state===0?'Sign In':'Sign Up'"
      v-model="loginValue"
      width="720"
      :mask-closable="false"
      :styles="styles"
    >
      <Form :model="form">
        <Row :gutter="32">
          <Col span="24">
            <FormItem label="username" label-position="top">
              <Input v-model="form.username" placeholder="please enter username"/>
            </FormItem>
          </Col>
        </Row>
        <Row>
          <Col span="24">
            <FormItem label="email" label-position="top">
              <Input v-model="form.email" placeholder="please enter email"></Input>
            </FormItem>
          </Col>
        </Row>
      </Form>
      <div class="demo-drawer-footer">
        <Button style="margin-right: 8px" @click="loginValue = false">Cancel</Button>
        <Button type="primary" :loading="loading" @click="submit">{{state===0?"Sign In":"Sign Up"}}</Button>
      </div>
    </Drawer>
  </div>
</template>
<script>
import axios from 'axios'
import { host } from "./main.js"

export default {
  name: 'UVAcoupon',
  components: {
  },
  data() {
    return {
      rotate: 0,
      state: 0,
      loginValue: false,
      loading: false,
      form: {
        username: '',
        email: '',
      },
      styles: {
        height: 'calc(100% - 55px)',
        overflow: 'auto',
        paddingBottom: '53px',
        position: 'static'
      },
    }
  },
  computed: {
    user() { return this.$store.state.user },
  },
  filters: {
    percent(str) {
      return "-" + str * 100 + "%"
    }
  },
  watch: {
    '$store.state.user.point_balance': function () {
      this.rotate = 1;
      setTimeout(() => {
        this.rotate = 0
      }, 500)
    }
  },
  methods: {
    submit() {
      this.loading = true;
      this.login(this.form.username, this.form.email)
    },
    login(username, email) {
      if (this.state == 0) {
        axios.post(host + "/server/login.php", { name: username, email }).then(o => {
          this.$store.commit('update', ['user', o.data])
          this.loading = false
          this.$Notice.success({
            title: "Sign In Success!",
            duration: 1
          });
          this.loginValue = false
          this.$store.dispatch('updateHistory')
        }).catch(err => {
          this.$Notice.error({
            title: err.response.data,
            duration: 3
          });
          this.loading = false
        })
      } else {
        axios.post(host + "/server/signup.php", { name: username, email }).then(o => {
          this.$store.commit('update', ['user', o.data])
          this.loading = false
          this.$Notice.success({
            title: "Sign Up Success!",
            duration: 1
          });
          this.loginValue = false
          this.$store.dispatch('updateHistory')
        }).catch(err => {
          this.$Notice.error({ title: err.response.data, duration: 3 });
          this.loading = false
        })
      }

    },
    logout() {
      this.$store.commit('update', ['user', {}]);
      this.$store.commit('update', ['local_history', {}]);
      this.$store.commit('update', ['online_history', {}]);
      this.$store.commit('update', ['post_history', {}]);

      axios.post(host + "/server/logout.php").catch(err => this.$Notice.error({
        title: err.response.data
      }))
    },
  },
  created() {
    this.$store.dispatch('init', this)
  }
}

</script>
<style lang="stylus">
@import './main.styl'

#header {
  display flex
  flex-direction row
  justify-content space-between
  align-items center
  height 60px
  background-color $color1

  .nav-left a {
    display block
    background url('http://www.virginia.edu/sites/all/themes/custom/uva/images/logo-primary-ko.png') no-repeat center center
    background-size contain
    width 250px
    height 40px
  }

  .nav-title {
    margin-left -100px
    font-size 28px
    color white
    font-family 'PT Serif', serif

    @media (max-width: 900px) {
      display none
    }
  }

  .nav-mid {
    a:hover {
      color #E57200
    }

    .router-link-active {
      color $color3
    }

    a {
      display inline-block
      height 60px
      line-height 60px
      text-align center
      text-transform uppercase
      color #fff
      text-decoration none
      font-size 16px
    }

    a+a:before {
      content '/'
      color $color3
      font-weight 900
      display inline-block
      margin 0 10px
    }
  }

  .nav-right {
    margin-right 0px
    flex 0 0 200px

    .ivu-btn-ghost.ivu-btn-dashed:hover, .ivu-btn-ghost.ivu-btn-default:hover {
      color $color3
      border-color $color3
    }

    & > div {
      margin-right 20px
      float right
    }
  }
}

@keyframes rotate {
  0% {
    transform-origin center
    transform rotate3d(0, 1, 0, 0deg)
  }

  100% {
    transform-origin center
    transform rotate3d(0, 1, 0, 180deg)
  }
}

.user-info {
  #user-name i {
    display inline-block
    width 14px
    margin-bottom 2px
    margin-left 10px
    font-size 1.2em
    vertical-align middle
    color $color3

    &.rotate {
      animation rotate 1 0.5s ease-in-out
    }
  }

  .user-card {
    display none
    position absolute
    z-index 2
    right 20px
    margin-top 2px
    background-color $color1
    color white

    .ivu-card-body {
      padding 0
      line-height 1.2
    }
  }

  &:hover .user-card {
    display block
  }
}

.body {
  background-color $color4
  width 100vw
  height calc(100vh - 60px)
  overflow auto
  display flex
  flex-flow column nowrap
  justify-content center
  align-items center
}

#history-link {
  color white

  &:hover {
    color #2d8cf8
  }
}
</style>