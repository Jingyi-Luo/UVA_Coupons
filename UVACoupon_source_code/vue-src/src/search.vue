<template>
  <div class="search">
    <Input size="large" @on-search="search" enter-button class="search-input" search v-model="sql">
      <Select v-model="select" slot="prepend" style="width: 80px">
        <Option value="user">user</Option>
        <Option value="coupon">coupon</Option>
        <Option value="sql">SQL</Option>
      </Select>
    </Input>
    <Card shadow v-if="datas.length">
      <Table max-height="300" class="search-table" :columns="columns(datas)" :data="datas"></Table>
    </Card>
  </div>
</template>
<script>
import { host } from "./main.js"
export default {
  name: 'search',
  data() {
    return {
      datas: [],
      sql: '',
      select: 'sql',
    }
  },
  methods: {
    columns(obj) {
      return Object.keys(obj[0]).map(x => ({ title: x, key: x }))
    },
    search(key) {
      var sqls = {
        'user': `select * from coupon_user where name like '%${key}%' OR email like '%${key}%'`,
        'coupon': `select * from online_coupon where product_name like '%${key}%' 
                                              OR company_name like '%${key}%'
                                              OR o_type like '%${key}%'`,
        'sql': this.sql
      }
      var sql = sqls[this.select].replace(/\s+/g, ' ')
      axios.post(host + "/server/sql.php", { sql: sql })
        .then(res => this.datas = res.data)
        .catch(err => this.$Message.warning({
          content: err.response.data,
          duration: 5,
          closable: true,
          top: 100
        }))
    }
  },
  created() {
    // var sql = `select  * from clicks1 inner join online_coupon where clicks1.o_id = online_coupon.o_id AND clicks1.u_id = ${this.user.u_id}`

  }
}
</script>
<style lang="stylus">
@import './main'

.search {
  .search-input {
    margin 0px auto 100px
    width 60%

    .ivu-input-group-append.ivu-input-search {
      background $color1 !important
    }
  }

  .search-table {
    width 80vw
  }

  .ivu-input-group-prepend {
    border-color $color1 !important

    &, & * {
      background $color1 !important
      color white
      font-weight 700
    }
  }

  .ivu-input-group-append.ivu-input-search {
    border 1px solid $color3 !important
  }
}
</style>
