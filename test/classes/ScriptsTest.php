<?php

/*
 * Copyright 2017 Osclass
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

use PHPUnit\Framework\TestCase;


class ScriptsTest extends TestCase {
    
    
    public function testConstruct()
    {
        $scripts = new Scripts();
        $this->assertNotNull($scripts);
    }
    
    public function testNewInstance()
    {
        $scripts = Scripts::newInstance();
        $this->assertNotNull($scripts);
        $this->assertSame($scripts, Scripts::newInstance());
    }
    
    public function testRegisterScript()
    {
        $scripts = new Scripts();
        $scripts->registerScript("script", "url", "scripts");
        $this->assertArrayHasKey("script", $scripts->registered);
        
        $scripts->register("script_two", "url", ["script", "script_other"]);
        $this->assertArrayHasKey("script_two", $scripts->registered);
        
        $scripts->register("script_other", "url");
        $this->assertArrayHasKey("script_other", $scripts->registered);
    }
    
    public function testUnregisterScript()
    {
        $scripts = new Scripts();
        $scripts->registerScript("script", "url", "scripts");
        $scripts->registerScript("script_two", "url", ["script", "script_other"]);
        $this->assertArrayHasKey("script", $scripts->registered);
        $this->assertArrayHasKey("script_two", $scripts->registered);
        
        $scripts->unregisterScript("script_two");
        $this->assertArrayHasKey("script", $scripts->registered);
        $this->assertArrayNotHasKey("script_two", $scripts->registered);
        
        $scripts->unregisterScript("script");
        $this->assertArrayNotHasKey("script", $scripts->registered);
        $this->assertArrayNotHasKey("script_two", $scripts->registered);
    }
    
    
    public function testEnqueuScript()
    {
        $scripts = new Scripts();
        $this->assertEmpty($scripts->queue);
        $scripts->enqueuScript("script"); 
        $this->assertArrayHasKey("script", $scripts->queue);
        $scripts->enqueuScript("script_two");
        $this->assertArrayHasKey("script_two", $scripts->queue);
        $this->assertNotEmpty($scripts->queue);
        $this->assertEquals(["script" => "script", "script_two" => "script_two"], $scripts->queue);
    }
    
    public function testRemoveScript()
    {
        $scripts = new Scripts();
        $scripts->enqueu("script"); 
        $scripts->enqueu("script_two");
        $this->assertNotEmpty($scripts->queue);
        $this->assertEquals(["script" => "script", "script_two" => "script_two"], $scripts->queue);
        
        $scripts->remove("script_two");
        $this->assertArrayNotHasKey("script_two", $scripts->queue);
        
        $scripts->remove("script");
        $this->assertArrayNotHasKey("script", $scripts->queue);
        $this->assertEmpty($scripts->queue);
    }
    
    public function testGetScripts() {
        $scripts = new Scripts();
        $scripts->registerScript("script_root", "root_url");
        $scripts->registerScript("script", "script_url", "script_root");
        $scripts->registerScript("script_two", "script_two_url", ["script_root", "script"]);
        
        $scripts->enqueu("script_root");
        $scripts->enqueu("script");
        $scripts->enqueu("script_two");
        
        $result = $scripts->getScripts();
        $this->assertNotNull($result);
        $this->assertEquals($result, ["root_url", "script_url", "script_two_url"]);    
    }
    
}
