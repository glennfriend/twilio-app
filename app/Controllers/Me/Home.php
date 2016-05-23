<?php
namespace App\Controllers\Me;

use App\Controllers\AdminPageController;
use App\Utility\Output\MenuManager;
use App\Utility\Output\FormMessageManager;
use App\Utility\Output\PageLimit;
use App\Model;
use App\Model\Users;
use App\Model\UserLogs;
use App\Model\UserLogHelper;
use Bridge\Input as Input;

/**
 *
 */
class Home extends AdminPageController
{

    /**
     *
     */
    protected function init()
    {
        MenuManager::setMain('me');
    }

    /**
     *
     */
    protected function about()
    {
        MenuManager::setSub('me-about');

        $this->render('me.home.about');
    }

    /**
     *
     */
    protected function changePassword()
    {
        MenuManager::setSub('me-change-password');

        $view        = 'me.home.changePassword';
        $password    = Input::get('password');
        $password2   = Input::get('password2');
        $oldPassword = Input::get('oldPassword');

        // update password only
        if (Input::isPost()) {

            if (strlen($password) < 6) {
                // 新的密碼必須在 6 個字元以上
                FormMessageManager::addFieldMessage(['password' => 'new password character >= 6']);
                FormMessageManager::addErrorResultMessage('new password error');
                return $this->render($view);
            }
            if ($password !== $password2) {
                FormMessageManager::addFieldMessage(['password' =>'password not match']);
                FormMessageManager::addFieldMessage(['password2'=>'password not match']);
                FormMessageManager::addErrorResultMessage('old password not match');
                return $this->render($view);
            }

            $user = $this->authUser;
            if (!$user->validatePassword($oldPassword)) {
                FormMessageManager::addFieldMessage(array('oldPassword'=>'old password fail'));
                FormMessageManager::addErrorResultMessage('old password error');
                return $this->render($view);
            }

            $user->setPassword($password);
            $user->filter();

            if ($fieldMessages = $user->validate()) {
                FormMessageManager::setFieldMessages( $fieldMessages );
                FormMessageManager::addErrorResultMessage();
            }
            else {
                $users = new Users();
                $users->updateUser($user);
                UserLogHelper::addChangePassword($user->getId());
                FormMessageManager::addSuccessResultMessage('Modify Success');
                return redirect('/me');
            }

        }

        $this->render($view);
    }

    /**
     *
     */
    protected function showLogs()
    {
        MenuManager::setSub('me-logs');

        $page       = (int) Input::get('page');
        $actions    = Input::get('actions');

        $allActions = [
            ['All',             null                            ],
            ['Log in & fail',   'login-success,login-fail'      ],
            ['Change Passwd',   'password-update'               ],
        ];

        $fields = array_filter([
            'userId'    => $this->authUser->getId(),
            'actions'   => $actions,
        ]);
        $options = [
            'page' => $page,
        ];
        $userLogs   = new UserLogs();
        $myUserLogs = $userLogs->findUserLogs($fields, $options);
        $rowCount   = $userLogs->numFindUserLogs($fields, $options);

        $pageLimit = new PageLimit();
        $pageLimit->setBaseUrl('/me-logs');
        $pageLimit->setRowCount($rowCount);
        $pageLimit->setPage($page);
        $pageLimit->setparams([
            'actions' => $actions,
        ]);

        $this->render('me.home.showLogs', [
            'userLogs'  => $myUserLogs,
            'pageLimit' => $pageLimit,
            'actionsKey' => $actions,
            'allActions' => $allActions,
        ]);
    }

}
