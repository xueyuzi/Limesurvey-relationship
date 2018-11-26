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
        $aRelationships = $this->getRelationship();
        $aSetedSurvey = $this->getSetedSurvey($aRelationships);
        
        $aData["addUrl"] = $sAjaxBaseUrl."add";
        $aData["deleteUrl"] = $sAjaxBaseUrl."delete";
        ///
        $criteria = new CDbCriteria();
        $criteria->addNotInCondition("sid",$aSetedSurvey);
        $aData["surveys"] = Survey::model()->findAll($criteria);
        ///
        $aData["relation"] = $this->get($aRelationships);
        return $this->renderPartial('index',$aData, true);
    }



    public function add(){
        $master = Yii::app()->request->getParam('master');
        $slaves = Yii::app()->request->getParam('slave');
        $this->addRelationship($master,$slaves);
        Yii::app()->getRequest()->redirect($this->getUrl());
    }

    public function get($aRelationships){
        $aResult = [];
        foreach($aRelationships as $aRelationship){
            if($aRelationship["masterId"] == $aRelationship["slaveId"]){
                continue ;
            }
            $aResult[$aRelationship["masterId"]]["msruvey"] = Survey::model()->findByPk($aRelationship["masterId"]);
            $aResult[$aRelationship["masterId"]]["ssruvey"][] = Survey::model()->findByPk($aRelationship["slaveId"]);
        }
        return $aResult;
    }

    private function addRelationship($master,$slaves){
        foreach($slaves as $slave){
            $model = $this->api->newModel($this,"survey_relationship");
            $model->masterId = $master;
            $model->slaveId = $slave;
            $model->save();
        }
    }
    
    public function getRelationship(){
        $sQuery = 'SELECT * FROM {{surveyRelationship_survey_relationship}}';
        $aRelationships = Yii::app()->db->createCommand($sQuery)->query()->readAll();
        return $aRelationships;
    }

    public function getSetedSurvey($aRelationships){
        $result = [];
        foreach($aRelationships as $aRelationship){
            $result[] = $aRelationship["masterId"];
            $result[] = $aRelationship["slaveId"];
        }
        return array_unique($result);
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


    private function createTable(){
        $this->api->createTable($this,"survey_relationship",[
            'id' => "pk",
            'masterId' => "int",
            'slaveId' => "int"
        ]);
    }
}