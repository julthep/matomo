<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\UserSettings\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\UserSettings\Columns\Operatingsystemfamily;

class GetOSFamily extends Base
{
    protected function init()
    {
        parent::init();
        $this->dimension     = new Operatingsystemfamily();
        $this->name          = Piwik::translate('UserSettings_OperatingSystemFamily');
        $this->documentation = ''; // TODO
        $this->order = 8;
        $this->widgetTitle  = 'UserSettings_OperatingSystemFamily';
    }

    public function configureView(ViewDataTable $view)
    {
        $this->getBasicUserSettingsDisplayProperties($view);

        $view->config->title = Piwik::translate('UserSettings_OperatingSystemFamily');
        $view->config->addTranslation('label', Piwik::translate('UserSettings_OperatingSystemFamily'));
        $view->config->addRelatedReports($this->getOsRelatedReports());
    }

}
