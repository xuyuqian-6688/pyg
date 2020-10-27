<template>
  <div>
    <el-container>
      <el-aside width="300px">
        <el-menu
          default-active="2"
          class="el-menu-vertical-demo"
          router
        >
          <el-submenu index="1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>首页</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="indexs">首页</el-menu-item>
            </el-menu-item-group>
          </el-submenu>
        </el-menu>
      </el-aside>
      <el-main>

        首页内容区域


      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  // mounted() {
  //   if (!localStorage.getItem("token")) {
  //     this.$router.push({ path: "/login" });
  //   }
  // }
};
</script>

<style>
</style>
