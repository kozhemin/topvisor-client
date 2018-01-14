<?php

namespace kozhemin\TopVisor;

/**
 * Class Group
 * @package kozhemin\TopVisor
 *
 * @property integer id Group id
 * @method integer getId() Group id
 *
 * @property integer project_id Group project_id
 * @method integer getProject_id() Group project_id
 *
 * @property string name Group name
 * @method string getName() Group name
 *
 * @property integer on Group on
 * @method integer getOn() Group on
 *
 * @property integer status Group status
 * @method integer getStatus() Group status
 *
 * @property integer ord Group ord
 * @method integer getOrd() Group ord
 *
 * @property integer folder_id Group folder_id
 * @method integer getFolder_id() Group folder_id
 *
 * @property string folder_path Group folder_path
 * @method string getFolder_path() Group folder_path
 *
 * @property integer count_keywords Group count_keywords
 * @method integer getCount_keywords() Group count_keywords
 *
 */
class Group extends BaseObject implements DefaultFields
{
    /**
     * Group default fields
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/
     * @return array
     */
    public static function defaultFields(): array
    {
        return [
            'id',
            'project_id',
            'folder_id',
            'name',
            'on',
            'status',
            'ord',
            'folder_path',
            'count_keywords',
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
        return $this->topVisor->renameGroup($this->project_id, $name, $this->id);
    }

    /**
     * @param $on
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function on($on)
    {
        return $this->topVisor->onGroup($this->project_id, $on, $this->id);
    }

    /**
     * @param $toGroupId
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function move($toGroupId, $params = [])
    {
        return $this->topVisor->moveGroup($this->project_id, $this->id, $toGroupId, $params);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function remove()
    {
        return $this->topVisor->removeGroup($this->project_id, $this->id);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemove()
    {
        return $this->topVisor->unRemoveGroup(abs($this->project_id), $this->id);
    }

    /**
     * @return Project|null
     * @throws TopVisorException
     */
    public function getProject()
    {
        return $this->topVisor->getProject($this->project_id);
    }

    /**
     * @return Folder|null
     * @throws TopVisorException
     */
    public function getFolder()
    {
        return $this->topVisor->getFolder($this->project_id, $this->folder_id);
    }
}