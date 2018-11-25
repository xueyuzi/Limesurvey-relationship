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
        // Create the URL to the plugin action
        $url = $this->api->createUrl(
            'admin/pluginhelper',
            array(
                'sa'     => 'fullpagewrapper',
                'plugin' => $this->getName(),
                'method' => 'actionIndex'  // Method name in our plugin
            )
        );
        
        // Append menu
        $event = $this->getEvent();
        $event->append('extraMenus', array(
            new Menu(array(
                'label' => '问卷关系管理',
                'href'  => $url
            ))
        ));
    }
        /**
     * @return string html 
     */
    public function actionIndex()
    {
        $sPath = __DIR__."/assets";
        $sAjaxBaseUrl = "pluginhelper?sa=ajax&plugin=".$this->getName()."&method=";
        // $data["resourceUrl"] = Yii::app()->getAssetManager()->publish($path);
        $aData["addUrl"] = $sAjaxBaseUrl."add";
        $aData["deleteUrl"] = $sAjaxBaseUrl."delete";
        $aData['surveys'] = Survey::model()->findAll();
        return $this->renderPartial('index',$aData, true);
    }
    private function createTable(){
        // 'pk' => 'int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY',
    // 'bigpk' => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
    // 'string' => 'varchar(255)',
    // 'text' => 'text',
    // 'integer' => 'int(11)',
    // 'bigint' => 'bigint(20)',
    // 'float' => 'float',
    // 'decimal' => 'decimal',
    // 'datetime' => 'datetime',
    // 'timestamp' => 'timestamp',
    // 'time' => 'time',
    // 'date' => 'date',
    // 'binary' => 'blob',
    // 'boolean' => 'tinyint(1)',
    // 'money' => 'decimal(19,4)',
        $this->api->createTable($this,"survey_relationship",[
            'id' => "pk",
            'masterId' => "int",
            'slaveId' => "int"
        ]);
    }
    private function getTableName(){
        return $this->api->getTableName($this,"survey_relationship");
    }

    public function add(){
        $master = Yii::app()->request->getParam('master');
        $slaves = Yii::app()->request->getParam('slave');
        $this->addRelationship($master,$slaves);
        // echo var_dump($this->api->getTable($this,"surveys"));
        // $this->addRelationship();
        // $result["code"] = 200;
        // $result["message"] = "ok";
        // echo json_encode($result);
    }

    private function addRelationship($master,$slaves){
        
        foreach($slaves as $slave){
            $model = $this->api->newModel($this,"survey_relationship");
            $model->masterId = $master;
            $model->slaveId = $slave;
            $model->save();
        }
        

    }
    
    private function getRelationship(){
        
    }
}