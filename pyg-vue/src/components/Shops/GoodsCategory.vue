<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商城</el-breadcrumb-item>
      <el-breadcrumb-item>商品分类</el-breadcrumb-item>
    </el-breadcrumb>

    <el-table :data="categoryListData" style="width: 100%">
      <el-table-column prop="id" label="ID" ></el-table-column>
      <el-table-column prop="cate_name" label="分类名称"></el-table-column>
      <el-table-column prop="image_url" label="分类图片"></el-table-column>
      <el-table-column prop="is_show" label="是否显示" ></el-table-column>
      <el-table-column prop="is_hot" label="是否热门" ></el-table-column>
      
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
      categoryListData: []
    };
  },
  methods: {
    getCategoryList() {
      this.$http({
        url: "categorys"
      }).then(back => {
        // console.log(back);
        this.categoryListData = back.data.data;
      });
    }
  },

  mounted() {
    this.getCategoryList();
  }
};
</script>

<style>
</style>
