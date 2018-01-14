<?php

namespace kozhemin\TopVisor;

/**
 * Class Keyword
 * @package kozhemin\TopVisor
 *
 * @property integer id Keyword id
 * @method integer getId() Keyword id
 *
 * @property integer project_id Keyword project_id
 * @method integer getProject_id() Keyword project_id
 *
 * @property integer group_id Keyword group_id
 * @method integer getGroup_id() Keyword group_id
 *
 * @property string name Keyword name
 * @method string getName() Keyword name
 *
 * @property integer tags Keyword tags
 * @method integer getTags() Keyword tags
 *
 * @property integer target Keyword target
 * @method integer getTarget() Keyword target
 *
 * @property integer ord Keyword ord
 * @method integer getOrd() Keyword ord
 *
 * @property string group_name Keyword group_name
 * @method string getGroup_name() Keyword group_name
 *
 * @property string group_on Keyword group_on
 * @method string getGroup_on() Keyword group_on
 *
 * @property integer group_ord Keyword group_ord
 * @method integer getGroup_ord() Keyword group_ord
 *
 * @property integer group_folder_id Keyword group_folder_id
 * @method integer getGroup_folder_id() Keyword group_folder_id
 *
 * @property integer group_folder_path Keyword group_folder_path
 * @method integer getGroup_folder_path() Keyword group_folder_path
 *
 * @property integer target_status Keyword target_status
 * @method integer getTarget_status() Keyword target_status
 *
 * @property integer position Keyword position
 * @method integer getPosition() Keyword position
 *
 * @property integer relevant_url Keyword relevant_url
 * @method integer getRelevant_url() Keyword relevant_url
 *
 * @property integer visitors Keyword visitors
 * @method integer getVisitors() Keyword visitors
 *
 * @property integer volume Keyword volume
 * @method integer getVolume() Keyword volume
 *
 * @property integer cost_forecast Keyword cost_forecast
 * @method integer getCost_forecast() Keyword cost_forecast
 *
 */
class Keyword extends BaseObject implements DefaultFields
{

    /**
     * Keyword default fields
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/
     * @return array
     */
    public static function defaultFields(): array
    {
        return [
            'id',
            'project_id',
            'group_id',
            'name',
            'tags',
            'target',
            'ord',
            'group_name',
            'group_on',
            'group_ord',
            'group_folder_id',
            'group_folder_path',
        ];
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function rename($name)
    {
        return $this->topVisor->renameKeyword($this->project_id, $name, $this->id);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function remove()
    {
        return $this->topVisor->removeKeyword($this->project_id, $this->id);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemove()
    {
        return $this->topVisor->unRemoveKeyword(abs($this->project_id), $this->id);
    }

    /**
     * @param $groupId
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function move($groupId, $params = [])
    {
        return $this->topVisor->moveKeyword($this->project_id, $groupId, $this->id, $params);
    }

    /**
     * @param $target
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function serTarget($target, $params = [])
    {
        return $this->topVisor->setTargetKeyword($this->project_id, $target, $this->id, $params);
    }

    /**
     * @param array $tagsId
     * @param string $action
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function setTag(array $tagsId, $action = 'add', $params = [])
    {
        return $this->topVisor->setTagKeyword($this->project_id, $tagsId, $this->id, $action, $params);
    }

    /**
     * @param array $params
     *
     * @return Project|null
     * @throws TopVisorException
     */
    public function getProject($params = [])
    {
        return $this->topVisor->getProject($this->project_id, $params);
    }

    /**
     * @param array $params
     *
     * @return Folder|null
     * @throws TopVisorException
     */
    public function getFolder($params = [])
    {
        return $this->topVisor->getFolder($this->project_id, $this->group_folder_id, $params);
    }

    /**
     * @param array $params
     *
     * @return Group|null
     * @throws TopVisorException
     */
    public function getGroup($params = [])
    {
        return $this->topVisor->getGroup($this->project_id, $this->group_id, $params);
    }

}