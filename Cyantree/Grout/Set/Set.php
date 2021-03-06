<?php
namespace Cyantree\Grout\Set;

use Cyantree\Grout\Filter\ArrayFilter;
use Cyantree\Grout\Form\FormStatus;

class Set
{
    const MODE_LIST = 'list';
    const MODE_ADD = 'add';
    const MODE_EDIT = 'edit';
    const MODE_DELETE = 'delete';
    const MODE_SHOW = 'show';

    public $allowList = true;
    public $allowShow = true;
    public $allowEdit = true;
    public $allowAdd = true;
    public $allowDelete = true;

    /** @var Content[] */
    public $contents = array();

    /** @var Content */
    public $firstContent = null;
    /** @var Content */
    public $lastContent = null;

    /** @var ArrayFilter */
    public $config;

    /** @var FormStatus */
    public $status;

    public function __construct()
    {
        $this->config = new ArrayFilter();
        $this->status = new FormStatus();
    }

    public function onList($elements)
    {

    }

    public function getId()
    {
        return null;
    }

    public function setId($id)
    {

    }

    /** @param $content Content */
    public function appendContent($content)
    {
        $content->set = $this;

        if ($this->lastContent) {
            $this->lastContent->nextContent = $content;
            $content->previousContent = $this->lastContent;
        } else {
            $this->firstContent = $content;
        }

        $this->lastContent = $content;
        $this->contents[$content->name] = $content;
    }

    /** @param $content Content */
    public function prependContent($content)
    {
        $content->set = $this;

        if ($this->firstContent) {
            $this->firstContent->previousContent = $content;
            $content->nextContent = $this->firstContent;
        } else {
            $this->lastContent = $content;
        }

        $this->firstContent = $content;
        $this->contents[$content->name] = $content;
    }

    /** @param $content Content */
    public function addContentAfter($content, $previousContentId)
    {
        $content->set = $this;

        $otherContent = $this->contents[$previousContentId];

        if ($otherContent->nextContent) {
            $otherContent->nextContent->previousContent = $content;
            $content->nextContent = $otherContent->nextContent;
        } else {
            $this->lastContent = $content;
        }

        $otherContent->nextContent = $content;
        $content->previousContent = $otherContent;

        $this->contents[$content->name] = $content;
    }

    /** @param $content Content */
    public function addContentBefore($content, $previousContentId)
    {
        $content->set = $this;

        $otherContent = $this->contents[$previousContentId];

        if ($otherContent->previousContent) {
            $otherContent->previousContent->nextContent = $content;
            $content->previousContent = $otherContent->previousContent;
        } else {
            $this->firstContent = $content;
        }

        $otherContent->previousContent = $content;
        $content->nextContent = $otherContent;

        $this->contents[$content->name] = $content;
    }

    /** @return Content */
    public function getContentByName($name)
    {
        if (isset($this->contents[$name])) {
            return $this->contents[$name];
        }
        return null;
    }

    public function createNew()
    {

    }

    public function loadById($id)
    {

    }

    public function getData()
    {
        return null;
    }

    public function populate($rawData)
    {
        $d = new ArrayFilter($rawData);

        foreach($this->contents as $name => $content){
            if($content->editable){
                $content->populate($d, $name);
            }
        }
    }

    public function check()
    {
        $this->status->reset();

        foreach($this->contents as $name => $content){
            if($content->editable){
                $content->check();
            }
        }
    }

    public function save()
    {
        foreach($this->contents as $name => $content){
            if($content->editable){
                $content->save();
            }
        }

        $this->_collectData();

        $this->_doSave();

        foreach ($this->contents as $content) {
            if($content->editable){
                $content->onSaved();
            }
        }
    }

//    public function processData($data, $saveOnSuccess = true)
//    {
//        $data = new ArrayFilter($data);
//        $errors = array();
//
//        foreach ($this->contents as $name => $content) {
//            $content->populate($data, $name);
//
//            $r = $content->check();
//            if ($r !== true) {
//                $e = array();
//                foreach ($r as $k => $v) {
//                    $e[$k] = str_replace('%name%', $content->adminLabel, $v);
//                }
//                $errors[$name] = $e;
//            }
//        }
//
//        if (count($errors)) {
//            return $errors;
//        }
//
//        foreach ($this->contents as $name => $content) {
//            $content->save();
////            $this->_data[$name] = $content->encode();
//        }
//
//        $this->_collectData();
//
//        if ($saveOnSuccess) {
//            $this->save();
//        }
//        return true;
//    }

    protected function _collectData()
    {

    }

    protected function _doSave()
    {

    }

    public function prepareRendering($mode)
    {
        $this->_doPrepareRendering($mode);
        foreach ($this->contents as $content) {
            $content->prepareRendering($mode);
        }
    }

    protected function _doPrepareRendering($mode)
    {

    }

    public function setContentConfig($config, $value, $contentNames = null)
    {
        if ($contentNames) {
            if (is_array($contentNames)) {
                foreach ($contentNames as $contentName) {
                    $this->contents[$contentName]->config->set($config, $value);
                }
            } else {
                $this->contents[$contentNames]->config->set($config, $value);
            }
        } else {
            foreach ($this->contents as $content) {
                $content->config->set($config, $value);
            }
        }
    }

    public function render($mode, $nameOrContent)
    {
        if(is_object($nameOrContent)){
            return $nameOrContent->render($mode, $nameOrContent->name);
        }

        return $this->contents[$nameOrContent]->render($mode, $nameOrContent);
    }

    protected function _onLoaded()
    {

    }

    public function delete()
    {
        foreach ($this->contents as $content) {
            $content->prepareDelete();
        }
        foreach ($this->contents as $content) {
            $content->onDelete();
        }

        $this->_doDelete();

        foreach ($this->contents as $content) {
            $content->onDeleted();
        }

        return true;
    }

    protected function _doDelete()
    {

    }

    public function postError($code, $message = null, $messageReplaces = null)
    {
        $m = new SetMessage();
        $m->code = $code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->status->postError($code, $m);
    }

    public function postInfo($code, $message = null, $messageReplaces = null)
    {
        $m = new SetMessage();
        $m->code = $code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->status->postInfo($code, $m);
    }

    public function postSuccess($code, $message = null, $messageReplaces = null)
    {
        $m = new SetMessage();
        $m->code = $code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->status->postSuccess($code, $m);
    }
}