<?php
namespace Cyantree\Grout\Set;

use Cyantree\Grout\Filter\ArrayFilter;

class Content
{
    /** @var Set */
    public $set;

    public $name;

    public $guid;
    public $storeInSet = true;

    public $searchable = false;
    public $sortable = false;
    public $editable = true;

    public $required = false;

    /** @var ArrayFilter */
    public $config;

    /** @var Content  */
    public $nextContent = null;

    /** @var Content  */
    public $previousContent = null;

    public function __construct()
    {
        $this->config = new ArrayFilter(array('visible' => true));
    }

    public function decode($data)
    {
    }

    public function encode()
    {
        return '';
    }

    public function onLoaded()
    {
    }

    public function onSaved()
    {
    }

    public function render($mode)
    {
        return '';
    }

    public function prepareRendering($mode)
    {
    }

    /**
     * @param $data ArrayFilter
     */
    public function populate($data)
    {
    }

    public function check()
    {
        return true;
    }

    public function save()
    {
    }

    public function prepareDelete()
    {
    }

    public function onDelete()
    {
    }

    public function onDeleted()
    {
    }

    public function postError($code, $message = null, $messageReplaces = null)
    {
        $this->set->status->postError($this->name);

        if($message){
            if($messageReplaces === null){
                $messageReplaces = array();
            }
            $messageReplaces['%name%'] = $this->config->get('label');

//            $message = str_replace(array_keys($messageReplaces), array_values($messageReplaces), $message);
        }

        $m = new SetMessage();
        $m->content = $this;
        $m->code = $this->name.'.'.$code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->set->status->postError($this->name.'.'.$code, $m);
//        $this->set->status->postError($this->name.'.'.$code, $message);
    }

    public function postInfo($code, $message = null, $messageReplaces = null)
    {
        $this->set->status->postInfo($this->name);

        if($message){
            if($messageReplaces === null){
                $messageReplaces = array();
            }
            $messageReplaces['%name%'] = $this->config->get('label');

//            $message = str_replace(array_keys($messageReplaces), array_values($messageReplaces), $message);
        }

        $m = new SetMessage();
        $m->content = $this;
        $m->code = $this->name.'.'.$code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->set->status->postInfo($this->name.'.'.$code, $m);
//        $this->set->status->postInfo($this->name.'.'.$code, $message);
    }

    public function postSuccess($code, $message = null, $messageReplaces = null)
    {
        $this->set->status->postSuccess($this->name);

        if($message){
            if($messageReplaces === null){
                $messageReplaces = array();
            }
            $messageReplaces['%name%'] = $this->config->get('label');

//            $message = str_replace(array_keys($messageReplaces), array_values($messageReplaces), $message);
        }

        $m = new SetMessage();
        $m->content = $this;
        $m->code = $this->name.'.'.$code;
        $m->message = $message;
        $m->values = $messageReplaces;

        $this->set->status->postSuccess($this->name.'.'.$code, $m);
//        $this->set->status->postSuccess($this->name.'.'.$code, $message);
    }
}

class SetMessage
{
    public $content;
    public $code;
    public $message;
    public $values;

    public function __toString()
    {
        return $this->values ? str_replace(array_keys($this->values), array_values($this->values), $this->message) : $this->message;
    }
}