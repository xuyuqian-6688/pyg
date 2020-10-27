<template>
  <div>
    <!-- <el-container class="height100">
      <el-aside width="200px">
        <el-row>
          <el-col :span="20">
            <h3>电商后台管理系统</h3>
          </el-col>
          <el-col :span="4">
            <h5>3.0</h5>
          </el-col>
        </el-row>
      </el-aside>
      <el-main>
        <el-tabs v-model="activeName" @tab-click="handleClick" >
          
            <span slot="label">
              <i class="el-icon-date"></i> 我的行11程
            </span>
            
          <el-tab-pane label="用户管理" name="first">用户管理</el-tab-pane>
          <el-tab-pane label="配置管理" name="second">配置管理</el-tab-pane>
          <el-tab-pane label="角色管理" name="third">角色管理</el-tab-pane>
          <el-tab-pane label="定时任务补偿" name="fourth">定时任务补偿</el-tab-pane>
        </el-tabs>
      </el-main>
      <el-aside width="200px">Aside</el-aside>
    </el-container>-->

    <el-container>
      <el-header>
        <el-row>
          <el-col :span="4">
            <span>电商后台管理系统</span>
          </el-col>
          <el-col :span="16">
            <el-menu router class="el-menu-demo" mode="horizontal">
              <el-menu-item index="indexs">首页</el-menu-item>
              <el-menu-item index="platform">平台</el-menu-item>
              <el-menu-item index="shop">商城</el-menu-item>
              <el-menu-item index="operator">运营</el-menu-item>
            </el-menu>
          </el-col>
          <el-col :span="4">
            <el-dropdown>
              <span class="el-dropdown-link">
                admin
                <i class="el-icon-arrow-down el-icon--right"></i>
              </span>
              <el-dropdown-menu slot="dropdown">
                <el-dropdown-item>个人信息</el-dropdown-item>
                <el-dropdown-item>
                  <span @click="logout">退出</span>
                </el-dropdown-item>
              </el-dropdown-menu>
            </el-dropdown>
          </el-col>
        </el-row>
      </el-header>
      <el-main>
        <!-- 子组件显示位置 -->

        <router-view/>
      </el-main>
    </el-container>
  </div>
</template>

<script>
export default {
  mounted() {
    if (!localStorage.getItem("token")) {
      this.$router.push({ path: "/login" });
    }
    this.$router.push({ path: "indexs" });
  },
  methods: {
    logout() {
      // alert(2);
      this.$http({
        url: "logout"
      }).then(back => {
        console.log(back);
        localStorage.clear();
        this.$message({ message: "退出成功", type: "success" });
        this.$router.push({ path: "/login" });
      });
    }
  }
};
</script>

<style>
.bg-purple-light {
  font-size: 25px;
  color: white;
}
.el-col-4 {
  margin-top: 20px;
  text-align: center;
  font-size: 25px;
}
.el-aside {
  /* margin-left: 40px; */
}
</style>
