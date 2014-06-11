<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Actions;

use Piwik\ArchiveProcessor;
use Piwik\Common;
use Piwik\Db;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\HtmlTable;
use Piwik\Site;

/**
 * Actions plugin
 *
 * Reports about the page views, the outlinks and downloads.
 *
 */
class Actions extends \Piwik\Plugin
{
    const ACTIONS_REPORT_ROWS_DISPLAY = 100;

    /**
     * @see Piwik\Plugin::getListHooksRegistered
     */
    public function getListHooksRegistered()
    {
        $hooks = array(
            'ViewDataTable.configure'         => 'configureViewDataTable',
            'AssetManager.getStylesheetFiles' => 'getStylesheetFiles',
            'AssetManager.getJavaScriptFiles' => 'getJsFiles',
            'Insights.addReportToOverview'    => 'addReportToInsightsOverview'
        );
        return $hooks;
    }

    public function addReportToInsightsOverview(&$reports)
    {
        $reports['Actions_getPageUrls']   = array();
        $reports['Actions_getPageTitles'] = array();
        $reports['Actions_getDownloads']  = array('flat' => 1);
    }

    public function getStylesheetFiles(&$stylesheets)
    {
        $stylesheets[] = "plugins/Actions/stylesheets/dataTableActions.less";
    }

    public function getJsFiles(&$jsFiles)
    {
        $jsFiles[] = "plugins/Actions/javascripts/actionsDataTable.js";
    }

    public function isSiteSearchEnabled()
    {
        $idSite  = Common::getRequestVar('idSite', 0, 'int');
        $idSites = Common::getRequestVar('idSites', '', 'string');
        $idSites = Site::getIdSitesFromIdSitesString($idSites, true);

        if (!empty($idSite)) {
            $idSites[] = $idSite;
        }

        if (empty($idSites)) {
            return false;
        }

        foreach ($idSites as $idSite) {
            if (!Site::isSiteSearchEnabledFor($idSite)) {
                return false;
            }
        }

        return true;
    }

    static public function checkCustomVariablesPluginEnabled()
    {
        if (!self::isCustomVariablesPluginsEnabled()) {
            throw new \Exception("To Track Site Search Categories, please ask the Piwik Administrator to enable the 'Custom Variables' plugin in Settings > Plugins.");
        }
    }

    static public function isCustomVariablesPluginsEnabled()
    {
        return \Piwik\Plugin\Manager::getInstance()->isPluginActivated('CustomVariables');
    }

    public function configureViewDataTable(ViewDataTable $view)
    {
        if ($this->pluginName == $view->requestConfig->getApiModuleToRequest()) {
            if ($view->isRequestingSingleDataTable()) {
                // make sure custom visualizations are shown on actions reports
                $view->config->show_all_views_icons = true;
                $view->config->show_bar_chart = false;
                $view->config->show_pie_chart = false;
                $view->config->show_tag_cloud = false;
            }
        }
    }

    /**
     * @param \Piwik\DataTable $dataTable
     * @param int $level
     */
    public static function setDataTableRowLevels($dataTable, $level = 0)
    {
        foreach ($dataTable->getRows() as $row) {
            $row->setMetadata('css_class', 'level' . $level);

            $subtable = $row->getSubtable();
            if ($subtable) {
                self::setDataTableRowLevels($subtable, $level + 1);
            }
        }
    }


}

