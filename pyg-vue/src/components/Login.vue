<template>
  <div class="login-wrap">
    <el-form ref="form" label-width="80px" :model="loginForm" class="login-from">
      <h2>品有购后台管理系统 - 登录</h2>
      <el-form-item label="用户名">
        <el-input v-model="loginForm.username"></el-input>
      </el-form-item>
      <el-form-item label="密码">
        <el-input type="password" v-model="loginForm.password"></el-input>
      </el-form-item>
      <el-form-item label="验证码">
        <el-col :span="11">
          <el-input v-model="loginForm.code"></el-input>
        </el-col>
        <el-col class="line" :span="2">&nbsp;</el-col>
        <el-col :span="11">
          <el-image style="width: 100px; height: 35px" :src="verify.src"></el-image>
          <i class="el-icon-refresh" size="mini" @click="verifyCode"></i>
        </el-col>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" class="login-btn" @click="login">登录</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      verify: {},
      loginForm: {
        username: "",
        password: "",
        code: "",
        uniqid:""
      }
    };
  },

  methods: {
    
    // 获取验证码
    verifyCode() {
      this.$http({
        url: "captcha"
      }).then(backdata => {
        // console.log(backdata);
        var { data } = backdata.data;
        this.verify = data;
        this.uniqid = data.uniqid;
        //console.log(this.uniqid);
        //console.log(data);
        //let res = JSON.String(data);
        //console.log(res);
        //let res = data.uniqid;
        //console.log(res);
      });
    },

    // 登陆
    login() {
      this.loginForm.uniqid = this.verify.uniqid;
      // console.log(this.loginForm);
      this.$http({
        url: "logins",
        method: "post",
        data: this.loginForm
      }).then(backdata => {
        // console.log(backdata.data);
        var { data, code, msg } = backdata.data;
        // 登陆结果提示
        if (code == 200 && msg == "success") {
            // 存储token
            localStorage.setItem('token',data.token);
            // 跳转到后台首页
            this.$router.push({path:'/index'})
          this.$message({ message: "登陆成功", type: "success" });
        } else {
          this.$message({ message: msg, type: "warning" });
        }
      });
    }
  },

  mounted() {
    // 获取验证码
    this.verifyCode();
  }
};
</script>

<style>
.login-wrap {
  background-color: #324152;
  height: 100%;
  display: flex;
  justify-content: center;
  align-items: center;
}

.login-wrap .login-from {
  background-color: #fff;
  width: 400px;
  padding: 30px;
  border-radius: 5px;
}

.login-wrap .login-from .login-btn {
  width: 100%;
}
</style>
