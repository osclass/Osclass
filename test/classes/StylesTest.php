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
class StylesTest extends TestCase {
    
    
    public function testConstruct()
    {
        $styles = new Styles();
        $this->assertNotNull($styles);
    }
    
    public function testNewInstance()
    {
        $styles = Styles::newInstance();
        $this->assertNotNull($styles);
        $this->assertSame($styles, Styles::newInstance());
    }
    
    public function testAddStyle()
    {
        $styles = new Styles();
        $styles->addStyle("style", "url");
        $this->assertArrayHasKey("style", $styles->styles);
        
        $styles->addStyle("style_two", "url");
        $this->assertArrayHasKey("style_two", $styles->styles);
        
        $styles->addStyle("style_other", "url");
        $this->assertArrayHasKey("style_other", $styles->styles);
        $this->assertEquals(["style" => "url", "style_two" => "url", "style_other" => "url"], $styles->styles);
    }
    
    public function testRemoveScript()
    {
        $styles = new Styles();
        $styles->addStyle("style", "url");
        $styles->addStyle("style_two", "url");
        $this->assertNotEmpty($styles->styles);
        $this->assertEquals(["style" => "url", "style_two" => "url"], $styles->styles);
        
        $styles->removeStyle("style_two");
        $this->assertArrayNotHasKey("style_two", $styles->styles);
        
        $styles->removeStyle("style");
        $this->assertArrayNotHasKey("style", $styles->styles);
        $this->assertEmpty($styles->styles);
    }
    
    public function testGetStyles() {
        $styles = new Styles();
        $styles->addStyle("style", "style_url");
        $styles->addStyle("style_two", "style_two_url");
        $styles->addStyle("style_other", "style_other_url");
        
        $result = $styles->getStyles();
        $this->assertNotNull($result);
        $this->assertEquals($result, ["style" => "style_url", "style_two" => "style_two_url", "style_other" => "style_other_url"]);    
    }
}
