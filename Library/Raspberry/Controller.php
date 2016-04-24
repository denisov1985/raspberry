<?php
/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */

namespace Raspberry;
use Raspberry\Http\Request;

class Controller
{
    const CONTENT_HTML = 'html';
    const CONTENT_JSON = 'json';

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * @param string $contentType
     */
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
    }

    private $request;
    private $di;
    private $contentType = self::CONTENT_HTML;

    /**
     * @return mixed
     */
    public function getDi()
    {
        return $this->di;
    }

    /**
     * @param mixed $di
     */
    public function setDi($di)
    {
        $this->di = $di;
    }
    private $view = [];

    /**
     * @return mixed
     */
    public function getView()
    {
        return $this->view;
    }

    /**
     * @param mixed $view
     */
    public function setView($key, $view)
    {
        $this->view[$key] = $view;
    }

    /**
     * @return mixed
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @param mixed $request
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function flush(Model $model)
    {
        $db = $this->getDi()->get('application.database');
        if (strtolower(get_class($model)) != "raspberry\\model") {
            $table = str_ireplace('model', '', strtolower(get_class($model)));
            $db = $this->getDi()->get('application.database');
            $db->createTableIfNotExist($table, $model->getScaffold());
        }



        if (isset($model['id'])) {
            $db->update($table, $model->getData(), ['id' => $model['id']]);
        }   else  {
            $db->insert($table, $model->getData());
        }
    }


}