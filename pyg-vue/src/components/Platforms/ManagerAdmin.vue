<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>管理员管理</el-breadcrumb-item>
      <el-breadcrumb-item>管理员列表</el-breadcrumb-item>
    </el-breadcrumb>
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" @click="showAddAdmin">添加管理员</el-button>
        </div>
      </el-col>
    </el-row>

    <el-table :data="adminList" stripe style="width: 100%">
      <el-table-column prop="id" label="id" width="180"></el-table-column>
      <el-table-column prop="username" label="登陆名" width="180"></el-table-column>
      <el-table-column prop="nickname" label="昵称"></el-table-column>
      <el-table-column prop="email" label="邮箱"></el-table-column>
      <el-table-column prop="role_name" label="角色"></el-table-column>
      <el-table-column label="是否启用">
         <template slot-scope="scope">
          <el-switch v-model="scope.row.status" active-color="#13ce66" inactive-color="#ff4949"></el-switch>
        </template>
      </el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <i class="el-icon-edit" size="mini" @click="editAdminShow(scope.row)"></i> &nbsp;
          <i class="el-icon-delete" size="mini" @click="delAdmin(scope.row)"></i>
        </template>
      </el-table-column>
    </el-table>

    <!-- 添加管理员弹窗 -->
    <el-dialog title="添加管理员" :visible.sync="isShowAddAdmin">
      <el-form :model="addAdminData">
        <el-form-item label="管理员">
          <el-input v-model="addAdminData.username" autocomplete="off" label-width="10px"></el-input>
        </el-form-item>
        <el-form-item label="初始密码">
          <el-input v-model="addAdminData.password" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="邮箱">
          <el-input v-model="addAdminData.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="角色">
          <el-select v-model="addAdminData.role_id" placeholder="请选择角色">
            <el-option
              v-for="role_v in roleList"
              :key="role_v.id"
              :label="role_v.role_name"
              :value="role_v.id"
            ></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowAddAdmin = false">取 消</el-button>
        <el-button type="primary" @click="addRoleAdmin">确 定</el-button>
      </div>
    </el-dialog>

    <!-- 修改管理员弹窗 -->
    <el-dialog title="修改管理员" :visible.sync="isShowEditAdmin">
      <el-form :model="editAdminData">
        <el-form-item label="管理员">
          <el-input v-model="editAdminData.username" disabled autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="昵称">
          <el-input v-model="editAdminData.nickname" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="邮箱">
          <el-input v-model="editAdminData.email" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="角色">
          <el-select v-model="editAdminData.role_id" placeholder="请选择角色">
            <el-option
              v-for="role_v in roleList"
              :key="role_v.id"
              :label="role_v.role_name"
              :value="role_v.id"
            ></el-option>
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowEditAdmin = false">取 消</el-button>
        <el-button type="primary" @click="editAdmin">确 定</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script>
export default {
  data() {
    return {
      adminList: [], // 所有管理员信息

      // 添加管理员弹窗
      isShowAddAdmin: false,

      // 添加管理员信息
      addAdminData: {
        username: "",
        password: "123456",
        email: "",
        role_id: ""
      },
      // 角色列表
      roleList: [],

      // 修改管理员弹窗
      isShowEditAdmin: false,
      editAdminData: {
        username: "",
        email: "",
        role_id: "",
        nickname: ""
      }
    };
  },

  methods: {
    // 获取全部管理员数据
    getAdminList() {
      this.$http({
        url: "admins",
      }).then(backdata => {
        // console.log(backdata);
        var { data } = backdata.data;
        // console.log(data);
        this.adminList = data.data;
      });
    },

    // 获取角色列表
    getRoleList() {
      this.$http({
        url: "roles",
      }).then(backdata => {
        // console.log(backdata);
        var { data } = backdata.data;
        this.roleList = data;
      });
    },

    // 添加管理员弹窗
    showAddAdmin() {
      //   控制弹窗
      this.isShowAddAdmin = true;
    },

    // 添加管理员
    addRoleAdmin() {
      this.$http({
        url: "admins",
        method: "post",
        data: this.addAdminData
      }).then(backdata => {
        // console.log(backdata);
        var { data } = backdata;
        if (data.code == 200 && data.msg == "success") {
          this.$message({ message: "添加成功", type: "success" });
          this.getAdminList();
        }
      });
    },

    //  修改管理员数据弹窗
    editAdminShow(rowdata) {
      //   alert(12);
      this.isShowEditAdmin = true;
      //   console.log(rowdata);
      this.editAdminData = rowdata;
    },

    editAdmin() {
      this.$http({
        url: "admins/" + this.editAdminData.id,
        method: "put",
        data: {
          nickname: this.editAdminData.nickname,
          email: this.editAdminData.email,
          role_id: this.editAdminData.role_id
        },
        headers: { Authorization: localStorage.getItem("token") }
      }).then(backdata => {
        // console.log(backdata.data);
        if (backdata.data.code == 200) {
          this.$message({ message: "修改成功", type: "success" });
          this.isShowEditAdmin = false;
          this.getAdminList();
        }
      });
      console.log(this.editAdminData);
    },

    // 删除管理员
    delAdmin(row) {
      console.log(row);
      this.$http({
        url: "admins/" + row.id,
        method: "delete",
        headers: { Authorization: localStorage.getItem("token") }
      }).then(backdata => {
        if (backdata.data.code == 200) {
          this.$message({ message: "删除成功", type: "success" });
          // this.isShowEditAdmin = false;
          this.getAdminList();
        }
      });
      // alert(34);
    }

    
  },

  mounted() {
    this.getAdminList();
    // 获取角色列表
    this.getRoleList();
  }
};
</script>

<style>
.el-row {
  margin-top: 10px;
}
</style>
