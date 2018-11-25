
<div class='container-fluid'>
    <h3 class='pagetitle'>问卷关系管理</h3>
    <div id="app">
        <div class="row">
            <div class="master col-sm-6">
                <label class="control-label">主问卷</label>
                <select name="" id="" class="form-control" size="7" v-model="form.master">
                    <?php foreach($surveys as $survey) { ?>
                    <option value="<?php echo $survey->sid;?>"><?php echo $survey->getLocalizedTitle();?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="slave col-sm-6">
                <label class="control-label">从问卷</label>
                <select name="" id="" class="form-control" size="7" multiple v-model="form.slave">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="text-align:right">
                <button @click="submit" class="btn">提交</button>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<script >
    var app = new Vue({
        el: '#app',
        data: {
            message: 'Hello Vue!',
            form:{
                master:"",
                slave:[]
            }
        },
        methods:{
            submit(){
                console.log(this.form)
                $.get("<?php echo $addUrl;?>",this.form)
            }
        }
    })
</script>