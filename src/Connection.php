<?php
/**
 * @link https://github.com/kozhemin
 * @copyright Copyright (c) 08.01.18 Kozhemin Egor
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace kozhemin\TopVisor;

/**
 * Class Connection
 * @package kozhemin\TopVisor
 */
class Connection
{

    /**
     * @var null|resource cURL component
     */
    private $http = null;

    /**
     * @var string Base URL of the API
     */
    private $base_url = 'https://api.topvisor.ru/v2/json';

    /**
     * @var array Request headers
     */
    private $headers = [];

    /**
     * @var array Request Cookies
     */
    private $cookies = [];

    private $user_agent = 'Topvisor_API'; // Use this as user agent string.

    /**
     * @var bool Enables/Disables SSL verification when sending requests using cURL
     */
    private $verify_ssl = true;

    /**
     * @var int Connection timeout
     */
    private $connectTimeout; // seconds

    private $timeout; // seconds

    public function __construct($token, $userId, $connectTimeout = null, $timeout = null, $verifySsl = true)
    {
        $this->http = curl_init();

        $this->setConnectTimeout($connectTimeout);
        $this->setTimeout($timeout);
        $this->setVerifySsl($verifySsl);
        $this->tokenLogin($token, $userId);
    }

    /**
     * @return bool
     */
    public function getVerifySsl()
    {
        return $this->verify_ssl;
    }

    /**
     * Use this method to enable or disable the ssl_verifypeer option of curl.
     * This is usefull if you use self-signed ssl certificates.
     *
     * @param bool $verify_ssl
     *
     * @return void
     */
    public function setVerifySsl($verify_ssl)
    {
        $this->verify_ssl = $verify_ssl;
    }

    /**
     * @param int $timeout seconds
     *
     * @return $this
     */
    public function setTimeout($timeout)
    {
        $this->timeout = (int)$timeout;

        return $this;
    }

    /**
     * @param int $connectTimeout seconds
     *
     * @return $this
     */
    public function setConnectTimeout($connectTimeout)
    {
        $this->connectTimeout = (int)$connectTimeout;

        return $this;
    }

    /**
     * @param $token
     * @param $userId
     */
    protected function tokenLogin($token, $userId)
    {
        $this->headers[CURLOPT_HTTPHEADER] = [
            'Cache-Control: no-cache',
            'Content-Type: application/json; charset=utf-8',
            sprintf('User-Id: %d', $userId),
            sprintf('Authorization: Bearer %s', $token)
        ];
    }

    /**
     * @param $url
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2/basic-params/
     *
     * @return mixed
     * @throws TopVisorException
     */
    protected function request($url, $params = [])
    {
        if (
            substr($url, 0, strlen('http://')) != 'http://' &&
            substr($url, 0, strlen('https://')) != 'https://'
        ) {
            $url = $this->base_url . $url;
        }

        $this->http = curl_init();
        $headers = $this->headers;

        curl_setopt($this->http, CURLOPT_URL, $url);
        curl_setopt($this->http, CURLOPT_POST, true);
        curl_setopt($this->http, CURLOPT_USERAGENT, $this->user_agent);
        curl_setopt($this->http, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($this->http, CURLOPT_HTTPHEADER, $headers[CURLOPT_HTTPHEADER]);
        curl_setopt($this->http, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->http, CURLOPT_SSL_VERIFYPEER, $this->verify_ssl);
        if (!$this->verify_ssl) {
            curl_setopt($this->http, CURLOPT_SSL_VERIFYHOST, 0);
        }
        curl_setopt($this->http, CURLOPT_COOKIE, implode(';', $this->cookies));
        if (is_numeric($this->connectTimeout)) {
            curl_setopt($this->http, CURLOPT_CONNECTTIMEOUT, $this->connectTimeout);
        }
        if (is_numeric($this->timeout)) {
            curl_setopt($this->http, CURLOPT_TIMEOUT, $this->timeout);
        }
        $content = json_decode(curl_exec($this->http));
        curl_close($this->http);

        if (isset($content->errors)) {
            throw new TopVisorException($content->errors);
        }

        return $content;
    }

    /**
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2/basic-params/
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function getBank($params = [])
    {
        return $this->request('/get/bank_2/history', $params);
    }

    /**
     * @param array $params
     * @links https://topvisor.ru/api/v2-services/projects_2/projects/get/
     *
     * @link https://topvisor.ru/api/v2/basic-params/
     * @link https://topvisor.ru/api/v2-services/projects_2/projects/
     *
     * @return array Project|null
     * @throws TopVisorException
     */
    public function getProjects($params = [])
    {
        $projects = [];
        $params = $this->mergeFields($params, Project::defaultFields());
        $response = $this->request('/get/projects_2/projects', $params);
        if ($response->result) {
            foreach ($response->result as $item) {
                $projects[] = new Project($item, $this);
            }
        }
        return $projects;
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2/basic-params/
     * @link https://topvisor.ru/api/v2-services/projects_2/projects/
     *
     * @return Project|null
     * @throws TopVisorException
     */
    public function getProject($projectId, $params = [])
    {
        if ($projectId) {
            $params = $this->mergeFields($params + ['id' => $projectId], Project::defaultFields());
            $response = array_shift($this->request('/get/projects_2/projects', $params)->result);
            return new Project($response, $this);
        }
        return null;
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2/basic-params/
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/
     *
     * @return array Keyword|null
     * @throws TopVisorException
     */
    public function getKeywords($projectId, $params = [])
    {
        $keywords = [];
        $params = $this->mergeFields($params + ['project_id' => $projectId], Keyword::defaultFields());
        $response = $this->request('/get/keywords_2/keywords', $params);
        if ($response->result) {
            foreach ($response->result as $item) {
                $keywords[] = new Keyword($item, $this);
            }
        }
        return $keywords;
    }

    /**
     * @param $projectId
     * @param $keywordId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/get/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return Keyword|null
     * @throws TopVisorException
     */
    public function getKeyword($projectId, $keywordId, $params = [])
    {
        if ($keywordId) {
            $params = $this->mergeFields($params + ['id' => $keywordId, 'project_id' => $projectId],
                Keyword::defaultFields());
            $response = array_shift($this->request('/get/keywords_2/keywords', $params)->result);
            return new Keyword($response, $this);
        }
        return null;
    }

    /**
     * @param $projectId
     * @param $name
     * @param $groupId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/add/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function addKeyword($projectId, $name, $groupId, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'name' => $name, 'to_id' => $groupId]);
        return $this->request('/add/keywords_2/keywords', $params);
    }

    /**
     * @param $projectId
     * @param $name
     * @param $keywordId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-rename/
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function renameKeyword($projectId, $name, $keywordId)
    {
        $params = ['project_id' => $projectId, 'name' => $name, 'id' => $keywordId];
        return $this->request('/edit/keywords_2/keywords/rename', $params);
    }

    /**
     * @param $projectId
     * @param $keywordId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/del/
     *
     * @return mixed
     * @throws TopVisorException
     */
    public function removeKeyword($projectId, $keywordId)
    {
        $params = ['project_id' => $projectId, 'id' => $keywordId];
        return $this->request('/del/keywords_2/keywords', $params);
    }

    /**
     * @param $projectId
     * @param $keywordId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-undel/
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemoveKeyword($projectId, $keywordId)
    {
        $params = ['project_id' => $projectId, 'id' => $keywordId];
        return $this->request('/edit/keywords_2/keywords/undel', $params);
    }

    /**
     * @param $projectId
     * @param $groupId
     * @param $keywordId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-move/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function moveKeyword($projectId, $groupId, $keywordId, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'to_id' => $groupId, 'id' => $keywordId]);
        return $this->request('/edit/keywords_2/keywords/move', $params);
    }

    /**
     * @param $projectId
     * @param $target
     * @param $keywordId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-target/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function setTargetKeyword($projectId, $target, $keywordId, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'target' => $target, 'id' => $keywordId]);
        return $this->request('/edit/keywords_2/keywords/target', $params);
    }

    /**
     * @param $projectId
     * @param array $tagsId
     * @param $keywordId
     * @param string $action
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-tags/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function setTagKeyword($projectId, array $tagsId, $keywordId, $action = 'add', $params = [])
    {
        $params = array_merge($params,
            [
                'project_id' => $projectId,
                'tags' => $tagsId,
                'action' => $action,
                'id' => $keywordId
            ]);
        return $this->request('/edit/keywords_2/keywords/tags', $params);
    }

    /**
     * @param $fromProjectId
     * @param $toProjectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/keywords/edit-export-toProject/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function copyKeywordToProject($fromProjectId, $toProjectId, $params = [])
    {
        $params = array_merge($params, ['project_id' => $fromProjectId, 'to_project_id' => $toProjectId]);
        return $this->request('/edit/keywords_2/keywords/export/toProject', $params);
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/get/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return array Folder
     * @throws TopVisorException
     */
    public function getFolders($projectId, $params = [])
    {
        $folders = [];
        $params = $this->mergeFields($params + ['project_id' => $projectId], Folder::defaultFields());
        $response = $this->request('/get/keywords_2/folders', $params);
        if ($response->result) {
            foreach ($response->result as $item) {
                $folders[] = new Folder($item, $this);
            }
        }
        return $folders;
    }

    /**
     * @param $projectId
     * @param $folderId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/get/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return Folder|null
     * @throws TopVisorException
     */
    public function getFolder($projectId, $folderId, $params = [])
    {
        if ($folderId) {
            $params = $this->mergeFields($params + ['project_id' => $projectId], Folder::defaultFields());
            $response = $this->request('/get/keywords_2/folders', $params);
            if ($response->result) {
                foreach ($response->result as $item) {
                    if ($item->id == $folderId) {
                        return new Folder($item, $this);
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param $projectId
     * @param $name
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/add/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function addFolder($projectId, $name, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'name' => $name]);
        return $this->request('/add/keywords_2/folders', $params);
    }


    /**
     * @param $projectId
     * @param $name
     * @param $folderId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/edit-rename/
     * @return mixed
     * @throws TopVisorException
     */
    public function renameFolder($projectId, $name, $folderId)
    {
        $params = ['project_id' => $projectId, 'name' => $name, 'id' => $folderId];
        return $this->request('/edit/keywords_2/folders/rename', $params);
    }

    /**
     * @param $projectId
     * @param $folderId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/edit-move/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function moveFolder($projectId, $folderId, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'id' => $folderId]);
        return $this->request('/edit/keywords_2/folders/move', $params);
    }

    /**
     * @param $projectId
     * @param $folderId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/del/
     * @return mixed
     * @throws TopVisorException
     */
    public function removeFolder($projectId, $folderId)
    {
        $params = ['project_id' => $projectId, 'id' => $folderId];
        return $this->request('/del/keywords_2/folders', $params);
    }

    /**
     * @param $projectId
     * @param $folderId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/folders/edit-undel/
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemoveFolder($projectId, $folderId)
    {
        $params = ['project_id' => $projectId, 'id' => $folderId];
        return $this->request('/edit/keywords_2/folders/undel', $params);
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/get/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return array Group
     * @throws TopVisorException
     */
    public function getGroups($projectId, $params = [])
    {
        $groups = [];
        $params = $this->mergeFields($params + ['project_id' => $projectId], Group::defaultFields());
        $response = $this->request('/get/keywords_2/groups', $params);
        if ($response->result) {
            foreach ($response->result as $item) {
                $groups[] = new Group($item, $this);
            }
        }
        return $groups;
    }

    /**
     * @param $projectId
     * @param $groupId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/get/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return Group|null
     * @throws TopVisorException
     */
    public function getGroup($projectId, $groupId, $params = [])
    {
        if ($groupId) {
            $params = $this->mergeFields($params + ['project_id' => $projectId], Group::defaultFields());
            $response = $this->request('/get/keywords_2/groups', $params);
            if ($response->result) {
                foreach ($response->result as $item) {
                    if ($item->id == $groupId) {
                        return new Group($item, $this);
                    }
                }
            }
        }
        return null;
    }

    /**
     * @param $projectId
     * @param $name
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/add/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function addGroup($projectId, $name, $params = [])
    {
        $params = array_merge($params, ['project_id' => $projectId, 'name' => $name]);
        return $this->request('/add/keywords_2/groups', $params);
    }

    /**
     * @param $projectId
     * @param $name
     * @param $groupId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/edit-rename/
     * @return mixed
     * @throws TopVisorException
     */
    public function renameGroup($projectId, $name, $groupId)
    {
        $params = ['project_id' => $projectId, 'name' => $name, 'id' => $groupId];
        return $this->request('/edit/keywords_2/groups/rename', $params);
    }

    /**
     * @param $projectId
     * @param int $on
     * @param $groupId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/edit-on/
     * @return mixed
     * @throws TopVisorException
     */
    public function onGroup($projectId, $on = 1, $groupId)
    {
        $params = ['project_id' => $projectId, 'on' => $on, 'id' => $groupId];
        return $this->request('/edit/keywords_2/groups/on', $params);
    }

    /**
     * @param $projectId
     * @param $groupId
     * @param $toGroupId
     * @param $params
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/edit-move/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function moveGroup($projectId, $groupId, $toGroupId, $params)
    {
        $params = array_merge($params, ['project_id' => $projectId, 'id' => $groupId, 'to_id' => $toGroupId]);
        return $this->request('/edit/keywords_2/groups/move', $params);
    }

    /**
     * @param $projectId
     * @param $groupId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/del/
     * @return mixed
     * @throws TopVisorException
     */
    public function removeGroup($projectId, $groupId)
    {
        $params = ['project_id' => $projectId, 'id' => $groupId];
        return $this->request('/del/keywords_2/groups', $params);
    }

    /**
     * @param $projectId
     * @param $groupId
     *
     * @link https://topvisor.ru/api/v2-services/keywords_2/groups/edit-undel/
     * @return mixed
     * @throws TopVisorException
     */
    public function unRemoveGroup($projectId, $groupId)
    {
        $params = ['project_id' => $projectId, 'id' => $groupId];
        return $this->request('/edit/keywords_2/groups/undel', $params);
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/positions_2/edit-checker_go/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function positionsCheck($projectId, $params = [])
    {
        $params = array_merge($params, ['id' => $projectId]);
        return $this->request('/edit/positions_2/checker/go', $params)->result;
    }

    /**
     * @param $projectId
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/positions_2/get-checker_price/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function positionsCheckPrice($projectId, $params = [])
    {
        $params = array_merge($params, ['id' => $projectId]);
        return $this->request('/get/positions_2/checker/price', $params)->result;
    }

    /**
     * @param $projectId
     * @param array $regionsIndexes
     * @param $dateStart
     * @param $dateEnd
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/positions_2/get-history/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function positionsHistory($projectId, array $regionsIndexes, $dateStart, $dateEnd, $params = [])
    {
        $params = $this->mergeFields($params + [
                'project_id' => $projectId,
                'regions_indexes' => $regionsIndexes,
                'date1' => $dateStart,
                'date2' => $dateEnd,
            ], Keyword::defaultFields());
        return $this->request('/get/positions_2/history', $params)->result;
    }

    /**
     * @param $projectId
     * @param $regionIndex
     * @param $dateStart
     * @param $dateEnd
     * @param array $params
     *
     * @link https://topvisor.ru/api/v2-services/positions_2/get-summary/
     * @link https://topvisor.ru/api/v2/basic-params/
     * @return mixed
     * @throws TopVisorException
     */
    public function positionsSummary($projectId, $regionIndex, $dateStart, $dateEnd, $params = [])
    {
        $params = array_merge($params, [
            'project_id' => $projectId,
            'region_index' => $regionIndex,
            'dates' => [$dateStart, $dateEnd],
        ]);
        return $this->request('/get/positions_2/summary', $params)->result;
    }

    /**
     * @param array $params
     * @param array $defaultFields
     *
     * @return array
     */
    protected function mergeFields(array $params, array $defaultFields)
    {
        $retMergeFields = $params;
        if (array_key_exists('fields', $params)) {
            $retMergeFields['fields'] = array_unique(array_merge($params['fields'], $defaultFields));
        } else {
            $retMergeFields['fields'] = $defaultFields;
        }
        return $retMergeFields;
    }
}
