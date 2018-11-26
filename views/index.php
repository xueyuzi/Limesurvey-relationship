
<div class='container-fluid'>
    <h3 class='pagetitle'>问卷关系管理</h3>
    <?php foreach($relation as $key => $value) {?>
    <div class="panel panel-primary">
        <div class="panel-heading scenario-heading">
            <div class="row">
                <div class="col-sm-2">
                    <h5><?php echo $key;?></h5>
                </div>
                <div class="col-sm-10"></div>
            </div>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-6">
                    <b>
                    <?php 
                        echo $value["msruvey"]->getLocalizedTitle();
                    ?>
                    </b>
                </div>
                <div class="col-sm-6">
                    <ul style="list-style:none">
                        <?php foreach($value["ssruvey"] as $ssruvey){?>
                            <li><?php echo $ssruvey->getLocalizedTitle(); ?></li>
                        <?php }?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>

    <?php echo TbHtml::form(array($addUrl), 'post', array('id'=>'relationship', 'name'=>'relationship', 'enctype'=>'multipart/form-data')); ?>
        <div class="row">
            <div class="master col-sm-6">
                <label class="control-label">主问卷</label>
                <select name="master" id="" class="form-control" size="7" v-model="form.master">
                    <?php foreach($surveys as $survey) { ?>
                        <option value="<?php echo $survey->sid;?>"><?php echo $survey->getLocalizedTitle();?>(<?php echo $survey->sid;?>)</option>
                    <?php } ?>
                </select>
            </div>
            <div class="slave col-sm-6">
                <label class="control-label">从问卷</label>
                <select name="slave[]" id="" class="form-control" size="7" multiple v-model="form.slave">
                    <?php foreach($surveys as $survey) { ?>
                        <option value="<?php echo $survey->sid;?>"><?php echo $survey->getLocalizedTitle();?>(<?php echo $survey->sid;?>)</option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12" style="text-align:right">
                <button type="submit" class="btn">提交</button>
            </div>
        </div>
    </form>
</div>


