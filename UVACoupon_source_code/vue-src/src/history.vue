<template>
  <div class="history">
    <Card style="width:350px">
      <h3 slot="title" style="color:rgba(81,90,110,0.7)">
        Browsing History of
        <strong style="color:rgb(81,90,110)">Online_Coupon</strong>
      </h3>
      <Spin fix class="spin" v-if="spin[1]">
        <Icon type="ios-loading" size="18" class="demo-spin-icon-load"></Icon>
        <div>Loading</div>
      </Spin>
      <Timeline class="time">
        <TimelineItem v-for="(piece,index) of $store.state.online_history" :key="index">
          <p class="time">
            <Time :time="piece.date"/>
          </p>
          <p class="content">{{piece.product_name}} - {{piece.company_name}}</p>
        </TimelineItem>
      </Timeline>
    </Card>
    <Card style="width:350px">
      <h3 slot="title" style="color:rgba(81,90,110,0.7)">
        Browsing History of
        <strong style="color:rgb(81,90,110)">Local_Coupon</strong>
      </h3>
      <Spin fix class="spin" v-if="spin[2]">
        <Icon type="ios-loading" size="18" class="demo-spin-icon-load"></Icon>
        <div>Loading</div>
      </Spin>
      <Timeline class="time">
        <TimelineItem v-for="(piece,index) of $store.state.local_history" :key="index">
          <p class="time">
            <Time :time="piece.date"/>
          </p>
          <p class="content">{{piece.title}} - {{piece.l_discount_desc}}</p>
        </TimelineItem>
      </Timeline>
    </Card>
    <Card style="width:350px">
      <h3 slot="title" style="color:rgba(81,90,110,0.7)">
        <strong style="color:rgb(81,90,110)">My POST</strong>
      </h3>
      <Spin fix class="spin" v-if="spin[3]">
        <Icon type="ios-loading" size="18" class="demo-spin-icon-load"></Icon>
        <div>Loading</div>
      </Spin>
      <Timeline class="time">
        <TimelineItem v-for="(piece,index) of $store.state.post_history" :key="index">
          <p class="time">
            <Time :time="piece.date"/>
          </p>
          <p class="content">{{piece.title}} - {{piece.l_discount_desc}}</p>
        </TimelineItem>
      </Timeline>
    </Card>
  </div>
</template>
<script>
import { host } from "./main.js"
import axios from 'axios'
export default {
  name: 'history',
  data() {
    return {
    }
  },
  computed: {
    user() { return this.$store.state.user },
    spin() { return this.$store.state.spin }
  },
  watch: {
    // 如果路由有变化，会再次执行该方法
    '$route': function (to, from) {
      if (/history/.test(to.path)) {
        this.$store.dispatch('updateHistory', this)
      }
    }
  },
}
</script>
<style scoped lang="stylus">
.history {
  padding-top 40px
  display flex
  flex-direction row
  justify-content space-around
  align-items flex-start
  position relative

  .time {
    max-height 70vh
    overflow auto
  }
}

>>> .ivu-card-body {
  min-height 120px
  transition height 1s ease-in-out
}

.demo-spin-icon-load {
  animation ani-demo-spin 1s linear infinite
}

.spin {
  position absolute
  top 45px
  right 0
  height calc(100% - 45px)
  left 0
}

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
</style>
