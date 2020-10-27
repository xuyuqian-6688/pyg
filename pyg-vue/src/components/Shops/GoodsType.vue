<template>
  <div>
    <el-breadcrumb separator-class="el-icon-arrow-right">
      <el-breadcrumb-item :to="{ path: '/indexs' }">首页</el-breadcrumb-item>
      <el-breadcrumb-item>商城</el-breadcrumb-item>
      <el-breadcrumb-item>商品模型</el-breadcrumb-item>
    </el-breadcrumb>

    <el-row>
      <el-col :span="24">
        <div class="grid-content bg-purple-dark">
          <el-button type="primary" @click="addModels">添加模型</el-button>
        </div>
      </el-col>
    </el-row>

    <!-- 添加模型弹窗 -->
    <el-dialog title="添加模型" :visible.sync="isShowaddModels" width="70%">
      <el-form :model="addModelData">
        <el-form-item label="模型名称">
          <el-input v-model="addModelData.type_name" autocomplete="off"></el-input>
        </el-form-item>
      </el-form>

      <el-button @click="addSpec">添加规格</el-button>
      <el-table :data="addModelData.spec">
        <el-table-column label="规格名称">
          <template slot-scope="scope">
            <el-input v-model="scope.row.name" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="排序">
          <template slot-scope="scope">
            <el-input v-model="scope.row.sort" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="规格值">
          <template slot-scope="scope">
            <el-input v-model="scope.row.value" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button @click="delSpec(scope)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>
      <hr />
      <el-button @click="addAttr">添加属性</el-button>
      <el-table :data="addModelData.attr">
        <el-table-column label="属性名称">
          <template slot-scope="scope">
            <el-input v-model="scope.row.name" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column prop="name" label="排序">
          <template slot-scope="scope">
            <el-input v-model="scope.row.sort" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column prop="address" label="属性值">
          <template slot-scope="scope">
            <el-input v-model="scope.row.value" placeholder="请输入内容"></el-input>
          </template>
        </el-table-column>
        <el-table-column label="操作">
          <template slot-scope="scope">
            <el-button @click="delAttr(scope)">删除</el-button>
          </template>
        </el-table-column>
      </el-table>

      <div slot="footer" class="dialog-footer">
        <el-button @click="isShowaddModels = false">取 消</el-button>
        <el-button type="primary" @click="addModePost">确 定</el-button>
      </div>
    </el-dialog>

    <el-table :data="goodsTypeListData" style="width: 100%">
      <el-table-column prop="id" label="ID"></el-table-column>
      <el-table-column prop="type_name" label="模型名称"></el-table-column>

      <el-table-column label="操作">
        <template slot-scope="scope">
          <i class="el-icon-edit" size="mini"></i>
          <i class="el-icon-delete" size="mini" @click="delModel(scope.row)"></i>
        </template>
      </el-table-column>
    </el-table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      goodsTypeListData: [],
      isShowaddModels: false,
      addModelData: {
        type_name: "",
        spec: [{ name: "", sort: "", value: "" }],
        attr: [{ name: "", sort: "", value: "" }]
      }
      
    };
  },

  methods: {
    // 获取模型列表
    getGoodsTypeList() {
      this.$http({
        url: "types"
      }).then(back => {
        this.goodsTypeListData = back.data.data;
      });
    },

    // 添加模型弹窗
    addModels() {
      this.isShowaddModels = true;
    },

    // 添加规格表单
    addSpec() {
      this.addModelData.spec.push({ name: "", sort: "", value: "" });
    },
    // 添加属性表单
    addAttr() {
      this.addModelData.attr.push({ name: "", sort: "", value: "" });
    },
    // 删除规格表单
    delSpec(scope) {
      this.addModelData.spec.splice(scope.index, 1);
    },
    // 删除属性表单
    delAttr(scope) {
      this.addModelData.attr.splice(scope.index, 1);
    },

    // 添加模型 数据提交
    addModePost() {
      // console.log(this.addModelData);
      // 后台接收的参数类型格式有毒
      var newFormData = new FormData();
      newFormData.append("type_name", this.addModelData.type_name);

      var spec = this.addModelData.spec;
      spec.forEach((v, k, itme) => {
        newFormData.append("spec[" + k + "]" + "[name]", v.name);
        newFormData.append("spec[" + k + "]" + "[sort]", v.sort);
        newFormData.append("spec[" + k + "]" + "[value][]", v.value);
      });

      var attr = this.addModelData.attr;
      attr.forEach((v, k, itme) => {
        newFormData.append("attr[" + k + "]" + "[name]", v.name);
        newFormData.append("attr[" + k + "]" + "[sort]", v.sort);
        newFormData.append("attr[" + k + "]" + "[value][]", v.value);
      });

      // console.log(newFormData);

      this.$http({
        url: "/types",
        method: "post",
        headers: { "Content-type": " multipart/form-data" },
        data: newFormData
      }).then(back => {
        // console.log(back);
        if (back.data.code == 200 && back.data.msg == "success") {
          this.isShowaddModels = false;
          this.$message({ message: "添加模型成功", type: "success" });
          // 清空数据
          this.addModelData = {
            type_name: "",
            spec: [{ name: "", sort: "", value: "" }],
            attr: [{ name: "", sort: "", value: "" }]
          };
          this.getGoodsTypeList();
        }
      });
    },

    // 删除模型
    delModel(row) {
      this.$http({
        url: "types/" + row.id,
        method: "delete"
      }).then(back => {
        this.getGoodsTypeList();
      });
    }
  },
  mounted() {
    this.getGoodsTypeList();
  }
};
</script>

<style>
</style>
