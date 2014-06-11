<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Referrers\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\CoreVisualizations\Visualizations\HtmlTable;
use Piwik\Plugins\Referrers\Columns\Referrer;

class GetAll extends Base
{
    protected function init()
    {
        parent::init();
        $this->dimension     = new Referrer();
        $this->name          = Piwik::translate('Referrers_WidgetGetAll');
        $this->documentation = Piwik::translate('Referrers_AllReferrersReportDocumentation', '<br />');
        $this->order = 2;
        $this->widgetTitle  = 'Referrers_WidgetGetAll';
    }

    public function configureView(ViewDataTable $view)
    {
        $setGetAllHtmlPrefix = array($this, 'setGetAllHtmlPrefix');

        $view->config->show_exclude_low_population = false;
        $view->config->show_goals = true;
        $view->config->addTranslation('label', Piwik::translate('Referrers_Referrer'));

        $view->requestConfig->filter_limit = 20;

        if ($view->isViewDataTableId(HtmlTable::ID)) {
            $view->config->disable_row_actions = true;
        }

        $view->config->filters[] = array('MetadataCallbackAddMetadata', array('referer_type', 'html_label_prefix', $setGetAllHtmlPrefix));
    }

}
