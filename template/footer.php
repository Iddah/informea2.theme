<?php
function tengine_footer() {
?>
    <div id="footer" role="contentinfo">
        <div id="organizations" class="footer_section">
            <label class="footer_section_label">Organizations</label>
            <ul>
                <li>
                    <a class="un" target="_blank" href="http://www.un.org/" title="Visit United Nations website">
                        <p>UN</p>
                    </a>
                </li>
                <li>
                    <a class="unep" target="_blank" href="http://www.unep.org/" title="Visit United Nations Environment Programme website">
                        <p>UNEP</p>
                    </a>
                </li>
                <li><a class="fao" target="_blank" href="http://www.fao.org/" title="Visit Food and Agriculture Organization website website">	<p>FAO</p></a>
                </li>
                <li><a class="unesco" target="_blank" href="http://www.unesco.org/" title="Visit United Nations Educational, Scientific and Cultural Organization website">
                        <p>UNESCO</p></a>
                </li>
                <li><a class="unece" target="_blank" href="#" title="Visit UNECE website">
                        <p>UNECE</p></a>
                </li>
            </ul>
        </div>
        <hr />
        <div id="participating_treaties" class="footer_section">
            <label class="footer_section_label">Global treaties</label>
            <ul>
                <li>
                    <a class="unfccc" target="_blank" href="http://unfccc.int/" title="Visit United Nations Framework Convention on Climate Change website">
                        <p>UNFCCC</p>
                    </a>
                </li>
                <li>
                    <a class="unccd" target="_blank" href="http://www.unccd.int/" title="Visit United Nations Convention to Combat Desertification website">
                        <p>UNCCD</p>
                    </a>
                </li>
                <li><a class="ozone" target="_blank" href="http://ozone.unep.org/" title="Visit Ozone Secretariat website">
                        <p>OZONE</p>
                    </a></li>
                <li><a class="basel" target="_blank" href="http://www.basel.int/" title="Visit Basel Convention website">
                        <p>BASEL</p>
                    </a></li>
                <li><a class="rotterdam" target="_blank" href="http://www.pic.int/" title="Visit Rotterdam Convention website">
                        <p>ROTTERDAM</p>
                    </a></li>
                <li><a class="stockholm" target="_blank" href="http://chm.pops.int/" title="Visit Stockholm Convention website">
                        <p>STOCKHOLM</p>
                    </a></li>
                <li><a class="cbd" target="_blank" href="http://www.cbd.int/" title="Visit Convention on Biological Diversit website">
                        <p>CBD</p>
                    </a></li>
                <li><a class="cites" target="_blank" href="http://www.cites.org/" title="Visit Convention on International Trade in Endangered Species website">
                        <p>CITES</p>
                    </a></li>
                <li><a class="whc" target="_blank" href="http://whc.unesco.org/" title="Visit UNESCO World Heritage Convention website">
                        <p>WHC</p>
                    </a></li>
                <li><a class="ramsar" target="_blank" href="http://www.ramsar.org/" title="Visit Ramsar Convention website">
                        <p>Ramsar</p>
                    </a></li>
                <li><a class="itpgrfa" target="_blank" href="http://www.planttreaty.org/" title="Visit International Treaty on Genetic Resources for Food and Agriculture website">
                        <p>ITPGRFA</p>
                    </a></li>
                <li><a class="cms" target="_blank" href="http://www.cms.int/" title="Visit Convention on Migratory Species website">
                        <p>CMS</p>
                    </a></li>
            </ul>
        </div>
        <hr />
        <div id="regional_treaties" class="footer_section">
            <label class="footer_section_label">Regional treaties</label>
            <ul>
                <li>
                    <h3>UNECE related</h3>
                    <ul>
                        <li><a href="#">Aarhus Convention</a></li>
                        <li><a href="#">Espoo Convention</a></li>
                        <li><a href="#">Long-Renage Transboundary Air Pollution</a></li>
                        <li><a href="#">The Kyiv Protocol</a></li>
                        <li><a href="#">Protocol on Water and Health</a></li>
                        <li><a href="#">Water Convention</a></li>
                        <li><a href="#">Kiev Protocol</a></li>
                        <li><a href="#">Industrial Accidents Convention</a></li>
                    </ul>
                </li>

                <li>
                    <h3>UNEP related</h3>
                    <ul>
                        <li><a href="#">Barcelona Dumping Protocol</a></li>
                        <li><a href="#">Specially Protected Areas Protocol</a></li>
                        <li><a href="#">Prevention and Emergency Protocol</a></li>
                        <li><a href="#">Offshore Protocol</a></li>
                        <li><a href="#">Land-Based Sources Protocol</a></li>
                        <li><a href="#">Hazardous Wastes Protocol</a></li>
                        <li><a href="#">Vienna Convention</a></li>
                        <li><a href="#">Montreal Protocol</a></li>
                    </ul>
                </li>
                <li>
                    <h3>CMS related</h3>
                    <ul>
                        <li><a href="#">ACAP</a></li>
                        <li><a href="#">ACCOBAMS</a></li>
                        <li><a href="#">AEWA</a></li>
                        <li><a href="#">ASCOBANS</a></li>
                        <li><a href="#">EUROBATS</a></li>
                        <li><a href="#">International Gorilla Conservation Programme</a></li>
                        <li><a href="#">WADDEN SEA SEALS</a></li>
                    </ul>
                </li>
                <li>
                    <h3>Regional Seas related</h3>
                    <ul>
                        <li><a href="#">Abidjan Convention</a></li>
                        <li><a href="#">Barcelona Convention</a></li>
                        <li><a href="#">Cartagena Convention</a></li>
                    </ul>
                </li>
                <li>
                    <h3>Generic</h3>
                    <ul>
                        <li><a href="#">Apia Convention</a></li>
                        <li><a href="#">Antigua Convention</a></li>
                        <li><a href="#">Bamako Convention</a></li>
                        <li><a href="#">Jeddah Convention</a></li>
                        <li><a href="#">Kuwait Regional Convention</a></li>
                        <li><a href="#">Kyoto Protocol</a></li>
                        <li><a href="#">Lusaka Agreement</a></li>
                        <li><a href="#">Nairobi Convention</a></li>
                        <li><a href="#">Nagoya Protocol</a></li>
                        <li><a href="#">Noumea Convention</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <hr />
        <div id="disclaimer" class="footer_section">
            <?php wp_nav_menu(array('menu' => 'footer', 'theme_location' => 'footer')); ?>
            <p>
                &nbsp; &sdot; portions copyright © United Nations, FAO, UNEP, UNESCO
            </p>
        </div>
    </div>
<?php
}
?>
