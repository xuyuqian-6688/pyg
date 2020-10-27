<template>
  <div>
    <el-container>
      <el-aside width="300px">
        <el-menu default-active="2" class="el-menu-vertical-demo" router>
          <el-submenu index="1">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>系统配置</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="">配置管理</el-menu-item>
              <el-menu-item index="">网站设置</el-menu-item>
              <el-menu-item index="">附件管理</el-menu-item>
              <el-menu-item index="/manager_auth">权限管理</el-menu-item>
            </el-menu-item-group>
          </el-submenu>
          <el-submenu index="2">
            <template slot="title">
              <i class="el-icon-location"></i>
              <span>权限管理</span>
            </template>
            <el-menu-item-group>
              <el-menu-item index="/manager_admin">管理员管理</el-menu-item>
              <el-menu-item index="/manager_role">角色管理</el-menu-item>
              <el-menu-item index="/manager_log">管理日志</el-menu-item>
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
