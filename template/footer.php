<?php
function tengine_footer() {
    $mobile = new Mobile_Detect();
    ?>
    <div class="clear"></div>
    </div><!-- #main -->
    <div id="footer">
        <ul class="rows">
            <li class="row">
                <p><?php _e('Organizations', 'informea'); ?></p>
                <ul class="orglist">
                    <li>
                        <a target="_blank" href="http://www.un.org/" class="logo un" title="<?php _e('Visit United Nations website', 'informea'); ?>"></a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.unep.org/" class="logo unep" title="<?php _e('Visit United Nations Environment Programme website', 'informea'); ?>"></a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.fao.org/" class="logo fao" title="<?php _e('Visit Visit Food and Agriculture Organization website website', 'informea'); ?>"></a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.unesco.org/" class="logo unesco"></a>
                    </li>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="row">
                <p class="ptreaties"><?php _e('Participating Treaties', 'informea'); ?></p>
                <ul class="orglist">
                    <li>
                        <a target="_blank" href="http://unfccc.int/" class="logo unfccc" title="<?php _e('Visit United Nations Framework Convention on Climate Change website', 'informea'); ?>">
                            <span><?php _e('UNFCCC', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.unccd.int/" class="logo unccd"
                           title="<?php _e('Visit United Nations Convention to Combat Desertification website', 'informea'); ?>">
                            <span><?php _e('UNCCD', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://ozone.unep.org/" class="logo ozone" title="<?php _e('Visit Ozone Secretariat website', 'informea'); ?>">
                            <span><?php _e('OZONE', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.basel.int/" class="logo basel" title="<?php _e('Visit Basel Convention website', 'informea'); ?>">
                            <span><?php _e('Basel', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.pic.int/" class="logo rotterdam" title="<?php _e('Visit Rotterdam Convention website', 'informea'); ?>">
                            <span><?php _e('Rotterdam', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://chm.pops.int/" class="logo stockholm" title="<?php _e('Visit Stockholm Convention website', 'informea'); ?>">
                            <span><?php _e('Stockholm', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.cbd.int/" class="logo cbd" title="<?php _e('Visit Convention on Biological Diversit website', 'informea'); ?>">
                            <span><?php _e('CBD', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.cites.org/" class="logo cites" title="<?php _e('Visit Convention on International Trade in Endangered Species website', 'informea'); ?>">
                            <span><?php _e('CITES', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://whc.unesco.org/" class="logo whc" title="<?php _e('Visit UNESCO World Heritage Convention website', 'informea'); ?>">
                            <span><?php _e('WHC', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.ramsar.org/" class="logo ramsar" title="<?php _e('Visit Ramsar Convention website', 'informea'); ?>">
                            <span><?php _e('Ramsar', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.planttreaty.org/" class="logo itpgrfa" title="<?php _e('Visit International Treaty on Genetic Resources for Food and Agriculture website', 'informea'); ?>">
                            <span><?php _e('ITPGRFA', 'informea'); ?></span>
                        </a>
                    </li>
                    <li>
                        <a target="_blank" href="http://www.cms.int/" class="logo cms" title="<?php _e('Visit Convention on Migratory Species website', 'informea'); ?>">
                            <span><?php _e('CMS', 'informea'); ?></span>
                        </a>
                    </li>
                    <li class="ident">
                        <div class="clear"></div>
                        <br />
                        with {
                        <a target="_blank" href="http://www.acap.aq/">ACAP</a>,
                        <a target="_blank" href="http://www.accobams.org/">ACCOBAMS</a>,
                        <a target="_blank" href="http://www.unep-aewa.org/">AEWA</a>,
                        <a target="_blank" href="http://www.ascobans.org/">ASCOBANS</a>,
                        <a target="_blank" href="http://www.eurobats.org/">EUROBATS</a>,
                        <a target="_blank" href="http://www.igcp.org/">GORILLAS</a> and
                        <a target="_blank" href="http://www.waddensea-secretariat.org/">WADDEN SEA SEALS</a> }
                    </li>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="row">
                <p class="onerow"><?php _e('Regional treaties', 'informea'); ?></p>
                <ul class="orglist">
                    <li>
                        <a target="_blank" href="http://www.unep.org/" class="logo unep" title="Visit United Nations Environment Programme website">
                            <span><?php _e('Barcelona', 'informea'); ?></span>
                        </a>
                    </li>
                </ul>
                <div class="clear"></div>
            </li>
            <li class="row">
                <a href="/wp-admin/">Login</a>
                &middot;
                <a href="<?php echo bloginfo('url'); ?>/disclaimer"><?php _e('Terms and Conditions', 'informea'); ?></a>
                &middot;
                <?php _e('Portions copyright &copy; United Nations, FAO, UNEP and UNESCO', 'informea'); ?>
            </li>
        </ul>
    </div><!-- #footer -->
    </div><!-- #wrapper -->
<?php
}
?>
