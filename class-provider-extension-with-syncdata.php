<?php

abstract class NextendSocialLoginPROProviderExtensionWithSyncData extends NextendSocialLoginPROProviderExtension {

    /** @var NextendSocialProvider */
    protected $provider;

    protected $fields;

    public function __construct($provider) {
        parent::__construct($provider);

        add_action('nsl_' . $this->provider->getId() . '_enabled', array(
            $this,
            'providerEnabled'
        ));
    }

    protected function removeSyncActions() {

        /** Prevent multiple sync in the same request */
        remove_action('nsl_' . $this->provider->getId() . '_register_new_user', array(
            $this,
            'register_new_user'
        ));
        remove_action('nsl_' . $this->provider->getId() . '_login', array(
            $this,
            'login'
        ));
        remove_action('nsl_' . $this->provider->getId() . '_link_user', array(
            $this,
            'link'
        ));
    }


    public function providerEnabled() {
        $this->fields = $this->provider->getSyncFields();

        add_filter('nsl_' . $this->provider->getId() . '_scopes', array(
            $this,
            'scopes'
        ));

        add_filter('nsl_' . $this->provider->getId() . '_sync_node_fields', array(
            $this,
            'node_fields'
        ), 10, 2);

        add_action('nsl_' . $this->provider->getId() . '_register_new_user', array(
            $this,
            'register_new_user'
        ));

        if ($this->provider->settings->get('sync_fields/login')) {
            add_action('nsl_' . $this->provider->getId() . '_login', array(
                $this,
                'login'
            ));
        }

        if ($this->provider->settings->get('sync_fields/link')) {
            add_action('nsl_' . $this->provider->getId() . '_link_user', array(
                $this,
                'link'
            ));
        }
    }

    public function scopes($scopes) {

        $settings = $this->provider->settings;
        foreach ($this->fields as $field_name => $fieldData) {
            if (isset($fieldData['scope']) && $settings->get('sync_fields/fields/' . $field_name . '/enabled') && !in_array($fieldData['scope'], $scopes)) {
                $scopes[] = $fieldData['scope'];
            }
        }

        return $scopes;
    }

    /**
     * @param array        $fields
     * @param array|string $node
     *
     * @return mixed
     */
    public function node_fields($fields, $node = 'me') {
        $settings = $this->provider->settings;
        foreach ($this->fields as $field_name => $fieldData) {

            if (is_array($fieldData['node'])) {
                foreach ($fieldData['node'] as $fieldData_node_index => $fieldData_node) {
                    if ($fieldData_node === $node) {
                        if ($settings->get('sync_fields/fields/' . $field_name . '/enabled')) {
                            $fields[] = $field_name;
                        }
                    }
                }
            } else {
                if ($fieldData['node'] === $node) {
                    if ($settings->get('sync_fields/fields/' . $field_name . '/enabled')) {
                        $fields[] = $field_name;
                    }
                }
            }
        }

        return $fields;
    }

    /**
     * @param $user_id
     *
     * @throws Exception
     */
    public function register_new_user($user_id) {

        $this->synchronizeFields($user_id);

        $this->removeSyncActions();
    }

    /**
     * @param $user_id
     *
     * @throws Exception
     */
    public function login($user_id) {

        $this->synchronizeFields($user_id);

        $this->removeSyncActions();
    }

    /**
     * @param $user_id
     *
     * @throws Exception
     */
    public function link($user_id) {

        $this->synchronizeFields($user_id);

        $this->removeSyncActions();
    }

    /**
     * @param $user_id
     *
     * @throws Exception
     */
    private function synchronizeFields($user_id) {

        $nodes = array();

        foreach ($this->fields as $field_name => $fieldData) {

            if (is_array($fieldData['node'])) {
                foreach ($fieldData['node'] as $fieldData_node_index => $fieldData_node) {
                    if (!isset($nodes[$fieldData_node])) {
                        $nodes[$fieldData_node] = array();
                    }
                    $nodes[$fieldData_node][$field_name] = $fieldData;
                }
            } else {
                if (!isset($nodes[$fieldData['node']])) {
                    $nodes[$fieldData['node']] = array();
                }
                $nodes[$fieldData['node']][$field_name] = $fieldData;
            }
        }

        foreach ($nodes as $node_name => $fields) {
            $this->synchronizeNodeFields($user_id, $fields, $this->getRemoteData($node_name));
        }
    }

    protected function synchronizeNodeFields($user_id, $fields, $data) {

        $settings = $this->provider->settings;

        foreach ($fields as $field_name => $fieldData) {
            if ($settings->get('sync_fields/fields/' . $field_name . '/enabled')) {
                $meta_key = $settings->get('sync_fields/fields/' . $field_name . '/meta_key');
                if (!empty($meta_key) && isset($data[$field_name])) {
                    $meta_value = apply_filters('nsl_' . $this->provider->getId() . '_sync_field_' . $field_name, $data[$field_name], $data[$field_name]);
                    if ($meta_value === false) {
                        delete_user_meta($user_id, $meta_key);
                    } else {
                        update_user_meta($user_id, $meta_key, $meta_value);
                    }
                }
            }
        }
    }


    /**
     * @param string $node
     *
     * @return array
     * @throws Exception
     */
    protected abstract function getRemoteData($node = 'me');
}