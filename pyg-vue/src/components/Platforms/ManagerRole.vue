<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
      <el-breadcrumb-item>角色管理</el-breadcrumb-item>
    </el-breadcrumb>
    <!-- 添加角色按钮 -->
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" @click="showAddRole">添加角色</el-button>
        </div>
      </el-col>
    </el-row>

    <!-- 展示角色表格(可展开) -->
    <el-table :data="roleList" style="width: 100%">
      <el-table-column type="expand">
        <template slot-scope="props">
          <el-row v-for="item1 in props.row.role_auths" :key="item1.id">
            <el-col :span="6">
              <el-tag closable>{{item1.auth_name}}</el-tag>>
            </el-col>
            <el-col :span="18">
              <el-row v-for="item2 in item1.son" :key="item2.id">
                <el-col :span="6">
                  <el-tag closable type="danger">{{item2.auth_name}}</el-tag>>
                </el-col>
                <el-col :span="18">
                  <el-tag
                    closable
                    type="info"
                    v-for="item3 in item2.son"
                    :key="item3.id"
                  >{{item3.auth_name}}</el-tag>
                </el-col>
              </el-row>
            </el-col>
          </el-row>
        </template>
      </el-table-column>
      <el-table-column label="ID" prop="id"></el-table-column>
      <el-table-column label="角色名称" prop="role_name"></el-table-column>
      <el-table-column label="角色描述" prop="desc"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <i class="el-icon-edit" size="mini" @click="editRoleShow(scope.row)"></i> &nbsp;
          <i class="el-icon-delete" size="mini" @click="delRole(scope.row)"></i>
        </template>
      </el-table-column>
    </el-table>

    <!-- 添加角色弹窗 -->
    <el-dialog title="添加角色" :visible.sync="isShowAddRole">
      <el-form :model="addRoleData">
        <el-form-item label="名称">
          <el-input v-model="addRoleData.role_name" autocomplete="off" label-width="10px"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="addRoleData.desc" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="权限">
          <el-tree
            :data="authList"
            show-checkbox
            node-key="id"
            :props="defaultAuthProps"
            default-expand-all
            ref="authTree"
          ></el-tree>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowAddRole = false">取 消</el-button>
        <el-button type="primary" @click="addRolePost">确 定</el-button>
      </div>
    </el-dialog>

    <!-- 修改角色信息弹窗 -->
    <el-dialog title="修改角色" :visible.sync="isShowEditRole">
      <el-form :model="editRoleData">
        <el-form-item label="名称">
          <el-input v-model="editRoleData.role_name" autocomplete="off" label-width="10px"></el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="editRoleData.desc" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="权限">
          <el-tree
            :data="authList"
            show-checkbox
            node-key="id"
            :props="defaultAuthProps"
            default-expand-all
            :default-checked-keys="checkedKeys"
            ref="editAuthTree"
          ></el-tree>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowEditRole = false">取 消</el-button>
        <el-button type="primary" @click="editRole">确 定</el-button>
      </div>
    </el-dialog>

  </div>
</template>

<script>
export default {
  data() {
    return {
      roleList: [], //角色列表
      authList: [], //权限列表
      defaultAuthProps: {
        children: "son",
        label: "auth_name"
      },

      isShowAddRole: false, // 添加角色弹窗
      //   添加角色数据
      addRoleData: {
        role_name: "",
        desc: ""
      },
      // 修改角色弹窗
      isShowEditRole: false,
      //   修改角色数据
      editRoleData: {
        role_name: "",
        desc: ""
      },
      //   角色拥有权限的数组
      checkedKeys: [],
      editRoleId: ""
    };
  },

  methods: {
    //   获取全部权限
    getAuthList() {
      this.$http({
        url: "auths?type=tree",
      }).then(backdata => {
        this.authList = backdata.data.data;
        // console.log(this.authList);
      });
    },

    //  获取全部角色
    getRoleList() {
      this.$http({
        url: "roles",
      }).then(backdata => {
        // console.log(backdata);
        this.roleList = backdata.data.data;
      });
    },

    // 添加角色弹窗
    showAddRole() {
      this.isShowAddRole = true;
    },
    // 添加角色提交数据
    addRolePost() {
      var checkedKeys = this.$refs.authTree.getCheckedKeys();
      var halfCheckedKeys = this.$refs.authTree.getHalfCheckedKeys();
      var checkedString = checkedKeys.concat(halfCheckedKeys).join();
      this.addRoleData.auth_ids = checkedString;
      //   console.log(this.addRoleData);
      this.$http({
        url: "roles",
        method: "post",
        data: this.addRoleData
      }).then(backdata => {
        // console.log(backdata.data);
        if (backdata.data.code == 200) {
          this.$message({ message: "添加角色成功", type: "success" });
          this.isShowAddRole = false;
          this.getRoleList();
        } else {
          this.$message.error("添加角色失败");
          this.isShowAddRole = false;
        }
      });
    },

    // 修改角色信息弹窗
    editRoleShow(rowdata) {
      //   console.log(rowdata);
      this.editRoleId = rowdata.id;
      this.checkedKeys = [];
      rowdata.role_auths.forEach(item1 => {
        if (item1.son.length >= 1) {
          item1.son.forEach(item2 => {
            if (item2.son.length >= 1) {
              item2.son.forEach(item3 => {
                if (item3.id) {
                  this.checkedKeys.push(item3.id);
                }
              });
            }
          });
        }
      });
      //   this.checkedKeys = [];
      //   console.log(this.checkedKeys);
      this.editRoleData.role_name = rowdata.role_name;
      this.editRoleData.desc = rowdata.desc;
      this.isShowEditRole = true;
    },

    // 修改角色信息数据
    editRole() {
      //   this.editRoleData.auth_ids = this.checkedKeys.join();
      //   console.log(this.editRoleData);
      var checkedKeys = this.$refs.editAuthTree.getCheckedKeys();
      var halfCheckedKeys = this.$refs.editAuthTree.getHalfCheckedKeys();
      var checkedString = checkedKeys.concat(halfCheckedKeys).join();
      this.editRoleData.auth_ids = checkedString;

      this.$http({
        url: "roles/" + this.editRoleId,
        method: "put",
        data: this.editRoleData,
      }).then(backdata => {
        //   console.log(backdata.data);
        if (backdata.data.code == 200) {
          this.editRoleData.auth_ids = "";
          this.$message({ message: "修改角色成功", type: "success" });
          this.isShowEditRole = false;
          this.getRoleList();
        }
      });
    },

    // 删除角色
    delRole(row){
        this.$http({
            url:'roles/'+row.id,
            method:'delete',
        }).then(backdata=>{
            if(backdata.data.code == 200){
                this.$message({ message: "删除角色成功", type: "success" });
                this.getRoleList();
            }
        })
    }


  },

  mounted() {
    this.getAuthList();
    this.getRoleList();
  }
};
</script>

<style>
.el-tag {
  margin-left: 5px;
}
</style>
