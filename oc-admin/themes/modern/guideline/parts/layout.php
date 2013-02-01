<div id="#guideline-grid">
    <!-- Explain -->
    <div class="grid-system">
        <div class="grid-row grid-100 no-bottom-margin">
            <div class="row-wrapper">
                <h2 class="render-title">Layout</h2>
            </div>
        </div>
        
        <div class="grid-row grid-30">
            <div class="row-wrapper">
                <p>The basic template includes the admin toolbar, the menu, and the basic layout to render your page.</p>
            </div>
        </div>
        <div class="grid-row grid-30">
            <div class="row-wrapper">
                <p>
                <div class="well">Admin toolbar</div>
                <div style="width:30%; float:left; margin-top:-1px;">
                    <div class="well" style="height:200px">Menu</div>
                </div>
                <div style="width:70%; float:left; margin-top:-1px;">
                    <div class="well" style="border-left:0;height:200px">Render page</div>
                </div>
                </p>
            </div>
        </div>
        <div class="grid-row grid-40">
            <div class="row-wrapper">
                <pre style="color:#3b3b3b">&lt;?php
osc_add_hook(<span style="color:#666">'admin_page_header'</span>,<span style="color:#666">'customPageHeader'</span>);
    <span style="color:#ff5600">function</span> <span style="color:#21439c">customPageHeader</span>(){
        <span style="color:#45ae34;font-weight:700">echo</span> <span style="color:#666">'&lt;h1>'</span> <span style="color:#069;font-weight:700">.</span> __(<span style="color:#666">'Custom Layout'</span>) <span style="color:#069;font-weight:700">.</span> <span style="color:#666">'&lt;/h1>'</span>;
    }
?>
&lt;?php osc_current_admin_theme_path( <span style="color:#666">'parts/header.php'</span> ); ?>
    <span style="color:#af82d4">&lt;!-- You content here --></span>
    <span style="color:#af82d4">&lt;!-- #You content here --></span>
&lt;?php osc_current_admin_theme_path( <span style="color:#666">'parts/footer.php'</span> ); ?>
</pre>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!-- Explain end -->
</div



