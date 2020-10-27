<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商城</el-breadcrumb-item>
      <el-breadcrumb-item>订单管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-table :data="orderListData" style="width: 100%">
      <el-table-column prop="id" label="ID" width="50"></el-table-column>
      <el-table-column prop="user_id" label="用户ID" width="80"></el-table-column>
      <el-table-column prop="nickname" label="用户昵称" width="120"></el-table-column>
      <el-table-column prop="order_sn" label="订单编号" width="160"></el-table-column>
      <el-table-column prop="order_status" label=" 订单状态" width="120"></el-table-column>
      <el-table-column  label=" 下单时间" width="120"></el-table-column>      
      <el-table-column prop="address" label="地址"></el-table-column>
      <el-table-column  label="操作" width="120">
           <template slot-scope="" >
          <i class="el-icon-edit" size="mini" ></i>
          <i class="el-icon-delete" size="mini" ></i>
        </template>
      </el-table-column>

    </el-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      orderListData: []
    };
  },

  methods: {
    getOrderList() {
      this.$http({
        url: "orders"
        // headers: { Authorization: localStorage.getItem("token") }
      }).then(back => {
        console.log(back.data.data);
        this.orderListData = back.data.data.data;
      });
    }
  },

  mounted() {
    this.getOrderList();
  }
};
</script>

<style>
</style>
