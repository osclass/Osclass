<?php
/**
 * Enqueued dependiences class.
 *
 * @since 3.1.1
 */
class Dependencies {

    var $registered   = array();
    var $queue        = array();

    var $resolved     = array();
    var $unresolved   = array();
    var $error        = array();

    public function __construct()
    {
        $registered = array();
        $queue      = array();
    }

    /**
     * Register url to be loaded
     *
     * @param type $id
     * @param type $url
     * @param type $dependencies mixed, it could be an array or a string
     */
    public function register($id, $url, $dependencies) {
        $this->registered[$id] = array(
            'key' => $id
            ,'url' => $url
            ,'dependencies' => $dependencies
        );
    }

    /**
     * Remove url to not be loaded
     *
     * @param type $id
     */
    public function unregister($id)
    {
        unset($this->registered[$id]);
    }

    /**
     * Try to order all script having in mind their dependencies
     */
    public function order()
    {
        $this->resolved     = array();
        $this->unresolved   = array();
        $this->error        = array();

        foreach($this->queue as $queue) {
            if(isset($this->registered[$queue])) {
                $node = $this->registered[$queue];
                if($node['dependencies']==null) {
                    $this->resolved[$node['key']] = $node['key'];
                } else {
                    $this->solveDeps($node);
                }
            } else {
                $this->error[$queue] = $queue;
            }
        }
        if(!empty($this->error)) {
            echo sprintf(__('ERROR: Some dependencies could not be loaded (%s)'), implode(", ", $this->error));
        }
    }

    /**
     * Algorithm to solve the dependencies of the scripts
     *
     * @param type $node
     */
    private function solveDeps($node)
    {
        $error = false;
        if(!isset($this->resolved[$node['key']])) {
            $this->unresolved[$node['key']] = $node['key'];
            if($node['dependencies']!=null) {
                if(is_array($node['dependencies'])) {
                    foreach($node['dependencies'] as $dep) {
                        if(!in_array($dep, $this->resolved)) {
                            if(in_array($dep, $this->unresolved)) {
                                $this->error[$dep] = $dep;
                                $error = true;
                            } else {
                                if(isset($this->registered[$dep])) {
                                    $this->solveDeps($this->registered[$dep]);
                                } else {
                                    $this->error[$dep] = $dep;
                                }
                            }
                        }
                    }
                } else {
                    if(!in_array($node['dependencies'], $this->resolved)) {
                        if(in_array($node['dependencies'], $this->unresolved)) {
                            $this->error[$node['dependencies']] = $node['dependencies'];
                            $error = true;
                        } else {
                            if(isset($this->registered[$node['dependencies']])) {
                                $this->solveDeps($this->registered[$node['dependencies']]);
                            } else {
                                $this->error[$node['dependencies']] = $node['dependencies'];
                            }
                        }
                    }
                }
            }
            if(!$error) {
                $this->resolved[$node['key']] = $node['key'];
                unset($this->unresolved[$node['key']]);
            }
        }
    }
}