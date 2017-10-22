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
class DependenciesTest extends TestCase {
    
    
    public function testConstruct()
    {
        $dependencies = new Dependencies();
        $this->assertNotNull($dependencies);
    }
    
    
    public function testRegister()
    {
        $dependencies = new Dependencies();
        $dependencies->register("dependencie", "url", "dependencies");
        $this->assertArrayHasKey("dependencie", $dependencies->registered);
        $dependencie = $dependencies->registered["dependencie"];
        $this->assertNotNull($dependencie);
        $this->assertEquals("dependencie", $dependencie['key']);
        $this->assertEquals("url", $dependencie['url']);
        $this->assertEquals("dependencies", $dependencie['dependencies']);
        
        $dependencies->register("dependencie_two", "url", ["dependencie", "dependencie_other"]);
        $this->assertArrayHasKey("dependencie_two", $dependencies->registered);
        $dependencie_two = $dependencies->registered["dependencie_two"];
        $this->assertNotNull($dependencie_two);
        $this->assertEquals("dependencie_two", $dependencie_two['key']);
        $this->assertEquals("url", $dependencie_two['url']);
        $this->assertEquals(["dependencie", "dependencie_other"], $dependencie_two['dependencies']);
    }
    
    public function testUnregister()
    {
        $dependencies = new Dependencies();
        $dependencies->register("dependencie", "url", "dependencies");
        $dependencies->register("dependencie_two", "url", ["dependencie", "dependencie_other"]);
        $this->assertArrayHasKey("dependencie", $dependencies->registered);
        $this->assertArrayHasKey("dependencie_two", $dependencies->registered);
        
        $dependencies->unregister("dependencie_two");
        $this->assertArrayHasKey("dependencie", $dependencies->registered);
        $this->assertArrayNotHasKey("dependencie_two", $dependencies->registered);
        
        $dependencies->unregister("dependencie");
        $this->assertArrayNotHasKey("dependencie", $dependencies->registered);
        $this->assertArrayNotHasKey("dependencie_two", $dependencies->registered);
    }
    public function testEnqueu()
    {
        $dependencies = new Dependencies();
        $this->assertEmpty($dependencies->queue);
        $dependencies->enqueu("dependencie"); 
        $this->assertArrayHasKey("dependencie", $dependencies->queue);
        $dependencies->enqueu("dependencie_two");
        $this->assertArrayHasKey("dependencie_two", $dependencies->queue);   
        
        $this->assertNotEmpty($dependencies->queue);
        $this->assertEquals(["dependencie" => "dependencie", "dependencie_two" => "dependencie_two"], $dependencies->queue);
    }
    
    public function testRemove()
    {
        $dependencies = new Dependencies();
        $dependencies->enqueu("dependencie"); 
        $dependencies->enqueu("dependencie_two");
        $this->assertNotEmpty($dependencies->queue);
        $this->assertEquals(["dependencie" => "dependencie", "dependencie_two" => "dependencie_two"], $dependencies->queue);
        
        $dependencies->remove("dependencie_two");
        $this->assertArrayNotHasKey("dependencie_two", $dependencies->queue);
        
        $dependencies->remove("dependencie");
        $this->assertArrayNotHasKey("dependencie", $dependencies->queue);
        $this->assertEmpty($dependencies->queue);
    }
    
    public function testOrder()
    {
        $dependencies = new Dependencies();
        $dependencies->register("dependencie", "url", "dependencies");
        $dependencies->register("dependencie_two", "url", ["dependencie", "dependencies"]);
        $dependencies->register("dependencies", "url");
        $dependencies->enqueu("dependencie");
        $dependencies->enqueu("dependencie_two");
        $dependencies->enqueu("dependencies");
        $this->assertEmpty($dependencies->resolved);
        $dependencies->order();
        $this->assertNotEmpty($dependencies->resolved);
        
        $this->assertEmpty($dependencies->unresolved);
        $this->assertEmpty($dependencies->error);       
    }
}
