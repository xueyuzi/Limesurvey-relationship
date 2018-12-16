<?php

use \LimeSurvey\Menu\Menu;
/**
 * Demo for adding new admin page in LimeSurvey 2.56.1
 * @since 2016-11-22
 * @author Olle Härstedt
 */
class surveyRelationship extends \LimeSurvey\PluginManager\PluginBase
{
    static protected $description = 'Support multi-questionnaire Relation Management Function';
    static protected $name = 'surveyRelationship';
    static protected $exportData=[];

    protected $storage = 'DbStorage';
    /**
     * Init plugin and subscribe to event
     * @return void
     */
    public function init()
    {
        $this->subscribe('beforeAdminMenuRender');
    }

      /**
     * Append menus to top admin menu bar
     * @return void
     */
    public function beforeAdminMenuRender()
    {
        // Append menu
        $event = $this->getEvent();
        $event->append('extraMenus', array(
            new Menu(array(
                'label' => '问卷关系管理',
                'href'  => $this->getUrl()
            ))
        ));
    }
     /**
     * @return string html 
     */
    public function actionIndex()
    {
        $sAjaxBaseUrl = "pluginhelper?sa=ajax&plugin=".$this->getName()."&method=";
        $aData["exportUrl"] = $sAjaxBaseUrl."export";
        $aData["surveys"] = Survey::model()->findAll();
        return $this->renderPartial('index',$aData, true);
    }
    public function export(){
        $mSid = app()->request->getPost('master');
        $sSid = app()->request->getPost('slave');
        $mainIndexCode = $this->getMainIndexCode($mSid);
        $salveIndexCode = $this->getMainIndexCode($sSid);
        $this->exportData($mSid,$sSid,$mainIndexCode,$salveIndexCode);

    }

    /**
     * msid 主问卷id
     * ssid 子文卷id
     * micode 主问卷关联问题编号
     * sicode 子文卷关联问题编号
     */

    public function exportData($msid,$ssid,$micode,$sicode){
        
        $mainDatas = $this->exportMainData($msid)
        foreach($mainDatas as $mainData){
            exportSlaveData($mainData);
        }
    }

    public function exportMainData($sid){
        $sQuery = 'SELECT * FROM lime_survey_'.$msid ;
        $mainDatas = Yii::app()->db->createCommand($sQuery)->queryAll();
        return $mainDatas
    }

    public function exportSlaveData($sid,$sicode){

    }

    public function getMainIndexCode($sid){
        $sMainQuestion = "请问医生编号是什么?";
        $sQuery = 'SELECT * FROM {{questions}} where sid=:sid and question=:question';
        $mainIndexCode = Yii::app()->db->createCommand($sQuery)->bindValues(array(':sid'=>$sid, ':question'=>$sMainQuestion))->queryRow();
        if(!$mainIndexCode) return null;
        return $sid."X".$mainIndexCode["gid"]."X".$mainIndexCode["qid"];
    }



    private function getUrl(){
        return $url = $this->api->createUrl(
            'admin/pluginhelper',
            array(
                'sa'     => 'fullpagewrapper',
                'plugin' => $this->getName(),
                'method' => 'actionIndex'  // Method name in our plugin
            )
        );
    }

}