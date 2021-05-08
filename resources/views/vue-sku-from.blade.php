<div class="form-group {!! !$errors->has($label) ?: 'has-error' !!}" >
    <label for="{{$id}}" class="col-sm-2 control-label" >{{$label}}</label>
    <div class="col-sm-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <span style="color: red;">
            @include('admin::form.error')
        </span>
        <div class="sku_warp sku_data">
            <div id="{{$name}}-wrapper">
                <el-row>
                    <el-button :type="initDataObj.types=='single'?'success':'info'" v-on:click="singleFormat">单规格</el-button>
                    <el-button :type="initDataObj.types=='many'?'success':'info'" v-on:click="manyFormat">多规格</el-button>
                </el-row>

                <el-row style="margin-top: 15px;" v-if="initDataObj.types=='many'">
                    <el-table :data="initDataObj.attribute" border style="width: 100%">
                        <el-table-column prop="name" label="规格名" width="100">
                            <template slot-scope="scope" style="display: block;">
                                <el-input placeholder="请输入规格名" v-model="initDataObj.attribute[scope.$index].name" v-on:input="input_attr_name"></el-input>
                            </template>
                        </el-table-column>
                        <el-table-column label="规格值">
                            <template slot-scope="scope" style="display: block;">
                                <el-row :gutter="20">
                                    <el-col type="flex" v-for="(attr_item,attr_index) in scope.row.items" :key="attr_index" :span="6" style="margin-bottom: 10px;">
                                        <el-input placeholder="请输入规格值" v-model="initDataObj.attribute[scope.$index].items[attr_index]" v-on:input="input_attr_item">
                                            <template slot="append" style="display: block;">
                                                <el-button type="danger" v-on:click="remove_attr_item(scope.$index,attr_index)">X</el-button>
                                            </template>
                                        </el-input>
                                    </el-col>
                                    <el-button style="margin: 0 10px;" type="success" v-on:click="add_attr_item(scope.$index,scope.row.items.length)">+</el-button>
                                </el-row>
                            </template>
                        </el-table-column>
                        <el-table-column label="操作" width="100">
                            <template slot-scope="scope" style="display: block;">
                                <el-button type="success" v-on:click="add_attr_name(scope.$index)" v-if="scope.$index==0">添加</el-button>
                                <el-button type="danger" v-on:click="remove_attr_name(scope.$index)" v-else>移除</el-button>
                            </template>
                        </el-table-column>
                    </el-table>

                    <el-table :data="skuData" border style="width: 100%;margin-top: 15px;">
                        <el-table-column v-for="(attr, index) in initDataObj.attribute" :key="`attribute-${index}`" :label="attr.name" :prop="attr.name" width="120" align="center" :resizable="false"/>
                        <el-table-column v-for="(item,structure_index) in initDataObj.structure" :key="'structure_key_'+structure_index" :label="item.label" :prop="item.name" align="center" :resizable="false">
                            <template slot-scope="scope" style="display: block;">
                                <el-input v-if="item.type == 'input'" :placeholder="`请填写${item.label}`" v-model="skuData[scope.$index][item.name]" v-on:input="updateSkuData(0,[])" size="small" />
                                <div class="image-upload-container" v-if="item.type == 'image'">
                                    <el-image v-if="skuData[scope.$index].image" :src="skuData[scope.$index].image" :preview-src-list="[skuData[scope.$index].image]" fit="cover" title="点击预览" />
                                    <el-upload :show-file-list="false" action="{{ route('vue_sku_from.upload') }}" :headers="{{ json_encode(['X-CSRF-TOKEN'=>csrf_token()])}}" name="vue_sku_form_image" :on-success="res => imageUpload(res,scope)" class="images-upload">
                                        <el-button size="small" icon="el-icon-upload2">@{{ skuData[scope.$index].image ? '重新上传' : '上传图片' }}</el-button>
                                    </el-upload>
                                </div>
                            </template>
                        </el-table-column>
                    </el-table>
                </el-row>
                <div class="input-group">
                    <input type="hidden" name="{{$name}}" :value="JSON.stringify(initDataObj)" class="form-control title" placeholder="{{$label}}">
                </div>
            </div>
        </div>
        @include('admin::form.help-block')
    </div>
</div>
<script>
new Vue({
    el: "#{{$name}}-wrapper",
    data(){
        return {
            initDataStr:'{!!old($column, $value) ?? config('vue_sku_form.initDataStr')!!} ',
            initDataObj:{},
            skuData:[]
        };
    },
    mounted(){
        this.initDataObj = JSON.parse(this.initDataStr);
    },
    methods:{
        singleFormat(){
            this.initDataObj.types = 'single'
        },
        manyFormat(){
            this.initDataObj.types = 'many';
            if (this.initDataObj.attribute.length <= 0){
                this.initDataObj.attribute = [{
                    name:"示例名",items:["示例值"]
                }];
                this.updateSkuData();
            }
        },
        //添加规格
        add_attr_name(index){
            this.initDataObj.attribute.push({name:"示例名",items:["示例值"]});
            this.updateSkuData(index+1,this.skuData);
        },
        //删除规格
        remove_attr_name(attribute_index){
            this.initDataObj.attribute.splice(attribute_index, 1);
            this.updateSkuData();
        },
        input_attr_name(attribute_index){
            this.updateSkuData();
        },
        //添加规格值
        add_attr_item(attribute_index){
            this.initDataObj.attribute[attribute_index].items.push("");
            this.updateSkuData();
        },
        input_attr_item(){
            this.updateSkuData();
        },
        //删除规格值
        remove_attr_item(attribute_index,items_index){
            if (this.initDataObj.attribute[attribute_index].items.length <= 0){
                return this.$message.error('最后一项不可删除');
            }
            this.initDataObj.attribute[attribute_index].items.splice(items_index, 1);
            this.updateSkuData();
        },
        // 图片上传
        imageUpload(res, scope) {
            if (res.status === 1){
                this.skuData[scope.$index].image = res.url;
                return this.$message.success('图片上传成功');
            }
            return this.$message.error(res.msg);
        },
        //更新数据
        updateSkuData(index = 0,dataTemp = []){

            if (index == 0){
                for (var i = 0; i < this.initDataObj.attribute[index].items.length; i++) {
                    const obj = {
                        sku:this.initDataObj.attribute[index].items[i],
                        [this.initDataObj.attribute[0].name]: this.initDataObj.attribute[0].items[i]
                    }
                    this.initDataObj.structure.forEach(v => {
                        obj[v.name] = typeof v.defaultValue != 'undefined' ? v.defaultValue : ''
                    })
                    dataTemp.push(obj)
                }
            }else {
                const temp = []
                for (var i = 0; i < dataTemp.length; i++) {
                    for (var j = 0; j < this.initDataObj.attribute[index].items.length; j++) {
                        temp.push(JSON.parse(JSON.stringify(dataTemp[i])));
                        temp[temp.length - 1][this.initDataObj.attribute[index].name] = this.initDataObj.attribute[index].items[j];
                        temp[temp.length - 1]['sku'] = [temp[temp.length - 1]['sku'], this.initDataObj.attribute[index].items[j]].join(this.separator);
                    }
                }
                dataTemp = temp
            }

            if (index !== this.initDataObj.attribute.length - 1) {
                this.updateSkuData(index + 1, dataTemp)
            } else {
                for (var i = 0; i < this.skuData.length; i++) {
                    for (var j = 0; j < dataTemp.length; j++) {
                        if (this.skuData[i].sku === dataTemp[j].sku) {
                            dataTemp[j] = this.skuData[i]
                        }
                    }
                }
                this.skuData = dataTemp;
                this.initDataObj.sku = this.skuData;
            }
        }
    }
});
</script>