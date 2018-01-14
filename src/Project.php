<?php

namespace kozhemin\TopVisor;

/**
 * Class Project
 * @package kozhemin\TopVisor
 *
 * @property integer id Project id
 * @method integer getId() Project id
 *
 * @property string name Project name
 * @method string getName() Project name
 *
 * @property string site Project site
 * @method string getSite() Project site
 *
 * @property integer user_id Project user_id
 * @method integer getUser_id() Project user_id
 *
 * @property string right Project right
 * @method string getRight() Project right
 *
 * @property string update Project update
 * @method string getUpdate() Project update
 *
 * @property integer favorite Project favorite
 * @method integer getFavorite() Project favorite
 *
 * @property string tags Project tags
 * @method string getTags() Project tags
 *
 * @property string user_email Project user_email
 * @method string getUser_email() Project user_email
 *
 * @property integer status_positions Project status_positions
 * @method integer getStatus_positions() Project status_positions
 *
 * @property integer status_volumes Project status_volumes
 * @method integer getStatus_volumes() Project status_volumes
 *
 * @property integer status_claster Project status_claster
 * @method integer getStatus_claster() Project status_claster *
 *
 * @property integer on Project on
 * @method integer getOn() Project on
 *
 * @property string time_for_update Project time_for_update
 * @method string getTime_for_update() Project on
 *
 * @property integer auto_cond Project auto_cond
 * @method integer getAuto_cond() Project auto_cond
 *
 * @property integer wait_after_updates Project wait_after_updates
 * @method integer getWait_after_updates() Project wait_after_updates
 *
 * @property integer subdomains Project subdomains
 * @method integer getSubdomains() Project subdomains
 *
 * @property integer filter Project filter
 * @method integer getFilter() Project filter
 *
 * @property integer auto_correct Project auto_correct
 * @method integer getAuto_correct() Project auto_correct
 *
 * @property integer with_snippets Project with_snippets
 * @method integer getWith_snippets() Project with_snippets
 *
 * @property integer do_snapshots Project do_snapshots
 * @method integer getDo_snapshots() Project do_snapshots
 *
 * @property integer report_on Project report_on
 * @method integer getReport_on() Project report_on
 *
 * @property string report_time Project report_time
 * @method string getReport_time() Project report_time
 *
 * @property string report_format Project report_format
 * @method string getReport_format() Project report_format
 *
 * @property string common_traffic Project common_traffic
 * @method string getCommon_traffic() Project common_traffic
 *
 * @property integer guest_link_right Project guest_link_right
 * @method integer getGuest_link_right() Project guest_link_right
 *
 * @property integer broker_count_campaigns Project broker_count_campaigns
 * @method integer getBroker_count_campaigns() Project broker_count_campaigns
 *
 * @property integer broker_count_banners Project broker_count_banners
 * @method integer getBroker_count_banners() Project broker_count_banners
 *
 * @property object domain_expire Project domain_expire
 * @method object getDomain_expire() Project domain_expire
 *
 * @property array history Project history
 * @method array getHistory() Project history
 *
 * @property array searchers Project history
 * @method array getSearchers() Project history
 *
 * @property array positions_summary Project positions_summary
 * @method array getPositions_summary() Project positions_summary
 *
 * @property array registrator_data Project registrator_data
 * @method array getRegistrator_data() Project registrator_data
 *
 */

class Project extends BaseObject implements DefaultFields
{

    /**
     * Project default fields
     * @link https://topvisor.ru/api/v2-services/projects_2/projects/
     * @return array
     */
    public static function defaultFields(): array
    {
        return [
            'id',
            'name',
            'site',
            'user_id',
            'right',
            'update',
            'favorite',
            'tags',
            'user_email',
            'status_positions',
            'status_volumes',
            'status_claster',
            'on',
            'time_for_update',
            'auto_cond',
            'wait_after_updates',
            'subdomains',
            'filter',
            'auto_correct',
            'with_snippets',
            'do_snapshots',
            'report_on',
            'report_time',
            'report_format',
            'common_traffic',
            'guest_link_right',
            'broker_count_campaigns',
            'broker_count_banners',
            'domain_expire',
        ];
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws TopVisorException
     */
    public function getKeywords($params = [])
    {
        return $this->topVisor->getKeywords($this->id, $params);
    }

    /**
     * @param $name
     * @param $groupId
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function addKeyword($name, $groupId, $params = [])
    {
        return $this->topVisor->addKeyword($this->id, $name, $groupId, $params);
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws TopVisorException
     */
    public function getFolders($params = [])
    {
        return $this->topVisor->getFolders($this->id, $params);
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function addFolder($name)
    {
        return $this->topVisor->addFolder($this->id, $name);
    }

    /**
     * @param array $params
     *
     * @return array
     * @throws TopVisorException
     */
    public function getGroups($params = [])
    {
        return $this->topVisor->getGroups($this->id, $params);
    }

    /**
     * @param $name
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function addGroup($name, $params = [])
    {
        return $this->topVisor->addGroup($this->id, $name, $params);
    }

}