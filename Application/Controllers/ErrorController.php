<?php

/**
 *
 *
 * @package
 * @subpackage
 * @author     Dmytro Denysov dmytro.denysov@racingpost.com
 * @copyright  2016 Racing Post
 */
class ErrorController extends BaseController
{

    public function indexAction()
    {
        $this->setTitle('Error occurred');
        $error = $this->getRequest()->getError();

        //\Raspberry\Debug::prd($error['trace']);

        return [
            'error' => $error
        ];
    }

}