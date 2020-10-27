<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商城</el-breadcrumb-item>
      <el-breadcrumb-item>商品列表</el-breadcrumb-item>
    </el-breadcrumb>

    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" @click="showAddGoods">添加商品</el-button>
        </div>
      </el-col>
    </el-row>
    <!-- 添加商品弹窗 -->
    <el-dialog title="添加商品" :visible.sync="isShowaddGoods" fullscreen>

      <el-tabs tab-position="top" style="height: 200px;">
        <el-tab-pane label="用户管理">通用信息</el-tab-pane>
        <el-tab-pane label="配置管理">商品相册</el-tab-pane>
        <el-tab-pane label="角色管理">商品模型</el-tab-pane>
        <el-tab-pane label="定时任务补偿">其他信息</el-tab-pane>
      </el-tabs>

      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowaddGoods = false">取 消</el-button>
        <el-button type="primary" >确 定</el-button>
      </div>
    </el-dialog>

    <!-- 商品列表 -->
    <el-table :data="goodsListData" style="width: 100%">
      <el-table-column prop="id" label="ID"></el-table-column>
      <el-table-column prop="goods_name" label="商品名称"></el-table-column>
      <el-table-column label="商品LOGO">
        <template slot-scope="scope">
          <el-image
            style="width: 80px; height: 110px"
            :src="'http://LaravelPyg:8020/'+scope.row.goods_logo"
          ></el-image>
        </template>
      </el-table-column>
      <el-table-column prop="goods_price" label="商品价格"></el-table-column>
      <el-table-column prop="goods_number" label="库存"></el-table-column>
      <el-table-column prop="sales_num" label="销量"></el-table-column>
      <el-table-column prop="cate_name" label="商品分类"></el-table-column>

      <el-table-column label="操作">
        <template slot-scope>
          <i class="el-icon-edit" size="mini"></i>
          <i class="el-icon-delete" size="mini"></i>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      goodsListData: [],
      isShowaddGoods: false
    };
  },

  methods: {
    getGoodsList() {
      this.$http({
        url: "goods"
      }).then(back => {
        //console.log(back.data);
        this.goodsListData = back.data.data.data;
      });
    },

    showAddGoods() {
      this.isShowaddGoods = true;
    }
  },

  mounted() {
    this.getGoodsList();
  }
};
</script>

<style>
</style>
