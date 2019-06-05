<template>
  <div id="post-wrapper">
    <Button
      class="post"
      size="large"
      ghost
      icon="ios-send"
      type="default"
      @click="state='create';value3=true"
      v-if="$store.state.user.email"
    >POST A NEW COUPON</Button>
    <Drawer
      :title="state.toUpperCase()"
      v-model="value3"
      width="720"
      :mask-closable="false"
      :styles="styles"
    >
      <Form :model="formData">
        <Row :gutter="32">
          <Col span="12">
            <FormItem label="Title" label-position="top">
              <Input v-model="formData.title" placeholder="please enter title"/>
            </FormItem>
          </Col>
          <Col span="12">
            <FormItem label="Address" label-position="top">
              <Input v-model="formData.address" placeholder="please enter address"></Input>
            </FormItem>
          </Col>
        </Row>
        <Row :gutter="32">
          <Col span="12">
            <FormItem label="Store Name" label-position="top">
              <Input v-model="formData.store_name" placeholder="please enter Store Name"/>
            </FormItem>
          </Col>
          <Col span="12">
            <FormItem label="Type" label-position="top">
              <Select v-model="formData.type" placeholder="please choose the type">
                <Option
                  :value="type.toUpperCase()"
                  v-for="type of types"
                  :key="type"
                >{{type.toUpperCase()}}</Option>
              </Select>
            </FormItem>
          </Col>
        </Row>
        <Row :gutter="32">
          <Col span="12">
            <FormItem label="Photo Link" label-position="top">
              <Input
                v-model="formData.photo_link"
                placeholder="You can upload your photo to https://sm.ms to get link"
              />
            </FormItem>
          </Col>
          <Col span="12">
            <FormItem label="Effective Date" label-position="top">
              <DatePicker
                v-model="formData.dates"
                type="daterange"
                placeholder="please select the date"
                style="display: block"
                placement="bottom-end"
              ></DatePicker>
            </FormItem>
          </Col>
        </Row>
        <FormItem label="Description" label-position="top">
          <Input
            type="textarea"
            v-model="formData.discount"
            :rows="4"
            placeholder=".eg: $5 off of Tesla Model3"
          />
        </FormItem>
      </Form>
      <div class="demo-drawer-footer">
        <Button style="margin-right: 8px" @click="value3 = false">Cancel</Button>
        <Button
          type="primary"
          :loading="loading"
          @click="submit"
        >{{state=='create'?"Submit":"Update"}}</Button>
      </div>
    </Drawer>
  </div>
</template>
<script>
import axios from 'axios';
import { host } from './main'
export default {
  name: '',
  data() {
    return {
      value3: false,
      loading: false,
      styles: {
        height: 'calc(100% - 55px)',
        overflow: 'auto',
        paddingBottom: '53px',
        position: 'static'
      },
      state: "create" || "delete" || "update",
      types: ['other', 'food', 'Fashion', 'Beauty', 'digital', 'entertainment', 'fitness', 'health', 'grocery'],
      formData: {
        title: '',
        address: '',
        store_name: '',
        type: '',
        photo_link: '',
        dates: '',
        discount: '',
        l_id: '',
      },
    }
  },
  methods: {
    update(l_id) {
      this.state = "update"
      this.value3 = true
      var item = this.$store.state.local.find(x => x.l_id == l_id)
      this.formData = {
        title: item.title,
        address: item.address,
        store_name: item.store_name,
        type: item.l_type.toUpperCase(),
        photo_link: item.l_photo_link,
        dates: [item.l_effective_date, item.l_expiration_date],
        discount: item.l_discount_desc,
        l_id: l_id
      }
    },
    delete(l_id) {
      this.$Modal.confirm({
        title: 'DELETE THIS LOCAL_COUPON ?',
        loading: true,
        onOk: () => {
          var sql = `delete from local_coupon where l_id=${l_id}`
          axios.post(host + '/server/sql.php', { sql }).then(_ => {
            this.$Modal.remove()
            this.$Message.info('DELETE ok');
            this.$store.dispatch('updateForum', this)
          }).catch(err => {
            this.$Message.warning({
              content: err.response.data,
              duration: 5,
              closable: true,
              top: 100
            })
          })

        },
        onCancel: () => {
          this.$Message.info('DELETE canceled');
        }
      });
    },
    submit() {
      this.loading = true;
      if (this.formData.dates[0]) {
        var e_date = (new Date(this.formData.dates[0])).toISOString().replace(/T.*$/, "");
        var ex_date = (new Date(this.formData.dates[1])).toISOString().replace(/T.*$/, "");
      } else {
        var e_date = "2019-01-01"
        var ex_date = "2019-12-31"
      }
      if (this.state == "update") {
        axios.post(host + '/server/update.php', {
          u_id: this.$store.state.user.u_id,
          title: this.formData.title,
          type: this.formData.type,
          discount: this.formData.discount,
          date: new Date().toISOString(),
          e_date,
          ex_date,
          store_name: this.formData.store_name,
          address: this.formData.address,
          photo_link: this.formData.photo_link,
          l_id: this.formData.l_id
        }).then(res => {
          this.value3 = false;
          this.loading = false;
          this.$Modal.success({
            title: "Update Success",
          });
          this.$store.dispatch('updateForum', this)
        }).catch(err => {
          this.$Message.warning({
            content: err.response.data,
            duration: 5,
            closable: true,
            top: 100
          })
        })
      } else {
        axios.post(host + '/server/post.php', {
          u_id: this.$store.state.user.u_id,
          title: this.formData.title,
          type: this.formData.type,
          discount: this.formData.discount,
          date: new Date().toISOString(),
          e_date,
          ex_date,
          store_name: this.formData.store_name,
          address: this.formData.address,
          photo_link: this.formData.photo_link,
        }).then(res => {
          this.value3 = false;
          this.loading = false;
          this.$Modal.success({
            title: "Submit Success",
          });
          this.$store.dispatch('updateForum', this)
        }).catch(err => {
          this.$Message.warning({
            content: err.response.data,
            duration: 5,
            closable: true,
            top: 100
          })
        })
      }

    }
  }
}
</script>
<style lang="stylus">
@import './main'

#post-wrapper {
  width 100%
  height 80px
  display flex
  align-items center
  justify-content center

  button {
    color white
    border 1px solid white
  }

  button:hover {
    color $color3
    border 1px solid $color3
  }

  .demo-drawer-footer {
    width 100%
    position absolute
    bottom 0
    left 0
    border-top 1px solid #e8e8e8
    padding 10px 16px
    text-align right
    background #fff
  }
}
</style>
