<?php

/*
 * Copyright 2014 Osclass
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

function osc_cache_add($key, $data, $expire = 0) {
    return Object_Cache_Factory::getCache()->add($key, $data, $expire);
}

function osc_cache_close() {
    return Object_Cache_Factory::getCache()->close();
}

function osc_cache_delete($key) {
    return Object_Cache_Factory::getCache()->delete($key);
}

function osc_cache_flush() {
    return Object_Cache_Factory::getCache()->flush();
}

function osc_cache_init() {
    Object_Cache_Factory::getCache();
}

function osc_cache_replace($key, $data, $expire = 0) {
    return Object_Cache_Factory::getCache()->replace($key, $data, $expire);
}

function osc_cache_get($key, $force = false) {
    return Object_Cache_Factory::getCache()->get($key, $force);
}

function osc_cache_set($key, $data, $expire = 0) {
    return Object_Cache_Factory::getCache()->set($key, $data, $expire);
}
?>