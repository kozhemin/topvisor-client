<?php

namespace kozhemin\TopVisor;

/**
 * Class Folder
 * @package kozhemin\TopVisor
 *
 * @property integer id Folder id
 * @method integer getId() Folder id
 *
 * @property integer project_id Folder project_id
 * @method integer getProject_id() Folder project_id
 *
 * @property integer parent_id Folder parent_id
 * @method integer getParent_id() Folder parent_id
 *
 * @property string name Folder name
 * @method string getName() Folder name
 *
 * @property integer count_Folder Folder count_Folder
 * @method integer getCount_Folder() Folder count_Folder
 *
 * @property integer count_groups Folder count_groups
 * @method integer getCount_groups() Folder count_groups
 *
 * @property integer ord Folder ord
 * @method integer getOrd() Folder ord
 *
 */
class Folder extends BaseObject implements DefaultFields
{
    /**
     * Folder default fields
     * @link https://topvisor.ru/api/v2-services/keywords_2/Folder/
     * @return array
     */
    public static function defaultFields(): array
    {
        return [
            'id',
            'project_id',
            'parent_id',
            'name',
            'count_groups',
            'ord',
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
        return $this->topVisor->renameFolder($this->project_id, $name, $this->id);
    }

    /**
     * @param array $params
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function move($params = [])
    {
        return $this->topVisor->moveFolder($this->project_id, $this->id, $params);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function remove()
    {
        return $this->topVisor->removeFolder($this->project_id, $this->id);
    }

    /**
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemove()
    {
        return $this->topVisor->unRemoveFolder($this->project_id, $this->id);
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
}