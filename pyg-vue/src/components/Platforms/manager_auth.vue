<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>系统配置</el-breadcrumb-item>
      <el-breadcrumb-item>权限管理</el-breadcrumb-item>
    </el-breadcrumb>
    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" @click="addAuthShow">添加权限</el-button>
        </div>
      </el-col>
    </el-row>

    <el-table
      :data="authList"
      style="width: 100%;margin-bottom: 20px;"
      row-key="id"
      border
      default-expand-all
      :tree-props="{children: 'son'}"
    >
      <el-table-column prop="id" label="ID" sortable width="180"></el-table-column>
      <el-table-column prop="auth_name" label="权限名称" sortable width="180"></el-table-column>
      <el-table-column prop="auth_c" label="控制器名"></el-table-column>
      <el-table-column prop="auth_a" label="方法名"></el-table-column>
      <el-table-column prop="is_nav" label="是否菜单"></el-table-column>
      <el-table-column label="操作">
        <template slot-scope="scope">
          <i class="el-icon-plus" size="mini" @click="showChildAddAuth(scope.row)"></i> &nbsp;
          <i class="el-icon-edit" size="mini" @click="add"></i>
          <i class="el-icon-delete" size="mini" @click="detele(scope.row)"></i>
        </template>
      </el-table-column>
    </el-table>

    <!-- 添加权限 -->
    <el-dialog title="添加权限" :visible.sync="isAddAuthShow">
      <el-form :model="addAuthData">
        <el-form-item label="权限名称">
          <el-input v-model="addAuthData.auth_name" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="上级权限">
          <el-cascader
            v-model="checkedData"
            :options="authList"
            :props="{ expandTrigger:'hover',children:'son',label:'auth_name',value:'id',checkStrictly: true}"
            @change="ch"
          ></el-cascader>
        </el-form-item>
        <el-form-item label="控制器名">
          <el-input v-model="addAuthData.auth_c" autocomplete="off"></el-input>
        </el-form-item>
        <el-form-item label="操作方法名">
          <el-input v-model="addAuthData.auth_a" autocomplete="off"></el-input>
        </el-form-item>

        <el-form-item label="是否菜单项">
          <el-radio v-model="addAuthData.radio" label="1">是</el-radio>
          <el-radio v-model="addAuthData.radio" label="0">否</el-radio>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="isAddAuthShow = false">取 消</el-button>
        <el-button type="primary" @click="addAuth">确 定</el-button>
      </div>
    </el-dialog>


    
  </div>
</template>

<script>
export default {
  data() {
    return {
      authList: [], //全部权限
      expand_all: false,
      isAddAuthShow: false,
      addAuthData: {
        auth_name: "",
        auth_c: "",
        auth_a: "",
        is_nav:'1',
        pid:''
      },
      checkedData: []
    };
  },

  methods: {
    add(){
      this.isAddAuthShow = true;
    },
    detele(en){
      this.$http({
        url: "auths/" + en.id,
        method:"delete",
      }).then(backdata => {
        if(back.data.code == 200){
          this.$message({message:'删除成功',type:'success'});
          this.getAuthList();
        }
      });
    },
    //  获取全部权限
    getAuthList() {
      this.$http({
        url: "auths?type=tree",
      }).then(backdata => {
        this.authList = backdata.data.data;
        console.log(this.authList);
      });
    },

    // 添加权限弹窗
    addAuthShow() {
      this.isAddAuthShow = true;
    },

    // 获取选中的权限id
    ch(v) {
      // console.log(v);
      this.addAuthData.pid = v[v.length-1];
    },

    // 添加权限
    addAuth(){
      // console.log(this.addAuthData);
      this.$http({
        url:"auths",
        method:'post',
        data:this.addAuthData
      }).then(back=>{
        // console.log(back);
        if(back.data.code == 200){
          this.$message({message:'添加权限成功',type:'success'});
          this.isAddAuthShow = false;
          this.getAuthList();
        }
      })
    },


    // 添加子全选弹窗
    showChildAddAuth(row) {
      console.log(row);
    }

  },

  mounted() {
    this.getAuthList();
  }
};
</script>

<style>
.el-row {
  margin-top: 10px;
}
</style>
