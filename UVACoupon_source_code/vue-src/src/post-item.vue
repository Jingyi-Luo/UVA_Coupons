<template>
  <div class="post-item">
    <Card class="card" style="width:320px">
      <div style="text-align:center">
        <div class="post-img" :style="`background:url(${imgSrc()}) no-repeat center/cover`"></div>
        <h3>{{item.title}}</h3>
        <h4>{{item.l_discount_desc}}</h4>
      </div>
    </Card>
    <div class="button" style="float:right" v-if="isMyPost(item)">
      <span @click="$emit('update',item.l_id)">UPDATE</span>
      <span @click="$emit('delete',item.l_id)">DELETE</span>
    </div>
    <section>
      <ul>
        <li>
          <strong>TYPE:</strong>
          <span>{{item.l_type}}</span>
        </li>
        <li>
          <strong>Available Time :</strong>
          <span>{{item.l_effective_date}} ~ {{item.l_expiration_date}}</span>
        </li>
        <li>
          <strong>Store :</strong>
          <span>{{item.store_name}}</span>
        </li>
        <li>
          <strong>Address :</strong>
          <span>{{item.address}}</span>
        </li>
      </ul>
    </section>
  </div>
</template>
<script>
const pics = "animals,cats,city,food,nature,sports".split(',')

function randPick(arr) {
  return arr[Math.floor(Math.random() * arr.length)];
}
function getPlaceHolderMsg(type) {
  type = (pics.indexOf(type) !== -1) ? type : randPick(pics)
  return "http://lorempixel.com/300/200/" + type + `/${Math.random() * 20 >> 1}`
}
export default {
  props: ["item"],
  data() {
    return {

    }
  },
  filters: {

  },
  computed: {
    keys() {
      return Object.keys(this.item)
    }
  },
  methods: {
    isMyPost(item) {
        if(!this.$store.state.user.email) return false
        return item.u_id == this.$store.state.user.u_id
    },
    imgSrc() {
      var src = this.item.l_photo_link
      if (/http/.test(src)) {
        return src;
      } else {
        return getPlaceHolderMsg(this.item.l_type)
      }
    }
  }
}
</script>
<style lang="stylus">
@import './main'

.post-item {
  display flex
  flex-direction row
  align-items flex-start
  position relative
  .card {
    flex 0 1 150px
  }

  .post-img {
    width 280px
    height 200px
  }

  .button {
    position absolute
    top 0px
    right 10px

    &>span {
      font-size 20px
      margin 0 10px
      color #3e76f6
      font-weight normal !important

      &:hover {
        color $color3
      }
    }
  }

  section {
    ul {
      margin 30px 30px

      li {
        font-size 20px

        strong {
          margin-right 10px
        }
      }
    }
  }
}
</style>
