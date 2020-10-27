<template>
  <div>
    <el-container>
      <el-aside width="300px">
        <el-menu default-active="2" class="el-menu-vertical-demo" router>

          <el-submenu index="1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>订单管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="/manager_order">订单管理</el-menu-item>
              <el-menu-item index="/complain_order">投诉订单</el-menu-item>
              <el-menu-item index="refund_order">退款订单</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="2">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>商品管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="/goods_category">商品分类</el-menu-item>
              <el-menu-item index="/goods_brand">商品品牌</el-menu-item>
              <el-menu-item index="/goods_type">商品模型</el-menu-item>
              <el-menu-item index="/goods_list">商品列表</el-menu-item>
              <el-menu-item index="">回收站</el-menu-item>
              <el-menu-item index="">商品属性</el-menu-item>
              <el-menu-item index="">商品规格</el-menu-item>
              <el-menu-item index="">评价管理</el-menu-item>
              <el-menu-item index="">举报管理</el-menu-item>
              <el-menu-item index="">商品咨询</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="3">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>会员管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="">会员等级</el-menu-item>
              <el-menu-item index="">会员管理</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="4">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>基础设置</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="">广告管理</el-menu-item>
              <el-menu-item index="">银行管理</el-menu-item>
              <el-menu-item index="">支付管理</el-menu-item>
            </el-menu-item-group>
          </el-submenu>

          <el-submenu index="5">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>众筹管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="">众筹管理</el-menu-item>
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
