<?php

namespace kozhemin\TopVisor;

/**
 * Class TopVisorException
 *
 * @package TopVisor
 */
class TopVisorException extends \Exception
{
    protected $code;
    protected $errorString;

    /**
     * TopVisorException constructor.
     *
     * @param $errors
     */
    public function __construct($errors)
    {
        $currentError = array_shift($errors);
        $this->code = $currentError->code;
        $this->errorString = $currentError->string;
        $message = $this->formatErrorByCode();

        parent::__construct($message);
    }

    /**
     * @return string
     */
    public function formatErrorByCode(): string
    {
        $errorInfo = $this->getError();
        $errorMessage = "\nError Code: " . $this->code;
        $errorMessage .= "\nError String: " . $this->errorString;

        if ($errorInfo) {
            $errorMessage .= "\nError Description: " . $errorInfo['description'];
            $errorMessage .= "\nError Reason: " . $errorInfo['reason'];
        }

        return $errorMessage;
    }

    /**
     * @return mixed|string
     */
    public function getError(): array
    {
        return self::topVisorErrors()[$this->code] ?? [];
    }

    /**
     * @link https://topvisor.ru/api/v2/errors/codes/
     * @return array
     */
    protected static function topVisorErrors(): array
    {
        return [
            0 => [
                'description' => 'Нетипизированные ошибки',
                'reason' => 'Описание ошибки см. в ответе API'
            ],
            503 => [
                'description' => 'Функции API временно недоступны',
                'reason' => 'На сервере ведутся технические работы'
            ],
            429 => [
                'description' => 'Превышен лимит одновременных обращений к API',
                'reason' => 'С одного IP поступило более 5 одновременных запросов. Для одного User-Id поступило более 5 одновременных запросов'
            ],
            53 => [
                'description' => 'Ошибка авторизации',
                'reason' => 'Авторизационный токен не указан или указан неверно. ID пользователя не указан или указан неверно. Токен не соответствует ID пользователя'
            ],
            54 => [
                'description' => 'Нет прав',
                'reason' => 'Нет прав на просмотр запрашиваемых данных объекта. Нет прав на редактирование указанного объекта'
            ],
            1000 => [
                'description' => 'Неверное имя запроса',
                'reason' => ''
            ],
            1001 => [
                'description' => 'Неверно указан оператор',
                'reason' => ''
            ],
            1002 => [
                'description' => 'Неверно указан сервис',
                'reason' => ''
            ],
            1003 => [
                'description' => 'Неверно указан метод',
                'reason' => 'Указанный метод отсутствует в указанном сервисе. Указанный метод не соответствует указанному оператору'
            ],
            1004 => [
                'description' => 'Неверная версия API',
                'reason' => ''
            ],
            2000 => [
                'description' => 'Ошибка в формате передаваемых данных',
                'reason' => ''
            ],
            2001 => [
                'description' => 'Отсутствует обязательный параметр',
                'reason' => ''
            ],
            2002 => [
                'description' => 'Указан параметр с неверным типом',
                'reason' => 'Вместо числа передан другой тип. Вместо объекта передан другой тип'
            ],
            2003 => [
                'description' => 'Указан параметр с неверным значением',
                'reason' => ''
            ],
            2004 => [
                'description' => 'Неверно указаны параметры фильтра fitlers',
                'reason' => 'fitlers имеет неверную стркутуру. Элемент fitlers имеет неверный оператор. Элемент fitlers имеет неверный операнд'
            ],
            2005 => [
                'description' => 'Неверно указаны параметры пагинации',
                'reason' => 'limits или offset являются отрицательными. limits превышает 10000'
            ],
        ];
    }
}
