<template>
  <div>
    <el-container>
      <el-aside width="300px">
        <el-menu default-active="2" class="el-menu-vertical-demo" router>
          <el-submenu index="1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>促销管理</span>
            </template>
          </el-submenu>
          <el-submenu index="2">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>推荐管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index>店铺推荐</el-menu-item>
              <el-menu-item index>商品推荐</el-menu-item>
              <el-menu-item index>品牌推荐</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="3">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>统计报表</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index>商品销售排行</el-menu-item>
              <el-menu-item index>店铺销售排行</el-menu-item>
              <el-menu-item index>销售额统计</el-menu-item>
              <el-menu-item index>销售订单统计</el-menu-item>
              <el-menu-item index>新增会员统计</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="4">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>财务管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index>资金管理</el-menu-item>
              <el-menu-item index>体现申请</el-menu-item>
              <el-menu-item index>结算申请</el-menu-item>
              <el-menu-item index>商家结算</el-menu-item>
            </el-menu-item-group>
          </el-submenu>
        </el-menu>
      </el-aside>
      <el-main>
        <!-- 子组件显示位置 -->
        <router-view/>
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {};
</script>

<style>
</style>
