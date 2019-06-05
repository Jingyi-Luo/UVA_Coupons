<template>
  <div id="coupon-wrapper">
    <Select class="top-select" v-model="selectType" multiple style="width:260px">
      <Option v-for="item in types" :value="item" :key="item">{{ item }}</Option>
    </Select>
    <section id="coupon" class="card-wrapper">
      <CouponItem class="card" v-for="(item,index) in onlines" :item="item" :key="index">{{item}}</CouponItem>
    </section>
  </div>
</template>
<script>
import CouponItem from './coupon-item'
export default {
  name: 'coupon',
  components: { CouponItem },
  data() {
    return {
      selectType: ['ALL']
    }
  },
  computed: {
    types() {
      var types = this.$store.state.online.map(x => x.o_type.toUpperCase())
      return ['ALL', ...new Set(types)]
    },
    onlines() {
      if (this.selectType.indexOf('ALL') !== -1) {
        return this.$store.state.online
      } else {
        return this.$store.state.online.filter(x => this.selectType.indexOf(x.o_type.toUpperCase()) !== -1)
      }
    }
  },
  methods: {

  }
}
</script>
<style lang="stylus">
@import './main.styl'

#coupon-wrapper{
    display flex
    flex-flow column nowrap
    justify-content flex-start
}
#coupon-wrapper .top-select {
  flex 0 0 60px
  margin-top 60px
  margin-left 12vw
  align-self flex-start
  .ivu-select-selection,.ivu-select-dropdown{
    background-color $color1
    color white
    .ivu-select-item{
        background-color $color1
        color white
        &:hover{
            background-color $color2
        }
    }
  }
}

#coupon {
  width 80vw
  margin 0 auto
  display flex
  flex-flow row wrap
  justify-content space-around
  align-items center

  .card {
    flex 1 0 200px
    height 300px
    margin 10px
    background-color $color3
  }

  .panel {
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

  @keyframes shake {
    0%, 75% {
      transform none
    }

    75%, 78% {
      transform scale(1)
    }

    81%, 84% {
      transform scale(1.2)
    }

    84%, 91% {
      transform scale(1.1)
    }

    91%, 90% {
      transform scale(1.4)
    }

    90%, 100% {
      transform scale(1)
    }
  }
}
</style>