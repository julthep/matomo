<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 *
 */
namespace Piwik\Plugins\Events\Reports;

use Piwik\Piwik;
use Piwik\Plugin\ViewDataTable;
use Piwik\Plugins\Events\Columns\EventName;

class GetName extends Base
{
    protected function init()
    {
        parent::init();
        $this->dimension     = new EventName();
        $this->name          = Piwik::translate('Events_EventNames');
        $this->documentation = ''; // TODO
        $this->metrics       = array('nb_events', 'sum_event_value', 'min_event_value', 'max_event_value', 'avg_event_value', 'nb_events_with_value');
        $this->actionToLoadSubTables = 'getActionFromNameId';
        $this->order = 2;
        $this->widgetTitle  = 'Events_EventNames';
    }
}
