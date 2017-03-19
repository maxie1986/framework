<?php
/**
 * Created by PhpStorm.
 * User: maxi
 * Date: 3/17/17
 * Time: 8:41 PM
 */

namespace Framework\Base;


class View
{

    /**
     * @var string
     */
    protected $viewName;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var string
     */
    protected $layout;

    protected $content;

    /**
     * View constructor.
     * @param string $viewName
     * @param array $params
     */
    public function __construct($viewName = '', $params = [])
    {
        $this->viewName = $viewName;
        $this->params = $params;
        $this->layout = Config::get('layout');
    }

    public function render($isPartial = false)
    {
        extract($this->getParams(), EXTR_SKIP);
        ob_start();
        if (file_exists(VIEW_PATH . $this->viewName . '.php')) {
            $this->content = $this->renderFile(sprintf('%s.php', VIEW_PATH . $this->viewName));
        }

        if(!$isPartial) {
            $this->renderLayout();
        } else {
            return $this->content;
        }
    }

    protected function renderFile($file)
    {
        ob_start();
        extract($this->getParams(), EXTR_SKIP);
        require($file);
        return ob_get_clean();
    }

    /**
     * @return string
     */
    public function getViewName()
    {
        return $this->viewName;
    }

    /**
     * @param string $viewName
     */
    public function setViewName($viewName)
    {
        $this->viewName = $viewName;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;
    }

    public function getContents()
    {
        //if(file_exists(VIEW_PATH . DS . ))
    }


    protected function renderLayout()
    {

        $content = $this->content;
        include sprintf(VIEW_PATH . DS . 'layouts' . DS . '%s.php', Config::get('layout'));

        //return new Response(ob_get_clean());
    }

    public function renderPartial()
    {

    }


}