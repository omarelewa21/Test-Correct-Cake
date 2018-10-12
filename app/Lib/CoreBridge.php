<?php

App::uses('CoreConnector', 'Lib');

class CoreBridge {

    /**
     * Singleton pattern.
     *
     * @var CoreBridge
     */
    private static $instance;

    const CACHE_SHORT = 'settings_short';
    const CACHE_LONG = 'settings_long';

    public function __construct()
    {

    }

    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function generateMenu($userID, $privileges)
    {
        $key = "menu_{$userID}";
        $result = Cache::read($key, self::CACHE_SHORT);
        if ($result !== false) {
            return $result;
        }

        Configure::load('menu');
        $items = Configure::read('MenuItems');

        $result = array();
        foreach ($items as $key => $item) {
            if (empty($item['privileges'])) {
                $result[$key] = $item;
                continue;
            }

            foreach ($item['privileges'] as $privilege) {
                if (in_array($privilege, $privileges)) {
                    $result[$key] = $item;
                    break;
                }
            }
        }

        if (array_key_exists('notifications', $result)) {
            $result['notifications']['count'] = $this->getNotificationCount();
        }

        Cache::write($key, $result, self::CACHE_SHORT);
        return $result;
    }

    public function getNotificationCount()
    {
        $response = CoreConnector::instance()->getRequest('properties/notification_count', array());
        return $response ? $response['count'] : 0;
    }
}