<template>
  <div
    @click="check_o"
    class="coupon-item"
    :style="`background:url(${imgSrc}) no-repeat center/cover`"
  >
    <div class="shadow"></div>
    <div class="site-title">UVA COUPON</div>
    <div class="info">
      <div class="discount">{{item.o_discount_desc | percent}}</div>
      <div>{{item.product_name.toUpperCase()}}</div>
      <div>{{item.company_name.toUpperCase()}}</div>
    </div>
    <Modal class="item-modal" v-model="modal" width="500" class-name="vertical-center-modal">
      <p slot="header" style="color:#f60;text-align:left;margin-left:40px;font-size:19px">
        <span>UVA COUPON</span>
      </p>
      <div class="img" :style="`background:url(${imgSrc}) no-repeat center/cover`"></div>
      <div style="text-align:center">
        <ul>
          <li>
            <span>PRODUCT :</span>
            <span>{{item.product_name.toUpperCase()}}</span>
          </li>
          <li>
            <span>COMPANY :</span>
            <span>{{item.company_name.toUpperCase()}}</span>
          </li>
          <li>
            <span>DISCOUNT :</span>
            <span style="color:#e57200">{{item.o_discount_desc | percent}}</span>
          </li>
          <li>
            <span>START_DATE :</span>
            <span>{{item.o_effective_date}}</span>
          </li>
          <li>
            <span>END_DATE :</span>
            <span>{{item.o_expiration_date}}</span>
          </li>
          <li>
            <span>LINK :</span>
            <span>
              <a target="_blank" :href="item.link|href">{{item.link}}</a>
            </span>
          </li>
        </ul>
      </div>
      <div slot="footer"></div>
    </Modal>
  </div>
</template>
<script>
import axios from 'axios'
import { host } from './main'
const pics = "animals,cats,city,food,nature,sports".split(',')

function randPick(arr) {
  return arr[Math.floor(Math.random() * arr.length)];
}
function getPlaceHolderMsg(type) {
  type = (pics.indexOf(type) !== -1) ? type : randPick(pics)
  return "http://lorempixel.com/300/400/" + type + `/${Math.random() * 20 >> 1}`
}
export default {
  props: ["item"],
  data() {
    return {
      modal: false
    }
  },
  filters: {
    percent(str) {
      return Math.floor(parseFloat(str) * 100) + "% OFF"
    },
    href(str){
        return /http/.test(str) ? str: '//'+str
    }
  },
  computed: {
    keys() {
      return Object.keys(this.item)
    },
    imgSrc() {
      var src = this.item.o_photo_link
      if (/http/.test(src)) {
        return src;
      } else {
        return getPlaceHolderMsg(this.item.o_type)
      }
    }
  },
  methods: {
    /**
    * User click at online_coupon
    * 1. let his point++
    * 2. record this click in `clicks1`
    */
    async check_o() {
      this.modal = true
      var id = this.item.o_id
      var user = this.$store.state.user
      if(!user.email) return;
      Vue.set(this.$store.state.user, 'point_balance', this.$store.state.user.point_balance - 0 + 1)
      try {
        var sql = `UPDATE coupon_user SET point_balance = point_balance + 1 WHERE u_id = ${user.u_id}`
        await axios.post(host + "/server/sql.php", { sql: sql })
        let date = (new Date()).toISOString();
        axios.post(host + "/server/sql.php", {
          sql: `INSERT INTO clicks1 VALUES (${user.u_id}, ${id}, '${date}')`
        })
      } catch (err) {
        this.$Notice.error({ title: err.response.data })
      }
    },
  }
}
</script>
<style lang="stylus">
@import './main.styl'

.item-modal {
}

.vertical-center-modal {
  display flex
  align-items center
  justify-content center
  margin-top -180px

  .ivu-modal {
    ul {
      list-style none

      li {
        display flex
        flex-flow row
        justify-content flex-start
        align-items flex-start
        font-size 20px

        span:first-of-type {
          font-weight 700
          flex 0 0 150px
          text-align right
          margin-right 10px
        }
        span:nth-of-type(2){
          word-break break-all
        }
      }
    }

    .img {
      position absolute
      right 20px
      width 100px
      height 130px
    }
  }
}

.coupon-item {
  position relative

  &:hover .shadow {
    background-color rgba(1, 1, 1, 0.6)
    width 100%
    height 300px
  }

  .site-title {
    position absolute
    left 20px
    top 10px
    font-size 20px
    font-weight 900
    color white
  }

  .info {
    position absolute
    left 20px
    bottom 20px
    font-size 29px
    font-weight 700
    color white

    .discount {
      color $color3
    }
  }
}
</style>
