<?php

/**
 * @property array users
 * @property WpAdvQuiz_Model_Quiz quiz
 * @property array $supportedExportFormats
 */
class WpAdvQuiz_View_StatisticsNew extends WpAdvQuiz_View_View
{

    public function show()
    {
        ?>

        <style>
            .wpAdvQuiz_blueBox {
                padding: 20px;
                background-color: rgb(223, 238, 255);
                border: 1px dotted;
                margin-top: 10px;
            }

            .categoryTr th {
                background-color: #F1F1F1;
            }

            .wpAdvQuiz_modal_backdrop {
                background: #000;
                opacity: 0.7;
                top: 0;
                bottom: 0;
                right: 0;
                left: 0;
                position: fixed;
                z-index: 159900;
            }

            .wpAdvQuiz_modal_window {
                position: fixed;
                background: #FFF;
                top: 40px;
                bottom: 40px;
                left: 40px;
                right: 40px;
                z-index: 160000;
            }

            .wpAdvQuiz_actions {
                display: none;
                padding: 2px 0 0;
            }

            .mobile .wpAdvQuiz_actions {
                display: block;
            }

            tr:hover .wpAdvQuiz_actions {
                display: block;
            }
            .wpAdvQuiz_exportList {
                padding: 20px;
                background-color: rgb(223, 238, 255);
                border: 1px dotted;
                margin-top: 10px;
            }
            .wpAdvQuiz_exportList ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }
            .wpAdvQuiz_exportList li {
                float: left;
                padding: 3px;
                border: 1px solid #B3B3B3;
                margin-right: 5px;
                background-color: #F3F3F3;
            }
        </style>

        <?php
        add_thickbox();
        $this->showExportListBox();
        ?>

        <div class="wrap wpAdvQuiz_statisticsNew">
            <input type="hidden" id="quizId" value="<?php echo esc_attr($this->quiz->getId()); ?>" name="quizId">

            <h2><?php printf(__('Quiz: %s - Statistics', 'wp-adv-quiz'), $this->quiz->getName()); ?></h2>

            <p><a class="button-secondary" href="admin.php?page=wpAdvQuiz"><?php _e('back to overview',
                        'wp-adv-quiz'); ?></a></p>

            <?php if (!$this->quiz->isStatisticsOn()) { ?>
                <p style="padding: 30px; background: #F7E4E4; border: 1px dotted; width: 300px;">
                    <span style="font-weight: bold; padding-right: 10px;"><?php _e('Stats not enabled',
                            'wp-adv-quiz'); ?></span>
                    <a class="button-secondary"
                       href="admin.php?page=wpAdvQuiz&action=addEdit&quizId=<?php echo esc_attr($this->quiz->getId()); ?>"><?php _e('Activate statistics',
                            'wp-adv-quiz'); ?></a>
                </p>
                <?php return;
            } ?>

            <div style="padding: 10px 0px;" class="wpAdvQuiz_tab_wrapper">
                <a class="button-primary" href="#" data-tab="#wpAdvQuiz_tabHistory"><?php _e('History',
                        'wp-adv-quiz'); ?></a>
                <a class="button-secondary" href="#" data-tab="#wpAdvQuiz_tabOverview"><?php _e('Overview',
                        'wp-adv-quiz'); ?></a>
            </div>

            <div id="wpAdvQuiz_loadData" class="wpAdvQuiz_blueBox" style="background-color: #F8F5A8; display: none;">
                <img alt="load"
                     src="data:image/gif;base64,R0lGODlhEAAQAPYAAP///wAAANTU1JSUlGBgYEBAQERERG5ubqKiotzc3KSkpCQkJCgoKDAwMDY2Nj4+Pmpqarq6uhwcHHJycuzs7O7u7sLCwoqKilBQUF5eXr6+vtDQ0Do6OhYWFoyMjKqqqlxcXHx8fOLi4oaGhg4ODmhoaJycnGZmZra2tkZGRgoKCrCwsJaWlhgYGAYGBujo6PT09Hh4eISEhPb29oKCgqioqPr6+vz8/MDAwMrKyvj4+NbW1q6urvDw8NLS0uTk5N7e3s7OzsbGxry8vODg4NjY2PLy8tra2np6erS0tLKyskxMTFJSUlpaWmJiYkJCQjw8PMTExHZ2djIyMurq6ioqKo6OjlhYWCwsLB4eHqCgoE5OThISEoiIiGRkZDQ0NMjIyMzMzObm5ri4uH5+fpKSkp6enlZWVpCQkEpKSkhISCIiIqamphAQEAwMDKysrAQEBJqamiYmJhQUFDg4OHR0dC4uLggICHBwcCAgIFRUVGxsbICAgAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggYWpheGxvYWQuaW5mbwAh+QQJCgAAACwAAAAAEAAQAAAHjYAAgoOEhYUbIykthoUIHCQqLoI2OjeFCgsdJSsvgjcwPTaDAgYSHoY2FBSWAAMLE4wAPT89ggQMEbEzQD+CBQ0UsQA7RYIGDhWxN0E+ggcPFrEUQjuCCAYXsT5DRIIJEBgfhjsrFkaDERkgJhswMwk4CDzdhBohJwcxNB4sPAmMIlCwkOGhRo5gwhIGAgAh+QQJCgAAACwAAAAAEAAQAAAHjIAAgoOEhYU7A1dYDFtdG4YAPBhVC1ktXCRfJoVKT1NIERRUSl4qXIRHBFCbhTKFCgYjkII3g0hLUbMAOjaCBEw9ukZGgidNxLMUFYIXTkGzOmLLAEkQCLNUQMEAPxdSGoYvAkS9gjkyNEkJOjovRWAb04NBJlYsWh9KQ2FUkFQ5SWqsEJIAhq6DAAIBACH5BAkKAAAALAAAAAAQABAAAAeJgACCg4SFhQkKE2kGXiwChgBDB0sGDw4NDGpshTheZ2hRFRVDUmsMCIMiZE48hmgtUBuCYxBmkAAQbV2CLBM+t0puaoIySDC3VC4tgh40M7eFNRdH0IRgZUO3NjqDFB9mv4U6Pc+DRzUfQVQ3NzAULxU2hUBDKENCQTtAL9yGRgkbcvggEq9atUAAIfkECQoAAAAsAAAAABAAEAAAB4+AAIKDhIWFPygeEE4hbEeGADkXBycZZ1tqTkqFQSNIbBtGPUJdD088g1QmMjiGZl9MO4I5ViiQAEgMA4JKLAm3EWtXgmxmOrcUElWCb2zHkFQdcoIWPGK3Sm1LgkcoPrdOKiOCRmA4IpBwDUGDL2A5IjCCN/QAcYUURQIJIlQ9MzZu6aAgRgwFGAFvKRwUCAAh+QQJCgAAACwAAAAAEAAQAAAHjIAAgoOEhYUUYW9lHiYRP4YACStxZRc0SBMyFoVEPAoWQDMzAgolEBqDRjg8O4ZKIBNAgkBjG5AAZVtsgj44VLdCanWCYUI3txUPS7xBx5AVDgazAjC3Q3ZeghUJv5B1cgOCNmI/1YUeWSkCgzNUFDODKydzCwqFNkYwOoIubnQIt244MzDC1q2DggIBACH5BAkKAAAALAAAAAAQABAAAAeJgACCg4SFhTBAOSgrEUEUhgBUQThjSh8IcQo+hRUbYEdUNjoiGlZWQYM2QD4vhkI0ZWKCPQmtkG9SEYJURDOQAD4HaLuyv0ZeB4IVj8ZNJ4IwRje/QkxkgjYz05BdamyDN9uFJg9OR4YEK1RUYzFTT0qGdnduXC1Zchg8kEEjaQsMzpTZ8avgoEAAIfkECQoAAAAsAAAAABAAEAAAB4iAAIKDhIWFNz0/Oz47IjCGADpURAkCQUI4USKFNhUvFTMANxU7KElAhDA9OoZHH0oVgjczrJBRZkGyNpCCRCw8vIUzHmXBhDM0HoIGLsCQAjEmgjIqXrxaBxGCGw5cF4Y8TnybglprLXhjFBUWVnpeOIUIT3lydg4PantDz2UZDwYOIEhgzFggACH5BAkKAAAALAAAAAAQABAAAAeLgACCg4SFhjc6RhUVRjaGgzYzRhRiREQ9hSaGOhRFOxSDQQ0uj1RBPjOCIypOjwAJFkSCSyQrrhRDOYILXFSuNkpjggwtvo86H7YAZ1korkRaEYJlC3WuESxBggJLWHGGFhcIxgBvUHQyUT1GQWwhFxuFKyBPakxNXgceYY9HCDEZTlxA8cOVwUGBAAA7AAAAAAAAAAAA">
                <?php _e('Loading', 'wp-adv-quiz'); ?>
            </div>

            <div id="wpAdvQuiz_content" style="display: block;">
                <?php $this->showHistory(); ?>
                <?php $this->showTabOverview(); ?>
            </div>

            <?php $this->showModalWindow(); ?>

        </div>

        <?php
    }

    private function showHistory()
    {
        ?>
        <div id="wpAdvQuiz_tabHistory" class="wpAdvQuiz_tabContent" style="display: block;">

            <div id="poststuff">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Filter', 'wp-adv-quiz'); ?></h3>

                    <div class="inside">
                        <ul>
                            <li>
                                <label>
                                    <?php _e('Which users should be displayed:', 'wp-adv-quiz'); ?>
                                    <select id="wpAdvQuiz_historyUser">
                                        <optgroup label="<?php _e('special filter', 'wp-adv-quiz'); ?>">
                                            <option value="-1" selected="selected"><?php _e('all users',
                                                    'wp-adv-quiz'); ?></option>
                                            <option value="-2"><?php _e('only registered users',
                                                    'wp-adv-quiz'); ?></option>
                                            <option value="-3"><?php _e('only anonymous users',
                                                    'wp-adv-quiz'); ?></option>
                                        </optgroup>

                                        <optgroup label="<?php _e('User', 'wp-adv-quiz'); ?>">
                                            <?php foreach ($this->users as $user) {
                                                if ($user->ID == 0) {
                                                    continue;
                                                }

                                                echo '<option value="', esc_attr($user->ID), '">', $user->user_login, ' (', $user->display_name, ')</option>';
                                            } ?>
                                        </optgroup>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <label>
                                    <?php _e('How many entries should be shown on one page:', 'wp-adv-quiz'); ?>
                                    <select id="wpAdvQuiz_historyPageLimit">
                                        <option>1</option>
                                        <option selected="selected">10</option>
                                        <option>50</option>
                                        <option>100</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <?php
                                $dateVon = '<input type="text" id="datepickerFrom">';
                                $dateBis = '<input type="text" id="datepickerTo">';

                                printf(__('Search to date limit from %s to %s', 'wp-adv-quiz'), $dateVon, $dateBis);
                                ?>
                            </li>
                            <li>
                                <input type="button" value="<?php _e('Filter', 'wp-adv-quiz'); ?>"
                                       class="button-secondary" id="filter">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="wpAdvQuiz_loadDataHistory" class="wpAdvQuiz_blueBox"
                 style="background-color: #F8F5A8; display: none;">
                <img alt="load"
                     src="data:image/gif;base64,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">
                <?php _e('Loading', 'wp-adv-quiz'); ?>
            </div>

            <div id="wpAdvQuiz_historyLoadContext"></div>

            <div style="margin-top: 10px;">

                <div style="float: left;" id="historyNavigation">
                    <input style="font-weight: bold;" class="button-secondary navigationLeft" value="&lt;"
                           type="button">
                    <select class="navigationCurrentPage">
                        <option value="1">1</option>
                    </select>
                    <input style="font-weight: bold;" class="button-secondary navigationRight" value="&gt;"
                           type="button">
                </div>

                <div style="float: right;">
                    <a class="button-secondary wpAdvQuiz_update" href="#"><?php _e('Refresh', 'wp-adv-quiz'); ?></a>
                    <a class="button-secondary wpAdvQuiz_export" href="#"><?php _e('Export', 'wp-adv-quiz'); ?></a>
                    <?php if (current_user_can('wpAdvQuiz_reset_statistics')) { ?>
                        <a class="button-secondary wpAdvQuiz_resetComplete" href="#"><?php _e('Reset entire statistic',
                                'wp-adv-quiz'); ?></a>
                    <?php } ?>
                </div>

                <div style="clear: both;"></div>
            </div>

        </div>
        <?php
    }

    private function showModalWindow()
    {
        ?>

        <div id="wpAdvQuiz_user_overlay" style="display: none;">
            <div class="wpAdvQuiz_modal_window" style="padding: 20px; overflow: scroll;">
                <input type="button" value="<?php _e('Close'); ?>" class="button-primary"
                       style=" position: fixed; top: 48px; right: 59px; z-index: 160001;" id="wpAdvQuiz_overlay_close">

                <div id="wpAdvQuiz_user_content" style="margin-top: 20px;"></div>

                <div id="wpAdvQuiz_loadUserData" class="wpAdvQuiz_blueBox"
                     style="background-color: #F8F5A8; display: none; margin: 50px;">
                    <img alt="load"
                         src="data:image/gif;base64,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">
                    <?php _e('Loading', 'wp-adv-quiz'); ?>
                </div>
            </div>
            <div class="wpAdvQuiz_modal_backdrop"></div>
        </div>

        <?php
    }

    private function showTabOverview()
    {
        ?>
        <div id="wpAdvQuiz_tabOverview" class="wpAdvQuiz_tabContent" style="display: none;">
            <div id="poststuff">
                <div class="postbox">
                    <h3 class="hndle"><?php _e('Filter', 'wp-adv-quiz'); ?></h3>

                    <div class="inside">
                        <ul>
                            <li>
                                <label>
                                    <?php _e('Show only users, who solved the quiz:', 'wp-adv-quiz'); ?>
                                    <input type="checkbox" value="1" id="wpAdvQuiz_overviewOnlyCompleted">
                                </label>
                            </li>
                            <li>
                                <label>
                                    <?php _e('How many entries should be shown on one page:', 'wp-adv-quiz'); ?>
                                    <select id="wpAdvQuiz_overviewPageLimit">
                                        <option>1</option>
                                        <option>4</option>
                                        <option selected="selected">50</option>
                                        <option>100</option>
                                        <option>500</option>
                                        <option>1000</option>
                                    </select>
                                </label>
                            </li>
                            <li>
                                <input type="button" value="<?php _e('Filter', 'wp-adv-quiz'); ?>"
                                       class="button-secondary" id="overviewFilter">
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div id="wpAdvQuiz_loadDataOverview" class="wpAdvQuiz_blueBox"
                 style="background-color: #F8F5A8; display: none;">
                <img alt="load"
                     src="data:image/gif;base64,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">
                <?php _e('Loading', 'wp-adv-quiz'); ?>
            </div>

            <div id="wpAdvQuiz_overviewLoadContext"></div>

            <div style="margin-top: 10px;">

                <div style="float: left;" id="overviewNavigation">
                    <input style="font-weight: bold;" class="button-secondary navigationLeft" value="&lt;"
                           type="button">
                    <select class="navigationCurrentPage">
                        <option value="1">1</option>
                    </select>
                    <input style="font-weight: bold;" class="button-secondary navigationRight" value="&gt;"
                           type="button">
                </div>

                <div style="float: right;">
                    <a class="button-secondary wpAdvQuiz_update" href="#"><?php _e('Refresh', 'wp-adv-quiz'); ?></a>
                    <a class="button-secondary wpAdvQuiz_export" href="#"><?php _e('Export', 'wp-adv-quiz'); ?></a>
                    <?php if (current_user_can('wpAdvQuiz_reset_statistics')) { ?>
                        <a class="button-secondary wpAdvQuiz_resetComplete" href="#"><?php _e('Reset entire statistic',
                                'wp-adv-quiz'); ?></a>
                    <?php } ?>
                </div>

                <div style="clear: both;"></div>
            </div>

        </div>
        <?php
    }

    protected function showExportListBox()
    {
        ?>

        <div id="wpAdvQuiz_statsticExportList_box" style="display: none;">
            <div class="wpAdvQuiz_exportList">
                <form action="" method="POST">
                    <h3 style="margin-top: 0;"><?php _e('Export', 'wp-adv-quiz'); ?></h3>

                    <p><?php echo __('Please select a format', 'wp-adv-quiz'); ?></p>

                    <?php do_action('wpAdvQuiz_view_statistics_exportListBox', $this); ?>

                    <div style="margin-bottom: 15px;">
                        <label><?php _e('Format:'); ?></label>
                        <select name="exportType">
                            <?php
                            foreach ($this->supportedExportFormats as $key => $value) {
                                echo '<option value="'.esc_attr($key).'">'.esc_html($value).'</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <input class="button-primary" name="exportStart" id="exportStart"
                           value="<?php echo __('Start export', 'wp-adv-quiz'); ?>" type="submit">
                </form>
            </div>
        </div>

        <?php
    }
}
