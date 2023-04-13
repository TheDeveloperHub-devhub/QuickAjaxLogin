<?php

namespace DeveloperHub\QuickAjaxLogin\Controller\Ajax;

use Exception;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\EmailNotificationInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\Result\Raw;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use DeveloperHub\QuickAjaxLogin\Model\Customer;

class Register implements ActionInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Customer
     */
    private $customerModel;

    /**
     * @var Session
     */
    private $customerSession;

    /**
     * @var RawFactory
     */
    private $resultRawFactory;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @var EmailNotificationInterface
     */
    private $emailNotification;

    /**
     * @var AccountManagementInterface
     */
    private $customerAccountManagement;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * Register constructor.
     * @param RequestInterface $request
     * @param Customer $customerModel
     * @param Session $customerSession
     * @param RedirectInterface $redirect
     * @param RawFactory $resultRawFactory
     * @param JsonFactory $resultJsonFactory
     * @param EmailNotificationInterface $emailNotification
     * @param AccountManagementInterface $customerAccountManagement
     */
    public function __construct(
        RequestInterface $request,
        Customer $customerModel,
        Session $customerSession,
        RedirectInterface $redirect,
        RawFactory $resultRawFactory,
        JsonFactory $resultJsonFactory,
        EmailNotificationInterface $emailNotification,
        AccountManagementInterface $customerAccountManagement
    ) {
        $this->request = $request;
        $this->redirect = $redirect;
        $this->customerModel = $customerModel;
        $this->customerSession = $customerSession;
        $this->resultRawFactory = $resultRawFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->emailNotification = $emailNotification;
        $this->customerAccountManagement = $customerAccountManagement;
    }

    /**
     * @return ResponseInterface|Json|Raw|ResultInterface
     */
    public function execute()
    {
        $httpBadRequestCode = 400;
        $response = [
            'errors' => false,
            'message' => __('Registration successful.'),
            'redirectUrl' => $this->redirect->getRefererUrl()
        ];

        if ($this->customerModel->userExists($this->request->getPost('email'))) {
            $response = [
                'errors' => true,
                'message' => __('A user already exists with this email id.')
            ];
        } else {
            $resultRaw = $this->resultRawFactory->create();
            try {
                $data = json_decode($this->request->getContent(), true);
                $userData = [
                    'firstname' => $data['firstname'],
                    'lastname' => $data['lastname'],
                    'email' => $data['email'],
                    'password' => $data['password'],
                    'password_confirmation' => $data['password_confirmation']
                ];
            } catch (Exception $e) {
                return $resultRaw->setHttpResponseCode($httpBadRequestCode);
            }
            if (!$userData || $this->request->getMethod() !== 'POST' || !$this->request->isXmlHttpRequest()) {
                return $resultRaw->setHttpResponseCode($httpBadRequestCode);
            }
            try {
                $isUserRegistered = $this->customerModel->createUser($userData);
                if (!$isUserRegistered) {
                    $response = [
                        'errors' => true,
                        'message' => __('Something went wrong.')
                    ];
                } else {
                    $customer = $this->customerAccountManagement->authenticate(
                        $userData['email'],
                        $userData['password']
                    );
                    $this->emailNotification->newAccount($customer);
                    $this->customerSession->setCustomerDataAsLoggedIn($customer);
                    $this->customerSession->regenerateId();
                }
            } catch (LocalizedException $e) {
                $response = [
                    'errors' => true,
                    'message' => $e->getMessage()
                ];
            } catch (Exception $e) {
                $response = [
                    'errors' => true,
                    'message' => __('Something went wrong.')
                ];
            }
        }

        $resultJson = $this->resultJsonFactory->create();
        return $resultJson->setData($response);
    }
}
