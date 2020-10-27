<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商城</el-breadcrumb-item>
      <el-breadcrumb-item>商品品牌</el-breadcrumb-item>
    </el-breadcrumb>

    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" >添加品牌</el-button>
        </div>
      </el-col>
    </el-row>

    <el-table :data="BrandListData" style="width: 100%">
      <el-table-column prop="id" label="ID" ></el-table-column>
      <el-table-column prop="sort" label="排序" ></el-table-column>
      <el-table-column label="LOGO" >
        <template slot-scope="scope">
          <el-image
            style="width: 100px; height: 30px"
            :src="'http://LaravelPyg:8020/'+scope.row.logo"
          ></el-image>
        </template>
      </el-table-column>
      <el-table-column prop="name" label="品牌名称" ></el-table-column>
      <el-table-column prop="cate_name" label="品牌分类" ></el-table-column>
      <el-table-column prop="desc" label="品牌描述"></el-table-column>

      <el-table-column label="操作" >
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
      BrandListData: []
    };
  },

  methods: {
    getCategoryList() {
      this.$http({
        url: "brands"
      }).then(back => {
        //console.log(back.data.data);
        this.BrandListData = back.data.data.data;
      });
    }
  },

  mounted() {
    this.getCategoryList();
  }
};
</script>

<style>
.el-row{
  margin: 10px;
}
</style>
