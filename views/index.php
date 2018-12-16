
<div class='container-fluid'>
    <h3 class='pagetitle'>问卷关系管理</h3>


    <?php echo TbHtml::form(array($exportUrl), 'post', array('id'=>'relationship', 'name'=>'relationship', 'enctype'=>'multipart/form-data')); ?>
        <div class="row">
            <div class="master col-sm-6">
                <label class="control-label">主问卷</label>
                <select name="master" id="" class="form-control" size="7">
                    <?php foreach($surveys as $survey) { ?>
                        <option value="<?php echo $survey->sid;?>"><?php echo $survey->getLocalizedTitle();?>(<?php echo $survey->sid;?>)</option>
                    <?php } ?>
                </select>
            </div>
            <div class="slave col-sm-6">
                <label class="control-label">从问卷</label>
                <select name="slave" id="" class="form-control" size="7">
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


